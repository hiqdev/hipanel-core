# Local HiPanel installation and use

HiPanel is split into 4 separate applications:

1. hiapi     [hiapi.advancedhosters.com]
2. hiam       [hiam.advancedhosters.com]
3. hipanel [hipanel.advancedhosters.com]

[hiapi.advancedhosters.com]: https://git.hiqdev.com/advancedhosters/hiapi.advancedhosters.com
[hiam.advancedhosters.com]: https://git.hiqdev.com/advancedhosters/hiam.advancedhosters.com
[hipanel.advancedhosters.com]: https://git.hiqdev.com/advancedhosters/hipanel.advancedhosters.com

To have working system it is necessary to have them all up and running.

## Installation (to be repeated for all applications)

0. Edit `/etc/hosts`, see recommended hosts and IPs
1. Environment install
2. Setup nginx proxy with [nginx-proxy-common](https://github.com/hiqdev/nginx-proxy-common) for `127.0.0.2`
3. `git clone` in a proper directory, see recommended dirs
4. `composer update --ignore-platform-req="ext-*"`
5. Make symlinks, see below (to be moved in hidev deploy)
6. Allow access to docker volume, see below (to be moved in hidev deploy)
7. Tweak `.env` (optional, should not be needed)
8. Chmod for runtime & public/assets
9. `docker-compose up -d` (create all required networks and volumes with commands that docker will suggest)
10. Setup database, see below

### Environment install

- PHP install & all needed extensions ```sudo apt install php8.1```
- Composer install ```curl -sS https://getcomposer.org/installer -o composer-setup.php```
- Docker install ```sudo apt install docker```
- Docker-compose install ```sudo apt install docker-compose```
- JDK install ```sudo apt install default-jre ```(for - local testing)

### Recommended hosts and IPs

Add recommended host names and IPs for local installation to `/ets/hosts`:

```hosts
127.0.0.2   local.hiapi.advancedhosting.com
127.0.0.2   local.hiam.advancedhosting.com
127.0.0.2   local.hipanel.advancedhosting.com
```

These IPs are registered in `.env.local` files, so if you change them be sure
to change there too.

### Recommended dirs

Recommended dirs are:

- `~/prj/hiqdev/hipanel.advancedhosting.com`
- `~/prj/hiqdev/hiam.advancedhosting.com`
- `~/prj/hiqdev/hiapi.advancedhosting.com`
- and so on

## Make symlinks

All root repositories contain `.env.local`.
All core repositories contain `docker-compose.yml.local`.
These files are thoroughly prepared and ready to use.
It is only necessary to symlink them:

```sh
ln -s .env.local .env
ln -s core/docker-compose.yml.local docker-compose.yml
```
Provide read, write and execute for runtime & public/assets in hipanel, hiapi, hiam
``` 
chmod 777 runtime
chmod 777 public/assets
```

Yes. For production installation you just need to use `.dist` versions.

## Access to docker volume

Find docker volume mountpoint with `inspect` command and then chmod it with `a+w`.
Also chmod +x all path. Like this:

```sh
API_VAR_DIR=$(docker volume inspect var-hiapi.demo.hipanel.com -f '{{json .Mountpoint}}')
sudo chmod a+w $API_VAR_DIR
sudo chmod a+x $(dirname $API_VAR_DIR)
sudo chmod a+x $(dirname $(dirname $API_VAR_DIR))
unset API_VAR_DIR
```

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

And migrate again.

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
### Clearing directory with temp files

After server's reboot you have to clear temp data in '''hipanel.demo.hipanel.com'''

'''rm -rf tests/_data/*'''
