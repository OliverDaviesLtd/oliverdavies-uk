#!/usr/bin/env bash

set -e

cd web

symfony php ../vendor/bin/drush config-export -y
