<?php

namespace Drupal\custom\Entity\Node;

use Drupal\discoverable_entity_bundle_classes\ContentEntityBundleInterface;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\ParagraphInterface;
use Illuminate\Support\Collection;

/**
 * Defines an talk node class.
 *
 * @ContentEntityBundleClass(
 *   label = @Translation("Talk"),
 *   entity_type = "node",
 *   bundle = "talk"
 * );
 */
class Talk extends Node implements ContentEntityBundleInterface {

  public function addEvent(ParagraphInterface $event): void {
    $this->set(
      'field_events',
      $this->getEvents()->push($event)->toArray()
    );
  }

  public function getEvents(): Collection {
    return Collection::make($this->get('field_events')
      ->referencedEntities());
  }

  public function getNextDate(): ?int {
    if ($this->get('field_event_date')->isEmpty()) {
      return NULL;
    }

    return (int) $this->get('field_event_date')->getString();
  }

  /**
   * Find the date for the latest event.
   *
   * @return string|null
   */
  public function findLatestEventDate(): ?string {
    return $this->getEvents()
      ->map(fn(ParagraphInterface $event) => $event->get('field_date')
        ->getString())
      ->max();
  }

  public function setNextDate(int $date): void {
    $this->set('field_event_date', $date);
  }

}
