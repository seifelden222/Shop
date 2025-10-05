#!/usr/bin/env bash
# Simple wrapper to run the vite JS entry using node, bypassing corrupted .bin shim
NODE_BIN="$(command -v node || command -v nodejs)"
if [ -z "$NODE_BIN" ]; then
  echo "node not found in PATH"
  exit 1
fi
"$NODE_BIN" node_modules/vite/bin/vite.js "$@"
