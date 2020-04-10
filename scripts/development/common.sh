#!/usr/bin/env bash

set -e

theme_path="web/themes/custom/opdavies"

function run_command {
  title=$1
  command=$2

  echo -e "\e[32m$title\e[0m"
  echo -e "\e[33mExecuting: \e[0m$command"
  $command
}
