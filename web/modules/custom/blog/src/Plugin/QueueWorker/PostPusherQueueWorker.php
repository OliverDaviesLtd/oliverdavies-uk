<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\opdavies_blog\Entity\Node\Post;

/**
 * @QueueWorker(
 *   id = "opdavies_blog_push_post_to_social_media",
 *   title = "Push a blog post to social media",
 *   cron = {"time": 30}
 * )
 */
final class PostPusherQueueWorker extends QueueWorkerBase {

  public function processItem($data): void {
    ['post' => $post] = $data;

    if (!$this->shouldBePushed($post)) {
      return;
    }
  }

  private function shouldBePushed(Post $post): bool {
    if ($post->isExternalPost()) {
      return FALSE;
    }

    if (!$post->isPublished()) {
      return FALSE;
    }

    if (!$post->shouldSendToSocialMedia()) {
      return FALSE;
    }

    if ($post->hasBeenSentToSocialMedia()) {
      return FALSE;
    }

    return TRUE;
  }

}
