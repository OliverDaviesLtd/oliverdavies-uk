<?php

declare(strict_types=1);

namespace Drupal\opdavies_talks\Plugin\views\sort;

use Carbon\Carbon;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\views\Annotation\ViewsSort;
use Drupal\views\Plugin\views\sort\Date;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @ViewsSort("event_sort")
 */
final class Event extends Date {

  private TimeInterface $time;

  public function __construct(
    array $configuration,
    string $pluginId,
    array $pluginDefinition,
    TimeInterface $time
  ) {
    parent::__construct($configuration, $pluginId, $pluginDefinition);

    $this->time = $time;
  }

  public static function create(
    ContainerInterface $container,
    array $configuration,
    $pluginId,
    $pluginDefinition
  ) {
    return new static(
      $configuration,
      $pluginId,
      $pluginDefinition,
      $container->get('datetime.time')
    );
  }

  public function query(): void {
    $this->ensureMyTable();

    $currentDate = Carbon::parse('today')->getTimestamp();

    $dateAlias = "$this->tableAlias.$this->realField";

    // Is this event in the past?
    $this->query->addOrderBy(
      NULL,
      sprintf("%d > %s", $currentDate, $dateAlias),
      $this->options['order'],
      "in_past"
    );

    // How far in the past/future is this event?
    $this->query->addOrderBy(
      NULL,
      sprintf('ABS(%s - %d)', $dateAlias, $currentDate),
      $this->options['order'],
      "distance_from_now"
    );
  }

}
