<?php

$databases['default']['default'] = [
  'driver' => 'mysql',
  'host' => 'mysql',
  'database' => getenv('MYSQL_DATABASE'),
  'username' => getenv('MYSQL_USER'),
  'password' => getenv('MYSQL_PASSWORD'),
  'port' => '3306',
  'prefix' => '',
  'collation' => 'utf8mb4_general_ci',
];

$settings['trusted_host_patterns'] = [
  '^localhost$',
];
