<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\Entity\Node;

use Drupal\discoverable_entity_bundle_classes\ContentEntityBundleInterface;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;
use Illuminate\Support\Collection;

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

  public const FIELD_EXTERNAL_LINK = 'field_external_link';
  public const FIELD_HAS_TWEET = 'field_has_tweet';
  public const FIELD_SEND_TO_SOCIAL_MEDIA = 'field_send_to_social_media';
  public const FIELD_SENT_TO_SOCIAL_MEDIA = 'field_sent_to_social_media';
  public const FIELD_TAGS = 'field_tags';

  public function getExternalLink(): ?array {
    return ($link = $this->get(self::FIELD_EXTERNAL_LINK)->get(0))
      ? $link->getValue()
      : NULL;
  }

  /**
   * @return Collection|Term[]
   */
  public function getTags(): Collection {
    return new Collection($this->get(self::FIELD_TAGS)->referencedEntities());
  }

  public function hasBeenSentToSocialMedia(): bool {
    return (bool) $this->get(self::FIELD_SENT_TO_SOCIAL_MEDIA)->getString();
  }

  public function hasTweet(): bool {
    return (bool) $this->get(self::FIELD_HAS_TWEET)->getString();
  }

  public function isExternalPost(): bool {
    return (bool) $this->getExternalLink();
  }

  public function markAsSentToSocialMedia(): self {
    $this->set(self::FIELD_SENT_TO_SOCIAL_MEDIA, TRUE);

    return $this;
  }

  public function setTags(array $tags): void {
    $this->set(self::FIELD_TAGS, $tags);
  }

  public function shouldSendToSocialMedia(): bool {
    return (bool) $this->get(self::FIELD_SEND_TO_SOCIAL_MEDIA)->getString();
  }

}
