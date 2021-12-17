<?php

// phpcs:disable Drupal.Commenting.DocComment, Drupal.NamingConventions.ValidFunctionName

namespace Drupal\Tests\opdavies_blog\Kernel\Entity\Node;

use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\opdavies_blog\Entity\Node\Post;
use Drupal\opdavies_blog_test\Factory\PostFactory;

final class PostTest extends EntityKernelTestBase {

  public static $modules = [
    // Core.
    'node',
    'link',
    'taxonomy',

    // Custom.
    'opdavies_blog',
    'opdavies_blog_test',
  ];

  private PostFactory $postFactory;

  /** @test */
  public function it_can_determine_if_a_post_contains_a_tweet(): void {
    $post = $this->postFactory->create();
    $post->save();

    $this->assertFalse($post->hasTweet());

    $post = $this->postFactory->create([Post::FIELD_HAS_TWEET => TRUE]);
    $post->save();

    $this->assertTrue($post->hasTweet());
  }

  protected function setUp() {
    parent::setUp();

    $this->postFactory = $this->container->get(PostFactory::class);

    $this->installEntitySchema('taxonomy_term');

    $this->installConfig(['opdavies_blog_test']);
  }

}
