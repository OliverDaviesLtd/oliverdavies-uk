#!/usr/bin/env bash

set -e

symfony php vendor/bin/phpcs -n \
  --standard="Drupal,DrupalPractice" \
  --extensions="php,module,inc,install,test,profile,theme" \
  --ignore=*/tests/* \
  --exclude="Drupal.Commenting.ClassComment,Drupal.Commenting.FunctionComment" \
  web/modules/custom

symfony php vendor/bin/phpcs -n \
  --standard="Drupal,DrupalPractice" \
  --extensions="php,module,inc,install,test,profile,theme" \
  --exclude="Drupal.Commenting.ClassComment,Drupal.Commenting.DocComment,Drupal.Commenting.FunctionComment,Drupal.NamingConventions.ValidFunctionName" \
  web/modules/custom/**/tests

vendor/bin/phpstan analyze

symfony php vendor/bin/phpunit \
  "$@"
