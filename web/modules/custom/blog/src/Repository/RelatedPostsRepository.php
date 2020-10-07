<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\Repository;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\opdavies_blog\Entity\Node\Post;
use Illuminate\Support\Collection;

final class RelatedPostsRepository {

  private EntityStorageInterface $nodeStorage;

  public function __construct(
    EntityTypeManagerInterface $entityTypeManager
  ) {
    $this->nodeStorage = $entityTypeManager->getStorage('node');
  }

  public function getFor(Post $post): Collection {
    $query = $this->nodeStorage->getQuery();

    // Ensure that the current node ID is not returned as a related post.
    $query->condition('nid', $post->id(), '!=');

    /** @var array $postIds */
    $postIds = $query->execute();

    $posts = $this->nodeStorage->loadMultiple($postIds);

    return (new Collection($posts))->values();
  }

}
