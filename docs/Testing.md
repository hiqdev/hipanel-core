# Testing

## Running tests locally

1. Install JDK
2. Install Selenium Web Driver by executing `download.sh` which is located in your hipanel dir in `tests/software/`
3. Execute `run.sh` (in same folder) to run Selenium Web Driver in background
4. To run tests locally use `codecept` which is located in your hipanel dir in `vendor/bin/`

### Codeception tips

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

## Running tests inside Docker

// How to?

## Contributing to Codeception tests

// How to run tests and enhance them?

