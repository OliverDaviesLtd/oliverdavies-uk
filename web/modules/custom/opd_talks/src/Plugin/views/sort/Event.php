<?php

declare(strict_types=1);

namespace Drupal\opd_talks\Plugin\views\sort;

use Drupal\views\Plugin\views\sort\Date;
use Drupal\views\Annotation\ViewsSort;

/**
 * @ViewsSort("event_sort")
 */
final class Event extends Date {

  public function query() {
    $this->ensureMyTable();

    $currentTime = time();
    $dateAlias = "$this->tableAlias.$this->realField";

    // Is this event in the past?
    $this->query->addOrderBy(
      NULL,
      sprintf("%d > %s", $currentTime, $dateAlias),
      $this->options['order'],
      "in_past"
    );

    // How far in the past/future is this event?
    $this->query->addOrderBy(
      NULL,
      sprintf('ABS(%s - %d)', $dateAlias, $currentTime),
       $this->options['order'],
       "distance_from_now"
     );
  }

}
