<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\base;

use Yii;

class Model extends \hiqdev\hiart\ActiveRecord
{
    /**
     * @var string the i18n dictionary name that will be used to translate labels of Model
     */
    public static $i18nDictionary = 'app';

    /**
     * No rules be default.
     */
    public function rules()
    {
        return [];
    }

    /**
     * return default labels for attribute.
     */
    public function defaultAttributeLabels()
    {
        return [
            'id'            => Yii::t('hipanel', 'ID'),
            'client'        => Yii::t('hipanel', 'Client'),
            'seller'        => Yii::t('hipanel', 'Reseller'),
            'domain'        => Yii::t('hipanel', 'Domain Name'),
            'hdomain'       => Yii::t('hipanel', 'Domain Name'),
            'server'        => Yii::t('hipanel', 'Server'),
            'account'       => Yii::t('hipanel', 'Account'),
            'service'       => Yii::t('hipanel', 'Service'),
            'tariff'        => Yii::t('hipanel', 'Tariff'),
            'contact'       => Yii::t('hipanel', 'Contact'),
            'article'       => Yii::t('hipanel', 'Article'),
            'host'          => Yii::t('hipanel', 'Host'),
            'bill'          => Yii::t('hipanel', 'Bill'),
            'backup'        => Yii::t('hipanel', 'Backup'),
            'backuping'     => Yii::t('hipanel', 'Backuping'),
            'crontab'       => Yii::t('hipanel', 'Crontab'),
            'ip'            => Yii::t('hipanel', 'IP'),
            'mail'          => Yii::t('hipanel', 'Mail'),
            'request'       => Yii::t('hipanel', 'Request'),
            'db'            => Yii::t('hipanel', 'DataBase'),
            'state'         => Yii::t('hipanel', 'Status'),
            'status'        => Yii::t('hipanel', 'Status'),
            'type'          => Yii::t('hipanel', 'Type'),
            'descr'         => Yii::t('hipanel', 'Description'),
       ];
    }

    protected static $mergedLabels = [];

    /**
     * Merge Attribute labels for Model.
     */
    public function mergeAttributeLabels($labels)
    {
        if (!isset(static::$mergedLabels[static::class])) {
            $default = $this->defaultAttributeLabels();
            foreach ($this->attributes() as $k) {
                $label = $labels[$k] ?: $default[$k];
                if (!$label) {
                    if (preg_match('/(.+)_[a-z]+$/', $k, $m)) {
                        if (isset($labels[$m[1]])) {
                            $label = $labels[$m[1]];
                        }
                    }
                }
                if (!$label) {
                    $toTranslate = preg_replace(['/_id$/', '/_label$/', '/_ids$/', '/_like$/'], [' ID', '', ' IDs', ''], $k);
                    $toTranslate = preg_replace('/_/', ' ', $toTranslate);
                    $label = Yii::t(static::$i18nDictionary, ucfirst($toTranslate));
                }
                $labels[$k] = $label;
            }
            static::$mergedLabels[static::class] = $labels;
        }

        return static::$mergedLabels[static::class];
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function getSeller()
    {
        return $this->seller;
    }

    public function getSellerId()
    {
        return $this->seller_id;
    }
}
