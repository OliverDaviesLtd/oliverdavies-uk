#!/usr/bin/env bash

run "docker-compose down --volumes --remove-orphans"
run "rm -f tools/workspace/.flag-build"
