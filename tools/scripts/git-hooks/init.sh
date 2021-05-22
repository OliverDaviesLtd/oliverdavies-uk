#!/bin/sh

set -e

mkdir -p "$PWD/.git/hooks"

ln -sf "$PWD/tools/scripts/git-hooks/pre-push.sh" "$PWD/.git/hooks/pre-push"
