# Create new panel

This is the manual on creating hipanel site but it must be very much the same for hipanel, hiapi and hiam.

1. Copy
2. Fix names in `hidev.yml,` `composer.json,` `.env.*`
3. `composer update`
4. Add symlinks for `.env` and `docker-compose.yml`
5. `chmod a+w runtime public/assets`

## Copy

Start with copying the root package from an approriate existing hipanel site root project, e.g. `ahnames/0domain.name`.

## Fix names

Fix `.env.dist`, pay attention:

    HOSTS=0domain.name
    HIAM_SITE=hiam.0domain.name
    HIPANEL_URL=https://panel.0domain.name
    REAL_IP=88.208.3.112

Fix `hidev.yml`, pay attention:

    package name - 0domain.name

Fix ` composer.json`, pay attention:

    package name - 0domain.name
    namespace in autoload - ahnames\sites\site\odomainname
    require proper asset - yii-asset-0domain
