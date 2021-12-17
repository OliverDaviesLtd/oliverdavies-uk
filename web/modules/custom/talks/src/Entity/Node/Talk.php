<?php

declare(strict_types=1);

namespace Drupal\opdavies_talks\Entity\Node;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\paragraphs\ParagraphInterface;
use Illuminate\Support\Collection;

final class Talk {

  public const FIELD_EVENTS = 'field_events';
  public const FIELD_EVENT_DATE = 'field_event_date';

  private NodeInterface $node;

  public function __construct(EntityInterface $node) {
    $this->node = $node;
  }

  public function addEvent(ParagraphInterface $event): void {
    $this->set(
      self::FIELD_EVENTS,
      $this->getEvents()->push($event)->toArray()
    );
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

  public function get(string $name): FieldItemListInterface {
    return $this->node->get($name);
  }

  public function getCreatedTime(): int {
    return (int) $this->node->getCreatedTime();
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

  public function id(): int {
    return (int) $this->node->id();
  }

  public function label(): string {
    return $this->node->label();
  }

  public function save(): void {
    $this->node->save();
  }

  public function set(string $name, $value): void {
    $this->node->set($name, $value);
  }

  public function setCreatedTime(int $timestamp): void {
    $this->node->setCreatedTime($timestamp);
  }

  public function setEvents(array $events): void {
    $this->set(self::FIELD_EVENTS, $events);
  }

  public function setNextDate(int $date): void {
    $this->set(self::FIELD_EVENT_DATE, $date);
  }

  public static function createFromNode(EntityInterface $node): self {
    // TODO: ensure that this is a node and a `talk` type.
    return new self($node);
  }

}
