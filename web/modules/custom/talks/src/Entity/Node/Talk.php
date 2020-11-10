<?php

namespace Drupal\opdavies_talks\Entity\Node;

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

  public const FIELD_EVENTS = 'field_events';
  public const FIELD_EVENT_DATE = 'field_event_date';

  public function addEvent(ParagraphInterface $event): void {
    $this->set(
      self::FIELD_EVENTS,
      $this->getEvents()->push($event)->toArray()
    );
  }

  public function getEvents(): Collection {
    return Collection::make($this->get(self::FIELD_EVENTS)
      ->referencedEntities());
  }

  public function getNextDate(): ?int {
    if ($this->get(self::FIELD_EVENT_DATE)->isEmpty()) {
      return NULL;
    }

    return (int) $this->get(self::FIELD_EVENT_DATE)->getString();
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
    $this->set(self::FIELD_EVENT_DATE, $date);
  }

}
