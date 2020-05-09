<?php

declare(strict_types=1);

namespace Drupal\custom\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Illuminate\Support\Str;

/**
 * Perform custom value transformations.
 *
 * @MigrateProcessPlugin(
 *   id = "opd_redirect"
 * )
 */
final class OpdRedirect extends ProcessPluginBase {

  public function transform($value, MigrateExecutableInterface $migrateExecutable, Row $row, $destinationProperty) {
    if (Str::startsWith($value, '/')) {
      return "internal:{$value}";
    }

    return $value;
  }

}
