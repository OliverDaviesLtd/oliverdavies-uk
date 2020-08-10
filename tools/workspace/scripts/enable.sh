#!/usr/bin/env bash

if [ ! -f "tools/workspace/.flag-built" ];
then
  ws install

  touch tools/workspace/.flag-built
fi

run "docker-compose up -d"
