<?php

declare(strict_types=1);

namespace Drupal\custom\Command;

use Illuminate\Support\Collection;

final class ExportBodyValuesForThemePurgingCommand {

  /**
   * Drush command for updating legacy tag names.
   *
   * @command opdavies:export-body-values-for-theme-purging
   */
  public function exportBodyValues() {
    $blockValues = db_query('SELECT body_value FROM {block_content__body}')->fetchCol();
    $nodeValues = db_query('SELECT body_value FROM {node__body}')->fetchCol();

    $values = (new Collection([...$blockValues, ...$nodeValues]))
      ->implode(PHP_EOL);

    file_put_contents(drupal_get_path('theme', 'opdavies') . '/body-field-values.txt', $values);
  }

}
