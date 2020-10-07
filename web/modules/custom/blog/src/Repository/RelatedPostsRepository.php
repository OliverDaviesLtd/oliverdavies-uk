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
    $posts = $this->nodeStorage->loadByProperties([
      'type' => 'post',
    ]);

    return (new Collection($posts))
      ->filter(fn(Post $relatedPost) => $relatedPost->id() != $post->id())
      ->values();
  }

}
