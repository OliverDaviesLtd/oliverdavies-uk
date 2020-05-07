<?php

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
class OpdRedirect extends ProcessPluginBase {

  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (Str::startsWith($value, '/')) {
      return "internal:{$value}";
    }

    return $value;
  }

}
