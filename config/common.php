<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

/** @var array $params */

return [
    'aliases' => [
        '@organization' => $params['organization.url'],
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@vendor/bower' => '@vendor/bower-asset',
        '@vendor/npm' => '@vendor/npm-asset',
        '@file' => '/file',
        '@HIAM_SITE' => (YII_ENV === 'prod' ? 'https://' : 'http://') . $params['hiam.site'],
    ],
    'components' => [
        'cache' =>
            $params['cache.driver'] === 'memcached'
            ? [
                'class' => \yii\caching\MemCache::class,
                'useMemcached' => true,
                'servers' => [
                    'memcached' => [
                        'host' => $params['memcached.host'],
                        'port' => 11211,
                        'weight' => 60,
                    ],
                ],
            ]
            : ['class' => \hipanel\components\Cache::class]
        ,
        'i18n' => [
            'class' => \hipanel\components\I18N::class,
            'translations' => [
                'hipanel' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => dirname(__DIR__) . '/src/messages',
                ],
                'hipanel:synt' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => dirname(__DIR__) . '/src/messages',
                ],
                'hipanel:block-reasons' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => dirname(__DIR__) . '/src/messages',
                ],
                'hipanel.object-combo' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => dirname(__DIR__) . '/src/messages',
                ],
            ],
        ],
    ],
    'modules' => [
        'hipanel' => [
            'class' => \hipanel\Module::class,
            'notPanel' => $params['hipanel.notPanel'],
            'panelUrl' => $params['hipanel.url'],
        ],
        'language' => [
            'languages' => [
                'en' => 'English',
                'ru' => 'Русский',
            ],
        ],
        'debug' => !empty($params['debug.event.enable']) ? [] : [
            'panels' => [
                'event' => [
                    'class' => \hipanel\panels\FakeEventPanel::class,
                ],
            ],
        ],
    ],
    'container' => [
        'singletons' => [
            \Psr\Container\ContainerInterface::class => function (\yii\di\Container $container) {
                return new class($container) implements \Psr\Container\ContainerInterface {
                    /**
                     * @var \yii\di\Container
                     */
                    private $container;

                    public function __construct(\yii\di\Container $container)
                    {
                        $this->container = $container;
                    }

                    public function get($id)
                    {
                        return $this->container->get($id);
                    }

                    public function has($id)
                    {
                        return $this->container->has($id);
                    }
                };
            },
        ],
    ],
];
