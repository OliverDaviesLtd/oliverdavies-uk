<?php

/**
 * @file
 * Block preprocess hooks.
 */

declare(strict_types=1);

/**
 * Implements hook_preprocess_HOOK().
 */
function opdavies_blog_preprocess_block(array &$variables): void {
  // Add the 'markup' class to blocks.
  if (in_array($variables['plugin_id'], ['views_block:featured_blog_posts-block_1'])) {
    $variables['attributes']['class'][] = 'markup';
  }
}
