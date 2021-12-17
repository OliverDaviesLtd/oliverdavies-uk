<?php

declare(strict_types=1);

namespace Drupal\opdavies_blog\Entity\Node;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\taxonomy\Entity\Term;
use Illuminate\Support\Collection;

final class Post {

  public const FIELD_EXTERNAL_LINK = 'field_external_link';
  public const FIELD_HAS_TWEET = 'field_has_tweet';
  public const FIELD_SEND_TO_SOCIAL_MEDIA = 'field_send_to_social_media';
  public const FIELD_SENT_TO_SOCIAL_MEDIA = 'field_sent_to_social_media';
  public const FIELD_TAGS = 'field_tags';

  private NodeInterface $node;

  public function __construct(EntityInterface $node) {
    $this->node = $node;
  }

  public function bundle(): string {
    return 'post';
  }

  public function get(string $name): FieldItemListInterface {
    return $this->node->get($name);
  }

  public function getExternalLink(): ?array {
    return ($link = $this->get(self::FIELD_EXTERNAL_LINK)->get(0))
      ? $link->getValue()
      : NULL;
  }

  public function getNode(): NodeInterface {

    return $this->node;
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

  public function id(): int {
    return (int) $this->node->id();
  }

  public function isExternalPost(): bool {
    return (bool) $this->getExternalLink();
  }

  public function label(): string {
    return $this->node->label();
  }

  public function markAsSentToSocialMedia(): self {
    $this->set(self::FIELD_SENT_TO_SOCIAL_MEDIA, TRUE);

    return $this;
  }

  public function save(): void {
    $this->node->save();
  }

  public function set(string $name, $value): void {
    $this->node->set($name, $value);
  }

  public function setTags(array $tags): void {
    $this->set(self::FIELD_TAGS, $tags);
  }

  public function shouldSendToSocialMedia(): bool {
    return (bool) $this->get(self::FIELD_SEND_TO_SOCIAL_MEDIA)->getString();
  }

  public function url(string $type, array $options = []): string {
    return $this->node->url($type, $options);
  }

  public static function createFromNode(EntityInterface $node): self {
    // TODO: ensure that this is a node and a `post` type.
    return new self($node);
  }

}
