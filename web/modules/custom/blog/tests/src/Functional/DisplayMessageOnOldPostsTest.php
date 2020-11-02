<?php

namespace Drupal\Tests\opdavies_blog\Functional;

use Carbon\Carbon;
use Drupal\opdavies_blog_test\Factory\PostFactory;
use Drupal\Tests\BrowserTestBase;

final class DisplayMessageOnOldPostsTest extends BrowserTestBase {

  protected static $modules = [
    'field',
    'link',
    'node',
    'taxonomy',

    'hook_event_dispatcher',
    'core_event_dispatcher',

    'opdavies_blog_test',
    'opdavies_blog',
  ];

  /** @test */
  public function a_message_is_displayed_on_posts_over_a_year_old(): void {
    \Drupal::configFactory()->getEditable('opdavies_blog.settings')
      ->set('old_post_message_text', 'This is an old post.')
      ->save(TRUE);

    $post = (new PostFactory())->create([
      'created' => Carbon::now()->subYear()->subDay()->getTimestamp(),
    ]);
    $post->save();

    $this->drupalGet('node/1');

    $this->assertSession()->pageTextContainsOnce('This is an old post.');
  }

  /** @test */
  public function a_message_is_not_displayed_for_posts_less_than_a_year_old(): void {
    \Drupal::configFactory()->getEditable('opdavies_blog.settings')
      ->set('old_post_message_text', 'This is an old post.')
      ->save(TRUE);

    $post = (new PostFactory())->create([
      'created' => Carbon::now()->subDays(364)->getTimestamp(),
    ]);
    $post->save();

    $this->drupalGet('node/1');

    $this->assertSession()->pageTextNotContains('This is an old post.');
  }

  /** @test */
  public function a_message_is_not_displayed_for_non_post_nodes(): void {
    $this->drupalCreateContentType(['type' => 'page']);

    \Drupal::configFactory()->getEditable('opdavies_blog.settings')
      ->set('old_post_message_text', 'This is an old post.')
      ->save(TRUE);

    $this->drupalCreateNode([
      'created' => Carbon::now()->subYear()->subDay()->getTimestamp(),
      'type' => 'page',
    ]);

    $this->drupalGet('node/1');

    $this->assertSession()->pageTextNotContains('This is an old post.');
  }

}
