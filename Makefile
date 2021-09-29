.PHONY: up down web initialize run-tests migrate-fresh

initialize:
	docker-compose up --build -d
	docker exec -it challenge-web composer install
	docker exec -it challenge-web setfacl -dR -m u:www-data:rwX -m u:$(whoami):rwX /var/www/html/challenge/storage
	docker exec -it challenge-web setfacl -R -m u:www-data:rwX -m u:$(whoami):rwX /var/www/html/challenge/storage

migrate-fresh:
	docker exec -it challenge-web php artisan migrate:fresh

up:
	docker-compose up -d

down:
	docker-compose down

run-tests:
	docker exec -it challenge-web vendor/bin/phpunit tests/ProfileTest.php --testdox
