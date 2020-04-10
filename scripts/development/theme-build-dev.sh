#!/usr/bin/env bash

set -e

. $(dirname "$0")/common.sh

cd $theme_path

npm run development
