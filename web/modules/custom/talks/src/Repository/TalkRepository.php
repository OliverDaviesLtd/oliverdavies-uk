<?php

declare(strict_types=1);

namespace Drupal\opdavies_talks\Repository;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\NodeInterface;
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
  public function findAll(): Collection {
    $talks = $this->nodeStorage->loadByProperties($this->defaultProperties());

    return new Collection($talks);
  }

  /**
   * @return Collection|Talk[]
   */
  public function findAllPublished(): Collection {
    $talks = $this->nodeStorage->loadByProperties(array_merge(
      $this->defaultProperties(),
      [
        'status' => NodeInterface::PUBLISHED,
      ],
    ));

    return new Collection($talks);
  }

  private function defaultProperties(): array {
    return [
      'type' => 'talk',
    ];
  }

}
