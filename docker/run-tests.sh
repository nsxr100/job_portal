#!/usr/bin/env bash
set -euo pipefail

echo "Installing PHP dependencies via Composer..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-progress

echo "Running PHPUnit tests..."
./vendor/bin/phpunit --testdox

echo "Tests finished."
