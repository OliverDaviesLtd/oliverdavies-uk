<?php

declare(strict_types=1);

namespace Drupal\Tests\custom\Kernel\Entity\Node;

use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\opdavies_blog\Entity\Node\Post;
use Drupal\taxonomy\Entity\Term;

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
      'field_tags' => [
        Term::create(['vid' => 'tags', 'name' => 'Automated testing']),
        Term::create(['vid' => 'tags', 'name' => 'DDEV']),
        Term::create(['vid' => 'tags', 'name' => 'Drupal']),
        Term::create(['vid' => 'tags', 'name' => 'PHP']),
      ],
      'status' => NodeInterface::PUBLISHED,
      'title' => 'Creating a custom PHPUnit command for DDEV',
      'type' => 'post',
    ]);
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
    /** @var Post $post */
    $post = Node::create([
      'field_tags' => [
        Term::create(['vid' => 'tags', 'name' => 'Drupal']),
        Term::create(['vid' => 'tags', 'name' => 'Drupal Planet']),
        Term::create(['vid' => 'tags', 'name' => 'PHP']),
      ],
      'status' => NodeInterface::PUBLISHED,
      'title' => 'Drupal Planet should not be added as a hashtag',
      'type' => 'post',
    ]);
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
