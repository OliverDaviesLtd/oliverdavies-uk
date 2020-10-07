<?php

// phpcs:disable Drupal.Commenting.DocComment, Drupal.NamingConventions.ValidFunctionName

namespace Drupal\Tests\opdavies_blog\Kernel;

use Drupal\opdavies_blog\Repository\RelatedPostsRepository;
use Drupal\opdavies_blog_test\Factory\PostFactory;

final class RelatedPostsTest extends PostTestBase {

  private PostFactory $postFactory;

  private RelatedPostsRepository $relatedPostsRepository;

  /** @test */
  public function it_returns_related_posts(): void {
    $postA = $this->postFactory
      ->setTitle('Post A')
      ->withTags(['Drupal 8'])
      ->create();
    $postA->save();

    $postB = $this->postFactory
      ->setTitle('Post B')
      ->withTags(['Drupal 8'])
      ->create();
    $postB->save();

    $relatedPosts = $this->relatedPostsRepository->getFor($postA);

    $this->assertCount(1, $relatedPosts);
    $this->assertSame('Post B', $relatedPosts->first()->label());
  }

  /** @test */
  public function it_returns_an_empty_collection_if_there_are_no_related_posts(): void {
    $postA = $this->postFactory
      ->setTitle('Drupal 8 post')
      ->withTags(['Drupal 8'])
      ->create();
    $postA->save();

    $postB = $this->postFactory
      ->setTitle('Drupal 9 post')
      ->withTags(['Drupal 9'])
      ->create();
    $postB->save();

    $relatedPosts = $this->relatedPostsRepository->getFor($postA);

    $this->assertEmpty($relatedPosts);
  }

  protected function setUp() {
    parent::setUp();

    $this->postFactory = $this->container->get(PostFactory::class);
    $this->relatedPostsRepository = $this->container->get(RelatedPostsRepository::class);
  }

}
