<?php

declare(strict_types=1);

namespace Drupal\custom\Plugin\migrate\destination;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityInterface;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
use Drupal\media\Entity\Media;
use Drupal\migrate\Annotation\MigrateDestination;
use Drupal\migrate\Plugin\migrate\destination\EntityContentBase;
use Drupal\migrate\Row;
use Drupal\paragraphs\Entity\Paragraph;
use Illuminate\Support\Collection;

/**
 * A migrate destination for a talk node.
 *
 * @MigrateDestination(
 *   id="opd_talk"
 * )
 */
final class OpdTalk extends EntityContentBase {

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

    $this->createEventParagraphs($row, $node);
    $this->createVideoMedia($row, $node);

    $node->save();

    return [$node->id()];
  }

  private function createEventParagraphs(Row $row, EntityInterface $node): void {
    $eventData = $row->getSourceProperty('events');

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
    });
  }

  private function createVideoMedia(Row $row, EntityInterface $node) {
    $video = $row->getSourceProperty('video');

    if (!empty($video['type']) && !empty($video['id'])) {
      $video = Media::create([
        'bundle' => 'video',
        'field_media_oembed_video' => [
          'value' => $this->getVideoUrlFromId($video),
        ],
        'langcode' => \Drupal::languageManager()->getDefaultLanguage()->getId(),
        'uid' => 1,
      ]);

      $node->set('field_video', tap($video)->save());
    }
  }

  private function getVideoUrlFromId(array $video): string {
    $urls = new Collection([
      'vimeo' => 'https://vimeo.com/',
      'youtube' => 'https://www.youtube.com/watch?v=',
    ]);

    return $urls->get($video['type']) . $video['id'];
  }

}
