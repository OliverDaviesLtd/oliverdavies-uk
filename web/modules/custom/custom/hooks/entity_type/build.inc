<?php

/**
 * @file
 * Entity type build hooks.
 */

declare(strict_types=1);

use Drupal\discoverable_entity_bundle_classes\Storage\Node\NodeStorage;

/**
 * Implements hook_entity_type_build().
 */
function custom_entity_type_build(array &$entityTypes): void {
  /** @var \Drupal\Core\Entity\EntityTypeInterface[] $entityTypes */
  if (isset($entityTypes['node'])) {
    $entityTypes['node']->setStorageClass(NodeStorage::class);
  }
}
