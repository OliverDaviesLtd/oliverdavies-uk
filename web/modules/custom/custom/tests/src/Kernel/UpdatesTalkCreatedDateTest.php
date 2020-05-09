<?php

declare(strict_types=1);

namespace Drupal\Tests\custom\Kernel;

use Carbon\Carbon;
use Drupal\Core\Entity\EntityInterface;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\ParagraphInterface;

final class UpdatesTalkCreatedDateTest extends EntityKernelTestBase {

  /**
   * {@inheritDoc}
   */
  public static $modules = [
    // Core.
    'node',
    'file',
    'datetime',

    // Contrib.
    'entity_reference_revisions',
    'paragraphs',
    'hook_event_dispatcher',

    // Custom.
    'custom',
    'custom_test',
  ];

  public function testCreatingNode() {
    $eventDate = Carbon::today()->addWeek();
    $eventDateToFormat = $eventDate->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $eventDateToTimestamp = $eventDate->getTimestamp();

    $talk = $this->createTalk($eventDateToFormat);

    $this->assertEqual($eventDateToTimestamp, $talk->get('created')
      ->getString());
  }

  public function testUpdatingNode() {
    $talk = $this->createTalk();

    $eventDate = Carbon::today()->addWeek();
    $eventDateToFormat = $eventDate->format(DateTimeItemInterface::DATE_STORAGE_FORMAT);
    $eventDateToTimestamp = $eventDate->getTimestamp();

    $event = $this->createEvent($eventDateToFormat);
    $talk->set('field_events', [$event]);
    $talk->save();

    $this->assertEqual($eventDateToTimestamp, $talk->get('created')
      ->getString());
  }

  protected function setUp() {
    parent::setUp();

    $this->installEntitySchema('paragraph');
    $this->installSchema('node', ['node_access']);

    $this->installConfig(['custom_test']);
  }

  private function createTalk(?string $eventDateToFormat = NULL): EntityInterface {
    if ($eventDateToFormat) {
      $event = $this->createEvent($eventDateToFormat);
    }

    $talk = Node::create([
      'title' => 'TDD - Test Driven Drupal',
      'type' => 'talk',
    ]);

    if (isset($event)) {
      $talk->set('field_events', [$event]);
    }

    $talk->save();

    return $talk;
  }

  private function createEvent(string $eventDateToFormat): ParagraphInterface {
    $event = Paragraph::create([
      'field_date' => $eventDateToFormat,
      'type' => 'event',
    ]);

    $event->save();

    return $event;
  }

}
