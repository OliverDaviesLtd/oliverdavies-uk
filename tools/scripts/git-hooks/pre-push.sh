#!/bin/sh

set -e

bin/phpcs
bin/phpstan analyze
