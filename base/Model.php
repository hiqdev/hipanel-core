<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\base;

use Yii;

class Model extends \hiqdev\hiart\ActiveRecord
{

    /**
     * No rules be default
     */
    public function rules ()
    {
        return [];
    }

    /**
     * return default labels for attribute
     */
    public function defaultAttributeLabels () {
        return [
            'id'            => Yii::t('app', 'ID'),
            'client_id'     => Yii::t('app', 'Client'),
            'seller_id'     => Yii::t('app', 'Seller'),
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
            'type_id'       => Yii::t('app', 'Type ID'),
            'type_label'    => Yii::t('app', 'Type label'),
       ];
    }

    /**
     * Merge Attribute labels for Model
     */
    public function mergeAttributeLabels($labels) {
        $attributeLabels = [];
        $default = $this->defaultAttributeLabels();
        foreach ($this->rules() as $d) {
            if (is_string(reset($d))) continue;
            foreach (reset($d) as $k) $attributeLabels[$k] = $labels[$k] ? : $default[$k] ? : Yii::t('app', ucfirst($k));
        }
        return $attributeLabels;
    }
}
