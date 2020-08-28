<?php

declare(strict_types=1);

namespace Drupal\opdavies_talks\Service;

use Carbon\Carbon;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\NodeInterface;
use Drupal\opdavies_talks\Entity\Node\Talk;
use Drupal\paragraphs\ParagraphInterface;
use Illuminate\Support\Collection;

final class TalkCounter {

  private EntityStorageInterface $nodeStorage;

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->nodeStorage = $entityTypeManager->getStorage('node');
  }

  public function getCount(): int {
    $today = Carbon::today()->format('Y-m-d H:i:s');

    return $this->getTalks()
      ->flatMap(fn(Talk $talk) => $talk->getEvents())
      ->filter(fn(ParagraphInterface $event) => $event->get('field_date')->getString() <= $today)
      ->count();
  }

  private function getTalks(): Collection {
    $talks = $this->nodeStorage->loadByProperties([
      'status' => NodeInterface::PUBLISHED,
      'type' => 'talk',
    ]);

    return new Collection($talks);
  }

}
