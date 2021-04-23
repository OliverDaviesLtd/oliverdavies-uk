<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\Repository;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryInterface;
use Drupal\node\NodeInterface;
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

    /** @var array $postIds */
    $postIds = $this->query($post, $tagIds)->execute();

    $posts = $this->nodeStorage->loadMultiple($postIds);

    return new Collection(array_values($posts));
  }

  private function query(Post $post, Collection $tagIds): QueryInterface {
    $query = $this->nodeStorage->getQuery();

    // Ensure that the current node ID is not returned as a related post.
    $query->condition('nid', $post->id(), '!=');

    // Only return posts with the same tags.
    $query->condition('field_tags', $tagIds->toArray(), 'IN');

    $query->condition('status', NodeInterface::PUBLISHED);

    return $query;
  }

}
