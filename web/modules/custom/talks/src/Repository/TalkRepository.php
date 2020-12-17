<?php

declare(strict_types=1);

namespace Drupal\opdavies_talks\Repository;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\opdavies_talks\Entity\Node\Talk;
use Tightenco\Collect\Support\Collection;

final class TalkRepository {

  private EntityStorageInterface $nodeStorage;

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->nodeStorage = $entityTypeManager->getStorage('node');
  }

  /**
   * @return Collection|Talk[]
   */
  public function getAll(bool $publishedOnly = FALSE): Collection {
    $properties = ['type' => 'talk'];

    if ($publishedOnly) {
      $properties['status'] = TRUE;
    }

    return new Collection(
      $this->nodeStorage->loadByProperties($properties)
    );
  }

}
