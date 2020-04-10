#!/usr/bin/env bash

set -e

. $(dirname "$0")/common.sh

run_command "Install Composer dependencies" "symfony composer install"

cd web

run_command "Install Drupal" "symfony php ../vendor/bin/drush site-install -y --existing-config"
