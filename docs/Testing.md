# Testing

As all projects are running in docker environment as all test have to run in docker too.

In case of different testing frameworks there are different commands to run test.

## Running tests by default

For every project which uses `Codeception` framework for testing in `docker-compose.yml.local`
already included selenium image. So all you need to start testing is call codeception from docker-compose.
For example:

    docker-compose exec php-fpm ./vendor/bin/codecept run

For testing `phpunit` or `behat` command is the same but also you can add custom path to configuration file.
For example:

    docker-compose exec php-fpm ./vendor/bin/phpunit -c ./vendor/hiqdev/hiapi-legacy/phpunit.xml.dist
    docker-compose exec php-fpm ./vendor/bin/behat -c ./vendor/hiqdev/billing-mrdp/behat.xml

## Running tests locally 

1. Install JDK
2. Install Selenium Web Driver by executing `download.sh` which is located in your hipanel dir in `tests/software/`
3. Execute `run.sh` (in same folder) to run Selenium Web Driver in background
4. To run tests locally use `codecept` which is located in your hipanel dir in `vendor/bin/`

### Codeception tips

At first you need to configure .env file to codeception can reach you service. For example .env.local.

To limit tests execution scope, use path like this:

```sh
./vendor/bin/codecept run acceptance <path to dir or file>
```

Useful options:

- `--fail-fast (-f)` - stop after first failure
- `--debug (-d)` - show debug and scenario output
- `-v|vv|vvv` - increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

For more info about how to run tests see [codeception manual].

[codeception manual]: https://codeception.com/docs/02-GettingStarted#Running-Tests
