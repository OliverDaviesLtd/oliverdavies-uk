<?php

// phpcs:disable Drupal.Commenting.DocComment, Drupal.NamingConventions.ValidFunctionName

namespace Drupal\Tests\opdavies_blog\Kernel;

use Drupal\Core\Queue\QueueInterface;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\opdavies_blog\Entity\Node\Post;
use Drupal\Tests\node\Traits\NodeCreationTrait;

final class PushToSocialMediaTest extends EntityKernelTestBase {

  use NodeCreationTrait;

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

  private QueueInterface $queue;

  /** @test */
  public function a_post_is_pushed_to_social_media_once_published(): void {
    $this->assertSame(0, $this->queue->numberOfItems());

    $this->createNode([
      'title' => 'Ignoring PHPCS sniffs within PHPUnit tests',
      'type' => 'post',
    ]);

    $this->assertSame(1, $this->queue->numberOfItems());

    $item = $this->queue->claimItem();
    /** @var Post $post */
    $post = $item->data['post'];

    $this->assertNotNull($post);
    $this->assertInstanceOf(Post::class, $post);
    $this->assertSame('1', $post->id());
    $this->assertSame('Ignoring PHPCS sniffs within PHPUnit tests', $post->getTitle());
  }

  protected function setUp() {
    parent::setUp();

    $this->installConfig(['filter', 'opdavies_blog_test']);

    $this->installSchema('node', ['node_access']);

    $this->queue = $this->container->get('queue')
      ->get('opdavies_blog_push_post_to_social_media');
  }

}