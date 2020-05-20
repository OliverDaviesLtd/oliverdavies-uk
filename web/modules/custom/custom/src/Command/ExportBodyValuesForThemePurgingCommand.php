<?php

declare(strict_types=1);

namespace Drupal\custom\Command;

use Drupal\Core\Database\Connection;
use Illuminate\Support\Collection;

final class ExportBodyValuesForThemePurgingCommand {

  private static array $tableNames = [
    'block_content__body',
    'node__body',
  ];

  private string $filename = 'body-field-values.txt';

  private Connection $database;

  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * Drush command to export body field values into a file.
   *
   * @command opdavies:export-body-values-for-theme-purging
   */
  public function handle() {
    $values = Collection::make(self::$tableNames)
      ->flatMap(fn(string $tableName) => $this->getValuesFromTable($tableName))
      ->implode(PHP_EOL);

    file_put_contents($this->getFilePath(), $values);
  }

  private function getFilePath(): string {
    return drupal_get_path('theme', 'opdavies') . DIRECTORY_SEPARATOR . $this->filename;
  }

  private function getValuesFromTable(string $tableName): array {
    return $this->database->select($tableName)
      ->fields($tableName, ['body_value'])
      ->execute()
      ->fetchCol();
  }

}
