<?php

declare(strict_types=1);

namespace Drupal\opdavies_talks\Collection;

use Drupal\node\NodeInterface;
use Drupal\opdavies_talks\Entity\Node\Talk;
use Drupal\paragraphs\ParagraphInterface;
use Illuminate\Support\Collection;

final class TalkCollection extends Collection {

  /**
   * Return the events for the talks in the Collection.
   *
   * @return Collection|ParagraphInterface[]
   */
  public function getEvents(): Collection {
    return $this
      ->flatMap(fn(Talk $talk): Collection => $talk->getEvents());
  }

}
