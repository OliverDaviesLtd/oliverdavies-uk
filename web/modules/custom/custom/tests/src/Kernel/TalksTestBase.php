<?php

declare(strict_types=1);

namespace Drupal\Tests\custom\Kernel;

use Drupal\Core\Entity\EntityInterface;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\ParagraphInterface;

abstract class TalksTestBase extends EntityKernelTestBase {

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

  protected function createEvent(string $eventDateToFormat): ParagraphInterface {
    /** @var \Drupal\paragraphs\ParagraphInterface $event */
    $event = Paragraph::create([
      'field_date' => $eventDateToFormat,
      'type' => 'event',
    ]);

    return tap($event)->save();
  }

  protected function setUp() {
    parent::setUp();

    $this->installEntitySchema('paragraph');
    $this->installSchema('node', ['node_access']);

    $this->installConfig(['custom_test']);
  }

  protected function createTalk(?string $eventDateToFormat = NULL): EntityInterface {
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

    return tap($talk)->save();
  }

}
