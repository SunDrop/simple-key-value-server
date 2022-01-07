docker_compose_file=docker/docker-compose.yml
env_file=docker/variables.env
composer_file=src/composer.json
.PHONY: help

help:
	@echo "\033[32mmake up \033[0m- up all containers"

logs:
	# Show (and watch) logs of services running
	docker-compose --env-file $(env_file) -f $(docker_compose_file) logs -f

up:
	@echo "\033[32mStarting containers...\033[0m"
	@docker-compose --env-file $(env_file) -f $(docker_compose_file) up -d
	@docker-compose --env-file $(env_file) -f $(docker_compose_file) exec php composer install

down:
	@echo "\033[32mStop containers...\033[0m"
	@docker-compose --env-file $(env_file) -f $(docker_compose_file) down -v --remove-orphans;

rebuild:
	@echo "\033[32mRebuild containers...\033[0m"
	@docker-compose --env-file $(env_file) -f $(docker_compose_file) build --force-rm --no-cache;

php:
	@echo "\033[32mEntering into php container...\033[0m"
	@docker-compose --env-file $(env_file) -f $(docker_compose_file) exec php bash

nginx:
	@echo "\033[32mEntering into nginx container...\033[0m"
	@docker-compose --env-file $(env_file) -f $(docker_compose_file) exec nginx bash

composer_install:
	@echo "\033[32mInstall dependency...\033[0m"
	@docker-compose --env-file $(env_file) -f $(docker_compose_file) exec php composer install
