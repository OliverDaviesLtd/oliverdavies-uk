<?php

// phpcs:disable Drupal.Commenting.DocComment, Drupal.NamingConventions.ValidFunctionName

namespace Drupal\Tests\opdavies_blog\Kernel;

use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\node\Entity\Node;
use Drupal\opdavies_blog\Entity\Node\Post;
use Drupal\taxonomy\Entity\Vocabulary;
use Drupal\taxonomy\TermInterface;
use Drupal\taxonomy\VocabularyInterface;
use Drupal\Tests\node\Traits\NodeCreationTrait;
use Drupal\Tests\taxonomy\Traits\TaxonomyTestTrait;

final class ReorderBlogTagsTest extends EntityKernelTestBase {

  use NodeCreationTrait;
  use TaxonomyTestTrait;

  public static $modules = [
    // Core.
    'node',
    'taxonomy',
    'link',

    // Contrib.
    'discoverable_entity_bundle_classes',
    'hook_event_dispatcher',
    'core_event_dispatcher',

    // Custom.
    'opdavies_blog',
    'opdavies_blog_test',
  ];

  /** @test */
  public function it_reorders_tags_on_blog_posts_to_be_arranged_alphabetically(): void {
    /** @var VocabularyInterface $vocabulary */
    $vocabulary = Vocabulary::load('tags');

    $this->createTerm($vocabulary, ['name' => 'Drupal']);
    $this->createTerm($vocabulary, ['name' => 'PHP']);
    $this->createTerm($vocabulary, ['name' => 'Symfony']);

    $post = $this->createNode([
      'type' => 'post',
      Post::FIELD_TAGS => [3, 1, 2],
    ]);

    /** @var Post $post */
    $post = Node::load($post->id());

    $this->assertSame(
      ['Drupal', 'PHP', 'Symfony'],
      $post->getTags()
        ->map(fn(TermInterface $tag) => $tag->label())
        ->toArray()
    );
  }

  protected function setUp() {
    parent::setUp();

    $this->installConfig([
      'filter',
      'opdavies_blog_test',
    ]);

    $this->installSchema('node', ['node_access']);

    $this->installEntitySchema('taxonomy_vocabulary');
    $this->installEntitySchema('taxonomy_term');
  }

}
