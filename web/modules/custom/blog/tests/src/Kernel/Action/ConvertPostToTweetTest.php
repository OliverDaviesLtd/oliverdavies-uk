<?php

namespace Drupal\Tests\opdavies_blog\Kernel\Action;

use Drupal\opdavies_blog\Action\ConvertPostToTweet;
use Drupal\opdavies_blog_test\Factory\PostFactory;
use Drupal\Tests\opdavies_blog\Kernel\PostTestBase;

final class ConvertPostToTweetTest extends PostTestBase {

  private ConvertPostToTweet $convertPostToTweet;

  public function testConvertPostToTweet(): void {
    $post = $this->postFactory
      ->setTitle('Creating a custom PHPUnit command for DDEV')
      ->withTags(['Automated testing', 'DDEV', 'Drupal', 'Drupal 8', 'PHP'])
      ->create();

    $post->save();

    $expected = <<<EOF
    Creating a custom PHPUnit command for DDEV

    http://localhost/node/1

    #AutomatedTesting #DDEV #Drupal #Drupal8 #PHP
    EOF;

    $this->assertSame($expected, ($this->convertPostToTweet)($post));
  }

  public function testCertainTermsAreNotAddedAsHashtags(): void {
    $post = $this->postFactory
      ->setTitle('Drupal Planet should not be added as a hashtag')
      ->withTags(['Drupal', 'Drupal Planet', 'PHP'])
      ->create();

    $post->save();

    $expected = <<<EOF
    Drupal Planet should not be added as a hashtag

    http://localhost/node/1

    #Drupal #PHP
    EOF;

    $this->assertSame($expected, ($this->convertPostToTweet)($post));
  }

  protected function setUp() {
    parent::setUp();

    $this->convertPostToTweet = $this->container->get(ConvertPostToTweet::class);
    $this->postFactory = $this->container->get(PostFactory::class);
  }

}
