<?php

namespace Drupal\test_global\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Test block.
 *
 * @Block(
 *  id = "test_block",
 *  admin_label = @Translation("Test block"),
 * )
 */
class TestBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The current node.
   *
   * @var \Drupal\Core\Entity\EntityInterface
   */
  protected $currentNode;

  /**
   * Creates an instance of the plugin.
   *
   * @param array $configuration
   *   The configuration for the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Routing\RouteMatchInterface $routeMatch
   *   The route match service.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityTypeManagerInterface $entity_type_manager,
    RouteMatchInterface $routeMatch
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->currentNode = $routeMatch->getParameter('node');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    if (!is_null($this->currentNode)) {
      $eventTypeId = $this->currentNode->get('field_event_type')->getString();
      $currentTime = new DrupalDateTime(date('Y-m-dTH:i:s'));
      $nodeStorage = $this->entityTypeManager->getStorage('node');

      $query = $nodeStorage->getQuery();
      $query->condition('status', NodeInterface::PUBLISHED);
      $query->condition('type', 'event');
      $query->condition('field_date_end', $currentTime, '>=');
      $query->condition('field_event_type', $eventTypeId);

      $ids = $query->execute();
      $events = $nodeStorage->loadMultiple($ids);

      // Remove current node.
      unset($events[$this->currentNode->id()]);

      if (!empty($events)) {
        return $this->entityTypeManager->getViewBuilder('node')->view(
          reset($events),
          'event_block'
        );
      }
    }

    return [];
  }

}
