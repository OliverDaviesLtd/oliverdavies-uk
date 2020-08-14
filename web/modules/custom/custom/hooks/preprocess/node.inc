<?php

/**
 * @file
 * Node preprocess functions.
 */

declare(strict_types=1);

/**
 * Implements hook_preprocess_HOOK().
 */
function custom_preprocess_node(array &$variables): void {
  if ($link = $variables['node']->getExternalLink()) {
    $variables['url'] = $link['uri'];
  }
}
