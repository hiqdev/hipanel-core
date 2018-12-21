# Testing

## Running tests locally

1. Install JDK
2. Install Selenium Web Driver by executing `download.sh` which is located in your hipanel dir in `tests/software/`
3. Execute `run.sh` (in same folder) to run on background
4. To run tests locally use `codecept` which is located in your hipanel dir in `vendor/bin/`

For more info about how to run tests see [codeception manual].

## Running tests inside Docker

// How to?

## Contributing to Codeception tests

// How to run tests and enhance them?

To limit tests execution scope to a single cest, use path to file like this:

```sh
./vendor/bin/codecept run <path to file>
```

Also see [codeception manual].

[codeception manual]: https://codeception.com/docs/02-GettingStarted#Running-Tests

