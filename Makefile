.PHONY: *

phpcs:
	symfony php vendor/bin/phpcs -n \
  		--standard="Drupal,DrupalPractice" \
  		--extensions="php,module,inc,install,test,profile,theme" \
  		--ignore=*/tests/* \
  		--exclude="Drupal.Commenting.ClassComment,Drupal.Commenting.FunctionComment" \
  		web/modules/custom

	symfony php vendor/bin/phpcs -n \
		--standard="Drupal,DrupalPractice" \
		--extensions="php,module,inc,install,test,profile,theme" \
		--exclude="Drupal.Commenting.ClassComment,Drupal.Commenting.DocComment,Drupal.Commenting.FunctionComment,Drupal.NamingConventions.ValidFunctionName" \
		web/modules/custom/**/tests

phpstan:
	vendor/bin/phpstan analyze

phpunit:
	vendor/bin/phpunit web/modules/custom

start:
	docker-compose up -d
	symfony server:start -d

stop:
	symfony server:stop
	docker-compose down

test: phpstan phpunit phpcs

