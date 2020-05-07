#!/usr/bin/env bash

set -e

symfony server:stop
docker-compose down
