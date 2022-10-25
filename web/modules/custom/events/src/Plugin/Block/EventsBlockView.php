<?php

namespace Drupal\events\Plugin\Block;

use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Cache\Cache;

/**
 * Block to display more events.
 *
 * @Block(
 *  id = "events_block",
 *  admin_label = @Translation("Events block")
 * )
 */
class EventsBlockView extends BlockBase
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        // https://www.kgaut.net/blog/2017/drupal-8-les-entityquery-par-lexemple.html
        $node = \Drupal::routeMatch()->getParameter('node');
        $now = new DrupalDateTime('now');
        $number_of_refs = 3;

        $events_ref_ids = \Drupal::entityQuery('node')
            ->condition('type', 'event')
            ->condition('nid', $node->id(), '<>') // Exclude current node
            ->condition('field_event_type', $node->field_event_type->target_id, '=') // Get same event type
            ->condition('field_date_end', $now->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT), '>=') // Get only events with end date available
            ->sort('field_date_start', 'ASC')
            ->execute();

        // Complete if need more content to display.
        if (count($events_ref_ids) < $number_of_refs) {
            $events_completion_ids = \Drupal::entityQuery('node')
                ->condition('type', 'event')
                ->condition('field_event_type', $node->field_event_type->target_id, '<>') // Get different event type
                ->condition('field_date_end', $now->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT), '>=') // Get only events with end date available
                ->sort('field_date_start', 'ASC')
                ->range(0, $number_of_refs - count($events_ref_ids)) // Complete number of results.
                ->execute();

            foreach ($events_completion_ids as $item) {
                array_push($events_ref_ids, $item);
            }
        }

        $events_ref = Node::loadMultiple($events_ref_ids);

        // Build the results.
        $build = \Drupal::entityTypeManager()->getViewBuilder('node')->viewMultiple($events_ref, 'teaser');

        return $build;
    }

    public function getCacheTags()
    {
        //With this when your node change your block will rebuild
        if ($node = \Drupal::routeMatch()->getParameter('node')) {
            //if there is node add its cachetag
            return Cache::mergeTags(parent::getCacheTags(), array('node:' . $node->id()));
        } else {
            //Return default tags instead.
            return parent::getCacheTags();
        }
    }

    public function getCacheContexts()
    {
        //if you depends on \Drupal::routeMatch()
        //you must set context of this block with 'route' context tag.
        //Every new route this block will rebuild
        return Cache::mergeContexts(parent::getCacheContexts(), array('route'));
    }
}
