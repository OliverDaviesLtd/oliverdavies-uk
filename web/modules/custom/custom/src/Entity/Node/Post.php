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

  public function hasBeenSentToSocialMedia(): bool {
    return (bool) $this->get('field_sent_to_social_media')->getString();
  }

  public function hasTweet(): bool {
    return (bool) $this->get('field_has_tweet')->getString();
  }

  public function toTweet(): string {
    // TODO: Add tags.

    $parts = [$this->label(), $this->url('canonical', ['absolute' => TRUE])];

    return implode(PHP_EOL . PHP_EOL, $parts);
  }

}
