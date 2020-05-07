#!/usr/bin/env bash

set -e

docker-compose up -d
symfony server:start -d
