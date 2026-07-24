# Directory Structure

- commited files:
    - `core` -> vendor/hiqdev/%the-core-package%/
    - `composer.json`
    - `composer.lock` - commited at releases
    - `.docker/nginx/etc/nginx/conf.d/vhost.conf` - almost standard but doesn't work when symlinked :(
    - `.env.X`
        - `.env.dist` - production config
        - `.env.local` - local installation config
    - `.gitignore`
    - `testing files` - can be everything in case of using testing frameworks:
        - `phpunit.xml` - for phpunit
        - `codeception.yml` - for codeception
        - `behat.xml` - for behat
        - etc.
    - `.gitlab-ci.yml` - Gitlab CI configuration file
    - `psalm.xml` - psalm config
    - `public/`
        - `assets/.gitignore` - ignore everything, for git to keep the directory
        - `favicon.ico` -> ../vendor/%reseller%/%assets-project%/src/assets/favicon.ico
        - `index.php` -> ../core/public/index.php
        - `robots.txt` -> ../core/public/robots.txt
    - `runtime/.gitignore` - ignore everything, for git to keep the directory
    - `tests/` -> directory with CI tests configuration
- uncommited symlinks:
    - `.env` -> `.env.X`
        - `.env.dist`   for **production**
        - `.env.beta`   for **staging** server with production database
        - `.env.dev`    for **staging** server with test database
        - `.env.local`  for **local** development
    - `docker-compose.yml` -> `core/docker-compose.yml.X` similarly

## Ideas

- we have many similar project installations
- we want to keep root packages as DRY as possible to avoid the need for propagating changes in all the roots
- that's  why we have `core` symlink to core package:
    - `hiqdev/hipanel-core` in **hipanel**
    - `hiqdev/hipanel-site` in **site**
    - `hiqdev/hiam`         in **hiam**
    - `hiqdev/hiapi-legacy` in **hiapi**
- thus standart files and dirs are symlinked from core, reusable between all installations:
    - public/index.php
    - public/robots.txt
    - public/favicon.ico
    - docker-compose.yml
- so project personalization comes down to tweaking several files
- thouroughly handcrafted files with project configuration:
    - composer.json
    - .env.X
        - .env.dist - production config
        - .env.beta - staging with production database
        - .env.dev - staging with test database
        - .env.local - config for local installation
        - .env.myname-dev - personal configurations allowed
    - hidev.yml - not really needed, to be removed
    - config/params.php - not really needed, to be removed
- thoughtfully simlinked files:
    - .env - choose one of .env.XXX
    - docker-compose.yml - normally is a symlink, but may be deriviated from `core/docker-compose.yml.X`
- copy pasted files, to be automated:
    - .docker/nginx/etc/nginx/conf.d/vhost.conf - can include IP restrictions
- also project has evolving files:
    - public/assets/ - needs chmod a+w
    - runtime/ - needs chmod a+w
    - composer.lock - commited at releases
- and other, possibly generated files, not really needed, may be absent:
    - README.md
    - LICENSE
    - ssl/ - normally not needed
        - fullchain.pem
        - privkey.pem

## Examples

- https://git.hiqdev.com/hiqdev/hiam-demo.hipanel.com
- https://git.hiqdev.com/hiqdev/hiapi-demo.hipanel.com
- https://git.hiqdev.com/hiqdev/hipanel-demo.hipanel.com
