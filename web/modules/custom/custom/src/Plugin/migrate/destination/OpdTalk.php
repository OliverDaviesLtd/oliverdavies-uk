<?php

namespace Drupal\custom\Plugin\migrate\destination;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityInterface;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
use Drupal\migrate\Annotation\MigrateDestination;
use Drupal\migrate\Plugin\migrate\destination\EntityContentBase;
use Drupal\migrate\Row;
use Drupal\paragraphs\Entity\Paragraph;
use Illuminate\Support\Collection;

/**
 * @MigrateDestination(
 *   id="opd_talk"
 * )
 */
class OpdTalk extends EntityContentBase {

  protected static function getEntityTypeId($plugin_id) {
    return 'node';
  }

  public function import(Row $row, array $old_destination_id_values = []) {
    $data = $row->getDestination();

    if ($nodes = $this->storage->loadByProperties(['title' => $data['title']])) {
      $node = current($nodes);
    }
    else {
      $node = $this->storage->create($data);
    }

    $eventData = $row->getSourceProperty('events');
    $this->createEventParagraphs($node, $eventData);

    $node->save();

    return [$node->id()];
  }

  private function createEventParagraphs(EntityInterface $node, array $eventData): void {
    Collection::make($eventData)->map(function (array $event): array {
      $paragraph = Paragraph::create([
        'field_date' => DrupalDateTime::createFromTimestamp($event['date'])
          ->format(DateTimeItemInterface::DATE_STORAGE_FORMAT),
        'field_link' => $event['url'],
        'field_location' => $event['location'],
        'field_name' => $event['name'],
        'field_remote' => $event['remote'] == 'true' ? 1 : 0,
        'type' => 'event',
      ]);

      $paragraph->save();

      return [
        'target_id' => $paragraph->id(),
        'target_revision_id' => $paragraph->getRevisionId(),
      ];
    })->pipe(function (Collection $events) use ($node) {
      $node->set('field_events', $events->toArray());
      $node->save();
    });
  }

}
