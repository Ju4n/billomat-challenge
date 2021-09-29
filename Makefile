.PHONY: up down web initialize

initialize:
	docker-compose up --build -d
	docker exec -it challenge-web composer install
	docker exec -it challenge-web setfacl -dR -m u:www-data:rwX -m u:$(whoami):rwX /var/www/html/challenge/storage
	docker exec -it challenge-web setfacl -R -m u:www-data:rwX -m u:$(whoami):rwX /var/www/html/challenge/storage
	docker exec -it challenge-web php artisan migrate:fresh 

up:
	docker-compose up -d
	make cache-clear

web:
	docker exec -it challenge-web bash

down:
	docker-compose down

cache-clear:
	docker exec -it challenge-web php artisan cache:clear

db:
	docker exec -it challenge-db bash
