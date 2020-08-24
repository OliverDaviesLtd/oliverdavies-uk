<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\Repository;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Illuminate\Support\Collection;

final class PostRepository {

  private EntityStorageInterface $nodeStorage;

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->nodeStorage = $entityTypeManager->getStorage('node');
  }

  public function getAll(): Collection {
    return new Collection(
      $this->nodeStorage->loadByProperties([
        'type' => 'post',
      ])
    );
  }

}
