#!/usr/bin/env bash

set -e

function run_command {
  title=$1
  command=$2

  echo -e "\e[32m$title\e[0m"
  echo -e "\e[33mExecuting: \e[0m$command"
  $command
}
