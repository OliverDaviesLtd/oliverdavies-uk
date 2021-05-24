<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\UseCase;

use Drupal\opdavies_blog\Entity\Node\Post;
use Drupal\taxonomy\Entity\Term;
use Illuminate\Support\Collection;

final class ConvertPostToTweet {

  private Post $post;

  public function __invoke(Post $post): string {
    $this->post = $post;

    $parts = [
      $post->label(),
      $post->url('canonical', ['absolute' => TRUE]),
      $this->convertTermsToHashtags(),
    ];

    return implode(PHP_EOL . PHP_EOL, $parts);
  }

  private function convertTermsToHashtags(): string {
    return $this->post
      ->getTags()
      ->filter(fn(Term $term) => !$this->tagsToRemove()
        ->contains($term->label()))
      ->map(fn(Term $term) => $this->convertTermToHashtag($term))
      ->implode(' ');
  }

  private function tagsToRemove(): Collection {
    // TODO: Move these values into configuration/settings.php.
    return new Collection([
      'Drupal Planet',
    ]);
  }

  private function convertTermToHashtag(Term $tag): string {
    return '#' . (new Collection(explode(' ', $tag->label())))
      ->map(fn(string $word): string => ucfirst($word))
      ->implode('');
  }

}
