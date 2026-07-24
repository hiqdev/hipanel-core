# Server HiPanel installation

## Naming and directories

### Production

1. API:         api.brand.com
2. ID:          id.brand.com
3. HiPanel:     hipanel.brand.com
4. Site:        brand.com

### Beta, Dev, Local

General idea: **who-type-app.brand.com**

- `who` - for whom, eg: sol, username
- `type` - one of: beta, dev, local
- `app` - hiapi/api, hiam/auth/id, hipanel/panel/admin/cp, hisite/www/site

1. API:         sol-dev-api.brand.com
2. ID:          sol-dev-id.brand.com
3. HiPanel:     sol-dev-hipanel.brand.com
4. Site:        sol-dev-site.brand.com

## Preparations

0. `mkdir ~/prj/brand`
1. Setup network
    - setup and run nginx proxy for required IP(s)
    - setup DNS or add  `/etc/hosts`


## Application installation (to be repeated for all applications)

1. `cd prj/brand; git clone app.brand.com who-type-app.brand.com`
2. `composer update` until it makes no changes
3. `hidev up`
   - add to `hidev up`:
   - `docker network create who-type-app.brand.com`
   - `docker network create pgsql-who-type-app.brand.com`
   - `docker network create redis-who-type-app.brand.com`
   - `docker volume create var-who-type-app.brand.com`
4. 
