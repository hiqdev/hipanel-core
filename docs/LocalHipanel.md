# Local HiPanel installation and use

HiPanel is split into 3 separate applications:

1. hiapi     [hiapi-demo.hipanel.com]
2. hiam       [hiam-demo.hipanel.com]
3. hipanel [hipanel-demo.hipanel.com]

[hiapi-demo.hipanel.com]:     https://git.hiqdev.com/hiqdev/hiapi-demo.hipanel.com
[hiam-demo.hipanel.com]:       https://git.hiqdev.com/hiqdev/hiam-demo.hipanel.com
[hipanel-demo.hipanel.com]: https://git.hiqdev.com/hiqdev/hipanel-demo.hipanel.com

To have working system it is necessary to have them all up and running.

## Installation (to be repeated for all applications)

0. edit `/etc/hosts`, see recommended hosts and IPs
1. `git clone` in a proper directory, see recommended dirs
2. `composer update`
3. `hidev deploy`
4. make symlinks, see below (to be moved in hidev deploy)
5. tweak `.env` (optional, should not be needed)
6. setup database, see below
7. create all required networks and volumes with commands that docker will suggest
8. `docker-compose up -d`

### Recommended hosts and IPs

Add recommended host names and IPs for local installation to `/ets/hosts`:

```hosts
127.0.30.1    hiapi-demo.hipanel.local
127.0.30.2     hiam-demo.hipanel.local
127.0.30.3  hipanel-demo.hipanel.local
127.0.30.4   hisite-demo.hipanel.local
```

These IPs are registered in `.env.local` files, so if you changes them be sure
to change there too.

### Recommended dirs

Recommended dirs are `~/prj/hiqdev/hipanel-demo.hipanel.local` and so on

### Make symlinks

All root repositories contain `docker-compose.yml.local` and `.env.local`.
These files are thoroughly prepared and ready to use.
It is only necessary to symlink them:

```sh
ln -s .env.local .env                            
ln -s docker-compose.yml.local docker-compose.yml
```

Yes. For production installation you just need to use `.dist` versions.

## Setup/Reset database

Database is run in `hiapi` docker compose.
To setup database run on a freshly started `hiapi` docker compose:

```sh
docker-compose exec php-fpm /app/vendor/bin/hidev migrate/up
```

To reset database reset container:

```sh
docker-compose down
docker-compose up -d
```

And setup (migrate) again.

## Development


## Testing

See [Testing] manual.

[Testing]: Testing.md

Long story short:

#### API Smoke tests

In hiapi dir:

```sh
docker-compose exec php-fpm /app/vendor/bin/phpunit
```

#### Panel Codeception tests

In hipanel dir:

```sh
codecept run -f -vvv --debug
```
