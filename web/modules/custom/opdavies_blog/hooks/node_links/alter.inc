<?php

/**
 * @file
 * Node links alter hooks.
 */

declare(strict_types=1);

use Drupal\Core\Url;
use Drupal\node\NodeInterface;

/**
 * Implements hook_node_links_alter().
 */
function opdavies_blog_node_links_alter(array &$links, NodeInterface $node): void {
  if (!method_exists($node, 'getExternalLink')) {
    return;
  }

  if ($link = $node->getExternalLink()) {
    $links['node']['#links']['node-readmore']['url'] = Url::fromUri($link['uri']);
    $links['node']['#links']['node-readmore']['title'] = t('Read more<span class="visually-hidden"> about @title</span> (<span class="visually-hidden">on </span>@domain)', [
      '@domain' => $link['title'],
      '@title' => $node->label(),
    ]);
  }
}
