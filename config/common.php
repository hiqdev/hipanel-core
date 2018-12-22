<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

return [
    'aliases' => [
        '@organization' => $params['organization.url'],
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@vendor/bower' => '@vendor/bower-asset',
        '@vendor/npm' => '@vendor/npm-asset',
        '@file' => '/file',
        '@reminder' => '/reminder',
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
            'languages' => [
                'en' => 'English',
                'ru' => 'Русский',
            ],
        ],
    ],
];
