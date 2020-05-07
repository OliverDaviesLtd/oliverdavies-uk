#!/usr/bin/env bash

./bin/drush.sh site:install -y \
  --existing-config \
  --account-pass=admin123
