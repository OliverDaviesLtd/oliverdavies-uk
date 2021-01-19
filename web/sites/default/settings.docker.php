<?php

$databases['default']['default'] = [
  'driver' => 'mysql',
  'host' => 'mysql',
  'database' => 'drupal',
  'username' => 'drupal',
  'password' => 'drupal',
  'port' => '3306',
  'prefix' => '',
  'collation' => 'utf8mb4_general_ci',
];

$settings['trusted_host_patterns'] = [
  '^localhost$',
];
