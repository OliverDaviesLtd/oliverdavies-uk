<?php

declare(strict_types=1);

namespace Drupal\Tests\custom\Kernel\Entity\Node;

use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
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

  /** @test */
  public function it_converts_a_post_to_a_tweet(): void {
    /** @var Post $post */
    $post = Node::create([
      'status' => NodeInterface::PUBLISHED,
      'title' => 'Creating a custom PHPUnit command for DDEV',
      'type' => 'post',
    ]);
    $post->save();

    $expected = <<<EOF
    Creating a custom PHPUnit command for DDEV

    http://localhost/node/1
    EOF;

    $this->assertSame($expected, $post->toTweet());
  }

  protected function setUp() {
    parent::setUp();

    $this->installConfig(['opdavies_blog_test']);
  }

}
