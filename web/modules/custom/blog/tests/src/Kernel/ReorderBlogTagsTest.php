<?php

// phpcs:disable Drupal.Commenting.DocComment, Drupal.NamingConventions.ValidFunctionName

namespace Drupal\Tests\opdavies_blog\Kernel;

use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\opdavies_blog\Entity\Node\Post;
use Drupal\taxonomy\Entity\Vocabulary;
use Drupal\taxonomy\TermInterface;
use Drupal\taxonomy\VocabularyInterface;

final class ReorderBlogTagsTest extends PostTestBase {

  /** @test */
  public function it_reorders_tags_on_blog_posts_to_be_arranged_alphabetically(): void {
    /** @var VocabularyInterface $vocabulary */
    $vocabulary = Vocabulary::load('tags');

    $this->createTerm($vocabulary, ['name' => 'Drupal']);
    $this->createTerm($vocabulary, ['name' => 'PHP']);
    $this->createTerm($vocabulary, ['name' => 'Symfony']);

    $post = $this->createNode([
      'type' => 'post',
      Post::FIELD_TAGS => [3, 1, 2],
    ]);

    $node = Node::load($post->id());
    $post = Post::createFromNode($node);

    $this->assertSame(
      ['Drupal', 'PHP', 'Symfony'],
      $post->getTags()
        ->map(fn(TermInterface $tag) => $tag->label())
        ->toArray()
    );
  }

}
