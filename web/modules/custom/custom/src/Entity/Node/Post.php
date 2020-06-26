<?php

declare(strict_types=1);

namespace Drupal\custom\Entity\Node;

use Drupal\discoverable_entity_bundle_classes\ContentEntityBundleInterface;
use Drupal\node\Entity\Node;

/**
 * Defines an blog post node class.
 *
 * @ContentEntityBundleClass(
 *   label = @Translation("Blog post"),
 *   entity_type = "node",
 *   bundle = "post"
 * );
 */
class Post extends Node implements ContentEntityBundleInterface {

  public function hasTweet(): bool {
    return (bool) $this->get('field_has_tweet')->getString();
  }

}
