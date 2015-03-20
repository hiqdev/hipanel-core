<?php
namespace frontend\modules\domain\models;

use Yii;

class Host extends \frontend\components\hiresource\ActiveRecord
{
    /** @inheritdoc */
    public function attributes () {
        return [
            'id','remoteid',
            'seller_id','client_id','domain_id',
            'seller','client','domain',
            'host',
            'ip',
            'ips',
            'created_date',
            'updated_date',
        ];
    }

    /** @inheritdoc */
    public function rules () {
        return [
            [['host'],                                  'safe'],
            [['id'],                                    'safe'],        /// XXX should be id
            [['seller_id','client_id','domain_id'],     'safe'],        /// XXX should be ids
            [['seller','client'],                       'safe'],        /// XXX should be client
            [['domain','host'],                         'safe'],        /// XXX should be domain
            [['ip'],                                    'safe'],        /// XXX should be ip
            [['ips'],                                   'safe'],        /// XXX should be ips
            [['id','ips'],                              'safe', 'on' => 'update'],
        ];
    }

    public function goodStates () {
        return ['ok', 'disabled'];
    }

    /**
     * @return bool
     */
    public function isOperable () {
        if ($this->running_task || !in_array($this->state, $this->goodStates())) {
            return false;
        }

        return true;
    }

    /** @inheritdoc */
    public function attributeLabels () {
        return [
            'id'                    => Yii::t('app', 'ID'),
            'remoteid'              => Yii::t('app', 'Remote ID'),
            'seller_id'             => Yii::t('app', 'Reseller ID'),
            'client_id'             => Yii::t('app', 'Client ID'),
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
