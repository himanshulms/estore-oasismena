#!/usr/bin/env bash
# Compile the child theme SCSS. Run after every edit to style.scss.
#
# Prefers dart-sass (npx sass) and falls back to whatever `sass` is on PATH.
# The system sass here is Ruby Sass 3.7.4 (EOL 2019) - the SCSS is written to
# compile under both, but dart-sass is the one to trust.
set -euo pipefail

THEME="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)/wp-content/themes/estore-child"
SRC="$THEME/assets/scss/style.scss"
OUT="$THEME/assets/scss/style.css"

if command -v npx >/dev/null 2>&1 && npx --no-install sass --version >/dev/null 2>&1; then
    npx --no-install sass --no-source-map "$SRC" "$OUT"
    echo "compiled with dart-sass -> $OUT"
elif command -v sass >/dev/null 2>&1; then
    sass --sourcemap=none "$SRC" "$OUT"
    rm -rf "$THEME/.sass-cache"
    echo "compiled with $(sass --version) -> $OUT"
else
    echo "No sass compiler found. Install dart-sass:  npm i -D sass" >&2
    exit 1
fi

[ "$SRC" -nt "$OUT" ] && echo "WARNING: source is still newer than output" >&2
exit 0
