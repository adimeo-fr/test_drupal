<?php
/**
 * @file
 * Contains \Drupal\test_global\Plugin\QueueWorker\CronEventsQueue.
 */

namespace Drupal\test_global\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;

/**
 * Processes tasks for example module.
 *
 * @QueueWorker(
 *   id = "cron_event_queue",
 *   title = @Translation("Cron Event Queue"),
 *   cron = {"time" = 60}
 * )
 */
class CronEventsQueue extends QueueWorkerBase {

  /**
   * {@inheritdoc}
   */
  public function processItem($item) {
    $item->setUnpublished()->save();
  }

}
