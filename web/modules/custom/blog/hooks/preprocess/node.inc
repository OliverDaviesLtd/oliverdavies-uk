<?php

/**
 * @file
 * Node preprocess functions.
 */

declare(strict_types=1);

/**
 * Implements hook_preprocess_HOOK().
 */
function opdavies_blog_preprocess_node(array &$variables): void {
  if (!method_exists($variables['node'], 'getExternalLink')) {
    return;
  }

  if ($link = $variables['node']->getExternalLink()) {
    $variables['url'] = $link['uri'];
  }
}
