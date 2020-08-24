<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\Entity\Node;

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

  public function getExternalLink(): ?array {
    return ($link = $this->get('field_external_link')->get(0))
      ? $link->getValue()
      : NULL;
  }

  public function hasBeenSentToSocialMedia(): bool {
    return (bool) $this->get('field_sent_to_social_media')->getString();
  }

  public function hasTweet(): bool {
    return (bool) $this->get('field_has_tweet')->getString();
  }

  public function isExternalPost(): bool {
    return (bool) $this->getExternalLink();
  }

  public function toTweet(): string {
    // TODO: Add tags.

    $parts = [$this->label(), $this->url('canonical', ['absolute' => TRUE])];

    return implode(PHP_EOL . PHP_EOL, $parts);
  }

}
