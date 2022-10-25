<?php

namespace Drupal\events\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\node\Entity\Node;

/**
 * @QueueWorker(
 *   id = "unpublish_old_events",
 *   title = @Translation("Unpublish old events worker"),
 *   cron = {"time" = 60}
 * )
 */
class UnpublishOldEventsQueueWorker extends QueueWorkerBase
{
    /**
     * Unpublish old events.
     *
     * @param object $old_event
     *   What was injected : Event.
     *
     * @throws \Drupal\Core\Entity\EntityStorageException
     * @throws \Exception
     */
    public function processItem($old_event): void
    {
        $old_event->setUnpublished();
        $old_event->save();
    }
}
