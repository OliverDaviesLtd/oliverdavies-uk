<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\Action;

use Drupal\opdavies_blog\Entity\Node\Post;

final class ConvertPostToTweet {

  public function __invoke(Post $post): string {
    return $post->toTweet();
  }

}
