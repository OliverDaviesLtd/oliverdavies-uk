<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\Repository;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\opdavies_blog\Entity\Node\Post;
use Drupal\taxonomy\TermInterface;
use Illuminate\Support\Collection;

final class RelatedPostsRepository {

  private EntityStorageInterface $nodeStorage;

  public function __construct(
    EntityTypeManagerInterface $entityTypeManager
  ) {
    $this->nodeStorage = $entityTypeManager->getStorage('node');
  }

  public function getFor(Post $post): Collection {
    $tags = $post->get('field_tags')->referencedEntities();

    if (!$tags) {
      return new Collection();
    }

    $tagIds = (new Collection($tags))
      ->map(fn(TermInterface $tag) => $tag->id())
      ->values();

    $query = $this->nodeStorage->getQuery();

    // Ensure that the current node ID is not returned as a related post.
    $query->condition('nid', $post->id(), '!=');

    $query->condition('field_tags', $tagIds->toArray(), 'IN');

    /** @var array $postIds */
    $postIds = $query->execute();

    $posts = $this->nodeStorage->loadMultiple($postIds);

    return (new Collection($posts))->values();
  }

}
