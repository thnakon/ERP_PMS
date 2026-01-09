#!/bin/bash
# Laravel Scheduler Cron Script for Docker
# This script runs the Laravel scheduler every minute

cd /Users/admin/Desktop/obounerp
docker compose exec -T app php artisan schedule:run >> /dev/null 2>&1
