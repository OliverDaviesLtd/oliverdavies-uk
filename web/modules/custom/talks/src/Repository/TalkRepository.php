<?php

declare(strict_types=1);

namespace Drupal\opdavies_talks\Repository;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\NodeInterface;
use Drupal\opdavies_talks\Collection\TalkCollection;
use Drupal\opdavies_talks\Entity\Node\Talk;

final class TalkRepository {

  private EntityStorageInterface $nodeStorage;

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->nodeStorage = $entityTypeManager->getStorage('node');
  }

  public function findAll(): TalkCollection {
    $talks = $this->nodeStorage->loadByProperties($this->defaultProperties());

    return (new TalkCollection($talks))
      ->map(fn(NodeInterface $node): Talk => Talk::createFromNode($node));
  }

  public function findAllPublished(): TalkCollection {
    $talks = $this->nodeStorage->loadByProperties(array_merge(
      $this->defaultProperties(),
      [
        'status' => NodeInterface::PUBLISHED,
      ],
    ));

    return (new TalkCollection($talks))
      ->map(fn(NodeInterface $node): Talk => Talk::createFromNode($node));
  }

  private function defaultProperties(): array {
    return [
      'type' => 'talk',
    ];
  }

}
