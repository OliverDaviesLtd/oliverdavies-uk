SHELL=/bin/bash -e -o pipefail

THEME_PATH:=web/themes/custom/opdavies

.PHONY: *

clean: theme-clean

phpcs:
	symfony php vendor/bin/phpcs -n \
		--standard="Drupal,DrupalPractice" \
		--extensions="php,module,inc,install,test,profile,theme" \
		--exclude="Drupal.Commenting.ClassComment,Drupal.Commenting.FunctionComment,Drupal.Commenting.VariableComment" \
		web/modules/custom \
		web/themes/custom

phpstan:
	vendor/bin/phpstan analyze

phpunit:
	vendor/bin/phpunit web/modules/custom

pull-from-prod:
	# Download and import the database.
	ansible-playbook tools/ansible/download-database.yml
	zcat < dump.sql.gz | bin/drush.sh sql-cli
	rm dump.sql.gz

	# Post import steps.
	bin/drush.sh sql-sanitize -y --sanitize-password=password
	bin/drush.sh updatedb -y
	bin/drush.sh cache-rebuild
	bin/drush.sh user-login 1

start:
	docker-compose up -d
	symfony server:start -d

stop:
	symfony server:stop
	docker-compose down

test: phpstan phpunit phpcs

theme-build: theme-install-dependencies
	cd ${THEME_PATH} && npm run dev

theme-build-prod: theme-install-dependencies
	bin/drush.sh opdavies:export-body-values-for-theme-purging
	cd ${THEME_PATH} && npm run prod

theme-clean: ${THEME_PATH}/build ${THEME_PATH}/node_modules
	rm -fr ${THEME_PATH}/build
	rm -fr ${THEME_PATH}/node_modules

theme-install-dependencies: ${THEME_PATH}/package.json ${THEME_PATH}/package-lock.json
	cd ${THEME_PATH} && npm install

theme-watch: theme-build
	cd ${THEME_PATH} && npm run watch
