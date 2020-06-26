<?php

declare(strict_types=1);

namespace Drupal\Tests\custom\Kernel\Entity\Node;

use Drupal\custom\Entity\Node\Post;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\node\Entity\Node;

final class PostTest extends EntityKernelTestBase {

  public static $modules = [
    // Core.
    'node',

    // Contrib.
    'discoverable_entity_bundle_classes',

    // Custom.
    'custom',
    'opdavies_posts_test',
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

    $this->installConfig(['opdavies_posts_test']);
  }

}
