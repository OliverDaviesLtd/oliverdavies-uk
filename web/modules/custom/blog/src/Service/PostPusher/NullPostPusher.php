<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\Service\PostPusher;

use Drupal\opdavies_blog\Entity\Node\Post;

final class NullPostPusher implements PostPusher {

  public function push(Post $post): void {}

}
