SHELL=/bin/bash -e -o pipefail

.PHONY: *

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

