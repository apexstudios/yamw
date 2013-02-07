#!/usr/bin/env sh
SRC_DIR="`pwd`"
cd "`dirname "$0"`"
cd "../phpunit/dbunit"
BIN_TARGET="`pwd`/dbunit.php"
cd "$SRC_DIR"
"$BIN_TARGET" "$@"
