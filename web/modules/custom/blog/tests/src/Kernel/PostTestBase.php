<?php

namespace Drupal\Tests\opdavies_blog\Kernel;

use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\Tests\node\Traits\NodeCreationTrait;
use Drupal\Tests\taxonomy\Traits\TaxonomyTestTrait;

abstract class PostTestBase extends EntityKernelTestBase {

  use NodeCreationTrait;
  use TaxonomyTestTrait;

  public static $modules = [
    // Core.
    'node',
    'taxonomy',
    'link',

    // Contrib.
    'hook_event_dispatcher',
    'core_event_dispatcher',

    // Custom.
    'opdavies_blog_test',
    'opdavies_blog',
  ];

  protected function setUp() {
    parent::setUp();

    $this->installConfig([
      'filter',
      'opdavies_blog_test',
    ]);

    $this->installEntitySchema('taxonomy_vocabulary');
    $this->installEntitySchema('taxonomy_term');
  }

}
