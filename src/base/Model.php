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
            'id'            => Yii::t('app', 'ID'),
            'client_id'     => Yii::t('app', 'Client'),
            'seller'        => Yii::t('app', 'Reseller'),
            'seller_id'     => Yii::t('app', 'Reseller'),
            'domain_id'     => Yii::t('app', 'Domain ID'),
            'domain'        => Yii::t('app', 'Domain Name'),
            'hdomain_id'    => Yii::t('app', 'Domain ID'),
            'hdomain'       => Yii::t('app', 'Domain Name'),
            'server_id'     => Yii::t('app', 'Server ID'),
            'account_id'    => Yii::t('app', 'Account ID'),
            'service_id'    => Yii::t('app', 'Service ID'),
            'tariff_id'     => Yii::t('app', 'Tariff ID'),
            'contact_id'    => Yii::t('app', 'Contact ID'),
            'article_id'    => Yii::t('app', 'Article ID'),
            'host_id'       => Yii::t('app', 'Host ID'),
            'bill_id'       => Yii::t('app', 'Bill ID'),
            'backup_id'     => Yii::t('app', 'Backup ID'),
            'backuping_id'  => Yii::t('app', 'Backuping ID'),
            'crontab_id'    => Yii::t('app', 'Crontab ID'),
            'ip_id'         => Yii::t('app', 'IP ID'),
            'ip'            => Yii::t('app', 'IP'),
            'mail_id'       => Yii::t('app', 'Mail ID'),
            'request_id'    => Yii::t('app', 'Request ID'),
            'db_id'         => Yii::t('app', 'DataBase ID'),
            'db'            => Yii::t('app', 'DataBase'),
            'state_id'      => Yii::t('app', 'Status ID'),
            'state_label'   => Yii::t('app', 'Status label'),
            'state'         => Yii::t('app', 'Status'),
            'type'          => Yii::t('app', 'Type'),
            'type_id'       => Yii::t('app', 'Type'),
            'type_label'    => Yii::t('app', 'Type'),
            'descr'         => Yii::t('app', 'Description'),
       ];
    }

    /**
     * Merge Attribute labels for Model.
     */
    public function mergeAttributeLabels($labels)
    {
        $attributeLabels = [];
        $default = $this->defaultAttributeLabels();
        foreach ($this->rules() as $d) {
            if (is_string(reset($d))) {
                continue;
            }
            foreach (reset($d) as $k) {
                $attributeLabels[$k] = $labels[$k] ?: $default[$k];
                if (!$attributeLabels[$k]) {
                    $toTranslate = preg_replace(['/_id$/', '/_label$/', '/_ids$/', '/_like$/'], [' ID', '', ' IDs', ''], $k);
                    $toTranslate = preg_replace('/_/', ' ', $toTranslate);
                    $attributeLabels[$k] = Yii::t(static::$i18nDictionary, ucfirst($toTranslate));
                }
            }
        }
        return $attributeLabels;
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
