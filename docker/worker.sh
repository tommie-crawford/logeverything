#!/bin/sh
set -e

php bin/console messenger:setup-transports
exec php bin/console messenger:consume async -vv --memory-limit=256M
