<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog_test\Factory;

use Assert\Assert;
use Drupal\node\Entity\Node;
use Drupal\opdavies_blog\Entity\Node\Post;
use Drupal\taxonomy\Entity\Term;
use Drupal\taxonomy\TermInterface;
use Tightenco\Collect\Support\Collection;

final class PostFactory {

  private Collection $tags;

  private string $title = 'This is a test blog post';

  public function __construct() {
    $this->tags = new Collection();
  }

  public function create(array $overrides = []): Post {
    $this->tags->each(function (TermInterface $tag): void {
      Assert::that($tag->bundle())->same('tags');
    });

    $values = [
      'title' => $this->title,
      'type' => 'post',
      Post::FIELD_TAGS => $this->tags->toArray(),
    ];

    /** @var Post $post */
    $post = Node::create($values + $overrides);

    return $post;
  }

  public function setTitle(string $title): self {
    Assert::that($title)->notEmpty();

    $this->title = $title;

    return $this;
  }

  public function withTags(array $tags): self {
    foreach ($tags as $tag) {
      Assert::that($tag)->notEmpty()->string();

      $this->tags->push(
        Term::create(['vid' => 'tags', 'name' => $tag])
      );
    }

    return $this;
  }

}
