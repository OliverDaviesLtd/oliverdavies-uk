<?php

namespace Drupal\opdavies_blog\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * @Block(
 *   id = "opdavies_blog_related_posts",
 *   admin_label = @Translation("Related Posts"),
 *   category = @Translation("Blog")
 * )
 */
class RelatedPostsBlock extends BlockBase {

  public function build(): array {
    $build['content'] = [
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
