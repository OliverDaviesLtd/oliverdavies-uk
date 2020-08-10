#!/usr/bin/env bash

run symfony server:stop
run docker-compose down --remove-orphans
