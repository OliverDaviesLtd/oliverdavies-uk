<?php

namespace Drupal\custom\Entity\Node;

use Drupal\discoverable_entity_bundle_classes\ContentEntityBundleInterface;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\ParagraphInterface;
use Illuminate\Support\Collection;

/**
 * Defines an talk node class.
 *
 * @ContentEntityBundleClass(
 *   label = @Translation("Talk"),
 *   entity_type = "node",
 *   bundle = "talk"
 * );
 */
class Talk extends Node implements ContentEntityBundleInterface {

  /**
   * Find the date for the latest event.
   *
   * @return string|null
   */
  public function findLatestEventDate(): ?string {
    return Collection::make($this->get('field_events')->referencedEntities())
      ->map(fn(ParagraphInterface $event) => $event->get('field_date')
        ->getString())
      ->max();
  }

}
