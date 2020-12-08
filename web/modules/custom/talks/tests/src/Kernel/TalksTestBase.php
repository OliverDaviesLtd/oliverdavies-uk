<?php

declare(strict_types=1);

namespace Drupal\Tests\opdavies_talks\Kernel;

use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\node\Entity\Node;
use Drupal\opdavies_talks\Entity\Node\Talk;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\ParagraphInterface;

abstract class TalksTestBase extends EntityKernelTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    // Core.
    'node',
    'file',
    'datetime',

    // Contrib.
    'discoverable_entity_bundle_classes',
    'entity_reference_revisions',
    'paragraphs',
    'hook_event_dispatcher',
    'core_event_dispatcher',

    // Custom.
    'opdavies_talks',
    'opdavies_talks_test',
  ];

  protected $strictConfigSchema = FALSE;

  protected function createEvent(array $overrides = []): ParagraphInterface {
    /** @var \Drupal\paragraphs\ParagraphInterface $event */
    $event = Paragraph::create(array_merge([
      'type' => 'event',
    ], $overrides));

    $event->save();

    return $event;
  }

  protected function createTalk(array $overrides = []): Talk {
    $talk = Node::create(array_merge([
      'title' => 'Test Driven Drupal',
      'type' => 'talk',
    ], $overrides));

    $talk->save();

    return $talk;
  }

  protected function setUp() {
    parent::setUp();

    $this->installEntitySchema('paragraph');
    $this->installSchema('node', ['node_access']);

    $this->installConfig(['opdavies_talks_test']);
  }

}
