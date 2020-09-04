<?php

declare(strict_types=1);

namespace Drupal\Tests\custom\Kernel\Entity\Node;

use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\node\Entity\Node;
use Drupal\opdavies_blog\Entity\Node\Post;
use Drupal\opdavies_blog_test\Factory\PostFactory;

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
    $post = (new PostFactory())->create();
    $post->save();

    $this->assertFalse($post->hasTweet());

    $post = (new PostFactory())->create(['field_has_tweet' => TRUE]);
    $post->save();

    $this->assertTrue($post->hasTweet());
  }

  /** @test */
  public function it_converts_a_post_to_a_tweet(): void {
    $post = (new PostFactory())
      ->setTitle('Creating a custom PHPUnit command for DDEV')
      ->withTags(['Automated testing', 'DDEV', 'Drupal', 'PHP'])
      ->create();

    $post->save();

    $expected = <<<EOF
    Creating a custom PHPUnit command for DDEV

    http://localhost/node/1

    #automated-testing #ddev #drupal #php
    EOF;

    $this->assertSame($expected, $post->toTweet());
  }

  /** @test */
  public function certain_terms_are_not_added_as_hashtags(): void {
    $post = (new PostFactory())
      ->setTitle('Drupal Planet should not be added as a hashtag')
      ->withTags(['Drupal', 'Drupal Planet', 'PHP'])
      ->create();

    $post->save();

    $expected = <<<EOF
    Drupal Planet should not be added as a hashtag

    http://localhost/node/1

    #drupal #php
    EOF;

    $this->assertSame($expected, $post->toTweet());
  }

  protected function setUp() {
    parent::setUp();

    $this->installEntitySchema('taxonomy_term');

    $this->installConfig(['opdavies_blog_test']);
  }

}
