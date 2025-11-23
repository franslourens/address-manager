run-tests:
	php artisan test

run-php:
	php artisan serve

run-npm:
	npm run dev

run-restart-queue:
	php artisan queue:restart

run-queue:
	php artisan queue:work

run-recreate-database:
	php artisan migrate:fresh --seed
	php artisan migrate:fresh --env=testing

run-migrate:
	php artisan migrate
	php artisan migrate --env=testing

run-clear-logs:
	cat /dev/null > storage/logs/laravel.log
	rm storage/logs/*

run-tail-logs:
	tail -f -n 0 storage/logs/*.log
