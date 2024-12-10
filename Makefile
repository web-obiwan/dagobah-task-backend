# Variables
PHP := php
COMPOSER := composer
SYMFONY := symfony
CONSOLE := bin/console
ENV := dev

# Cibles principales
install: composer-install assets-install

start: server-start

# Gestion des dépendances
composer-install:
	$(COMPOSER) install

assets-install:
	npm install
	npm run build

# Gestion de la base de données
db-create:
	$(PHP) $(CONSOLE) doctrine:database:create --env=$(ENV)

db-migrate:
	$(PHP) $(CONSOLE) doctrine:migrations:migrate --no-interaction --env=$(ENV)


# Lancement du serveur
deploy:
	make install
	make db-create
	make db-migrate

server-start:
	$(SYMFONY) server:start

server-stop:
	$(SYMFONY) server:stop

# Quality
lint: ##
	$(COMPOSER) run lint

phpstan: ##
	$(COMPOSER) run lint:phpstan -- --memory-limit=1G

phpcs: ##
	$(COMPOSER) run lint:phpcs

phpcs-fix:
	$(COMPOSER) run lint:phpcs:fix

# Tests
test:
	$(PHP) bin/phpunit

.PHONY: install start composer-install assets-install db-create db-migrate db-drop deploy server-start server-stop test
