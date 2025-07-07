<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

return [
    'aliases' => [
        '@organization' => $params['organization.url'],
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@vendor/bower' => '@vendor/bower-asset',
        '@vendor/npm' => '@vendor/npm-asset',
        '@file' => '/file',
    ],
    'components' => [
        'cache' => [
            'class' => \hipanel\components\Cache::class,
        ],
        'i18n' => [
            'class' => \hipanel\components\I18N::class,
            'translations' => [
                'hipanel' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hipanel/messages',
                ],
                'hipanel:synt' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hipanel/messages',
                ],
                'hipanel:block-reasons' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hipanel/messages',
                ],
                'hipanel.object-combo' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@hipanel/messages',
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
            'languages' => $params['language.languages'] ?? [
                'en' => 'English',
                'ru' => 'Русский',
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
