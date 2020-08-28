<?php

declare(strict_types=1);

namespace Drupal\Tests\custom\Kernel\Entity\Node;

use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\node\Entity\Node;
use Drupal\opdavies_blog\Entity\Node\Post;

final class PostTest extends EntityKernelTestBase {

  public static $modules = [
    // Core.
    'node',
    'link',
    'taxonomy',

    // Contrib.
    'discoverable_entity_bundle_classes',

    // Custom.
    'opdavies_blog',
    'opdavies_blog_test',
  ];

  /** @test */
  public function it_can_determine_if_a_post_contains_a_tweet(): void {
    /** @var Post $post */
    $post = Node::create(['type' => 'post']);
    $this->assertFalse($post->hasTweet());

    /** @var Post $post */
    $post = Node::create([
      'field_has_tweet' => TRUE,
      'type'  => 'post',
    ]);
    $this->assertTrue($post->hasTweet());
  }

  protected function setUp() {
    parent::setUp();

    $this->installConfig(['opdavies_blog_test']);
  }

}
