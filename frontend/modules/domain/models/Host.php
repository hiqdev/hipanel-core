<?php
namespace frontend\modules\domain\models;

use Yii;
use yii\base\NotSupportedException;

class Host extends \frontend\components\hiresource\ActiveRecord
{
    /**
     * @return array the list of attributes for this record
     */
    public function attributes () {
        return [
            'id',
            'ip',
            'ips',
            'remoteid',
            'client_id',
            'client',
            'seller_id',
            'seller',
            'host',
            'domain_id',
            'domain',
            'created_date',
            'updated_date',
        ];
    }

    public function rules () {
        return [
            [['host'], 'required'],
            [['id'], 'safe']
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

    /**
     * @inheritdoc
     */
    public function attributeLabels () {
        return [
            'id'                    => Yii::t('app', 'ID'),
            'ip'                    => Yii::t('app', 'IP'),
            'ips'                   => Yii::t('app', 'IPs'),
            'remoteid'              => Yii::t('app', 'Remote ID'),
            'client_id'             => Yii::t('app', 'Client ID'),
            'client'                => Yii::t('app', 'Client'),
            'seller_id'             => Yii::t('app', 'Seller ID'),
            'seller'                => Yii::t('app', 'Seller'),
            'state'                 => Yii::t('app', 'State'),
            'host'                  => Yii::t('app', 'Name'),
            'domain_id'             => Yii::t('app', 'Domain ID'),
            'domain'                => Yii::t('app', 'Domain'),
            'created_date'          => Yii::t('app', 'Create Time'),
            'updated_date'          => Yii::t('app', 'Update Time'),
        ];
    }
}
