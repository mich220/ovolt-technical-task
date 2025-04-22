# Setup
ALIAS              = ovolt
####

.DEFAULT_GOAL      = help
PLATFORM          ?= $(shell uname -s)
EXEC_PHP           = php
SYMFONY            = $(EXEC_PHP) bin/console
COMPOSER           = composer
BIN                = $(ALIAS)-php
DOCKER_GATEWAY    ?= $(shell if [ 'Linux' = "${PLATFORM}" ]; then ip addr show docker0 | awk '$$1 == "inet" {print $$2}' | grep -oE '[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+'; fi)
COMPOSE            = docker compose
EXEC_SF            = $(COMPOSE) exec $(BIN) $(SYMFONY)
DEV_PATH          ?= .docker

#-----------------------------------------------------------------------------------------------------------------------
#-----------------------------------------------------------------------------------------------------------------------
ARG := $(word 2, $(MAKECMDGOALS))
%:
	@:
test-run:
	@echo $(PLATFORM)
help:
	@echo -e '\033[1m make [TARGET] \033[0m'
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
	@echo && $(MAKE) -s env-info
alias: ## auto update aliases in docker files (.env, docker-compose.yaml)
	@sed -i 's/!{ALIAS}/'"$(shell sed -n 's/^ALIAS *=//p' Makefile | xargs)"'/g' ./.docker/.env
#-----------------------------------------------------------------------------------------------------------------------
#-----------------------------------------------------------------------------------------------------------------------

## -- Composer ---------------------------------------------------------------------------------------------------------
install: composer.lock ## Install vendors according to the current composer.lock file
	$(COMPOSER) install --no-progress --no-suggest --prefer-dist --optimize-autoloader

update: composer.json ## Update vendors according to the composer.json file
	$(COMPOSER) update

## -- Xdebug -----------------------------------------------------------------------------------------------------------
xdebug-setup: ## xdebug gateway setup
	@if [ "Linux" = "$(PLATFORM)" ]; then \
		sed "s/DOCKER_GATEWAY/$(DOCKER_GATEWAY)/g" $(DEV_PATH)/config/php/php-ini-overrides.ini.dist > $(DEV_PATH)/config/php/php-ini-overrides.ini; \
	fi

## -- Symfony ----------------------------------------------------------------------------------------------------------
sf: ## List all Symfony commands
	$(EXEC_SF)

cc:
	$(EXEC_SF) c:c

warmup-cache:
	$(EXEC_SF) cache:warmup

fix-perms:
	chmod -R 777 var/*

purge-tmp:
	rm -rf var/cache/* var/logs/*

consume:
	$(EXEC_SF) messenger:consume -vv

migrate: ## Run migrations [arguments: next|n, prev,p][default cmd: d:m:m]
	@if [ "${ARG}" = 'prev' ] || [ "${ARG}" = 'p' ]; then $(EXEC_SF) doctrine:migrations:migrate prev; fi
	@if [ "${ARG}" = 'next' ] || [ "${ARG}" = 'n' ]; then $(EXEC_SF) doctrine:migrations:migrate next; fi
	@if [ "${ARG}" = '' ]; then $(EXEC_SF) doctrine:migrations:migrate --all-or-nothing=true --no-interaction -vv; fi

## -- Docker -----------------------------------------------------------------------------------------------------------
build:
	$(COMPOSE) build 

up:
	$(COMPOSE) up -d

down:
	$(COMPOSE) down

stop:
	$(COMPOSE) stop

volume-prune:
	$(COMPOSE) down -v

clean-images: down
	docker rmi $$(docker image ls | grep -w "${ALIAS}-*" | awk '{print $$3}')

env-info:
	@echo -e '\033[1mCurrent docker environment variables \033[0m'
	@cat .env

## -- Project ----------------------------------------------------------------------------------------------------------
console:
	@if [ "${ARG}" = 'root' ] || [ "${ARG}" = 'r' ]; then docker exec -it -u root $(BIN) bash; fi
	@if [ "${ARG}" = '' ] || [ "${ARG}" = 'developer' ]; then docker exec -it $(BIN) bash; fi


version: ## Show project version
	@echo version: $(VERSION)

## -- Tests ------------------------------------------------------------------------------------------------------------
test: phpunit.xml
	./bin/phpunit --testsuite=main --stop-on-failure

## ---------------------------------------------------------------------------------------------------------------------


## -- Docs --------------------------------------------------------------------------------------------------------------
api-doc:
	docker exec -it $(BIN) bash -c "/application/vendor/bin/openapi src -o /application/filesystem/docs/"
## ---------------------------------------------------------------------------------------------------------------------
