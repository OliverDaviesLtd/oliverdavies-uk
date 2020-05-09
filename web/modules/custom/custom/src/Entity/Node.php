<?php

declare(strict_types=1);

namespace Drupal\custom\Entity;

use Drupal\node\Entity\Node as CoreNode;
use Drupal\paragraphs\ParagraphInterface;
use Illuminate\Support\Collection;

final class Node extends CoreNode {

  /**
   * Find the date for the latest event.
   *
   * @return string|null
   */
  public function findLatestEventDate(): ?string {
    if (!$this->hasField('field_events')) {
      return NULL;
    }

    return Collection::make($this->get('field_events')->referencedEntities())
      ->map(fn(ParagraphInterface $event) => $event->get('field_date')
        ->getString())
      ->max();
  }

}
