<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;

/**
 * @QueueWorker(
 *   id = "opdavies_blog_push_post_to_social_media",
 *   title = "Push a blog post to social media",
 *   cron = {"time": 30}
 * )
 */
final class PostPusherQueueWorker extends QueueWorkerBase {

  public function processItem($data): void {
  }

}
