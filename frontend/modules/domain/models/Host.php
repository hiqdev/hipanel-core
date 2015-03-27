<?php

namespace frontend\modules\domain\models;

use Yii;

class Host extends \frontend\components\Model
{
    use \frontend\components\ModelTrait;

    /** @inheritdoc */
    public function rules () {
        return [
            [['host'],                                  'safe'],
            [['id'],                                    'safe'],
            [['seller_id','client_id','domain_id'],     'safe'],
            [['seller','client'],                       'safe'],
            [['domain','host'],                         'safe'],
            [['ip'],                                    'safe'],
            [['ips'],                                   'safe'],
            [['ips'],                                   'safe', 'on' => 'update'],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels () {
        return [
            'id'                    => Yii::t('app', 'ID'),
            'remoteid'              => Yii::t('app', 'Remote ID'),
            'seller_id'             => Yii::t('app', 'Reseller'),
            'client_id'             => Yii::t('app', 'Client'),
            'domain_id'             => Yii::t('app', 'Domain ID'),
            'seller'                => Yii::t('app', 'Reseller'),
            'client'                => Yii::t('app', 'Client'),
            'state'                 => Yii::t('app', 'State'),
            'domain'                => Yii::t('app', 'Domain'),
            'host'                  => Yii::t('app', 'Name Server'),
            'ip'                    => Yii::t('app', 'IP'),
            'ips'                   => Yii::t('app', 'IPs'),
            'created_date'          => Yii::t('app', 'Create Time'),
            'updated_date'          => Yii::t('app', 'Update Time'),
        ];
    }
}
