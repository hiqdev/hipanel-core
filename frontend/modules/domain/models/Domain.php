<?php
namespace frontend\modules\domain\models;

use Yii;
use yii\base\NotSupportedException;

class Domain extends \frontend\components\hiresource\ActiveRecord
{
    public $key = 'id';

    /**
     * @return array the list of attributes for this record
     */
    public function attributes () {
        return [
            'id',
            'epp_client_id',
            'remoteid',
            'state',
            'state_label',
            'statuses',
            'errors',
            'name',
            'zone_id',
            'zone',
            'domain',
            'note',
            'nameservers',
            'created_date',
            'updated_date',
            'transfer_date',
            'expiration_date',
            'expires',
            'since',
            'lastop',
            'operated',
            'whois_protected',
            'block',
            'is_secured',
            'is_holded',
            'autorenewal',
            'is_freezed',
            'client_id',
            'client',
            'client_name',
            'seller_id',
            'seller',
            'seller_name',
            'foa_sent_to',
            'is_premium',
            'prem_expires',
            'prem_daysleft',
            'premium_autorenewal',
            'url_fwval',
            'mailval',
            'parkval',
            'daysleft',
            'is_expired',
            'expires_soon',
        ];
    }

    public function rules () {
        return [
            [['domain'], 'required'],
            [['id'], 'safe'],
            [['is_secured'],'safe'],
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
            'epp_client_id'         => Yii::t('app', 'EPP client ID'),
            'remoteid'              => Yii::t('app', 'Remote ID'),
            'state'                 => Yii::t('app', 'Status'),
            'state_label'           => Yii::t('app', 'Status'),
            'statuses'              => Yii::t('app', 'Statuses'),
            'errors'                => Yii::t('app', 'Errors'),
            'name'                  => Yii::t('app', 'Name'),
            'zone_id'               => Yii::t('app', 'Zone ID'),
            'zone'                  => Yii::t('app', 'Zone'),
            'domain'                => Yii::t('app', 'Domain Name'),
            'note'                  => Yii::t('app', 'Notes'),
            'nameservers'           => Yii::t('app', 'NameServers'),
            'created_date'          => Yii::t('app', 'Registered'),
            'updated_date'          => Yii::t('app', 'Update Time'),
            'transfer_date'         => Yii::t('app', 'Transfered'),
            'expiration_date'       => Yii::t('app', 'System Expiration Time'),
            'expires'               => Yii::t('app', 'Payed Till'),
            'since'                 => Yii::t('app', 'Since Time'),
            'lastop'                => Yii::t('app', 'Last Operation'),
            'operated'              => Yii::t('app', 'Last Operation Time'),
            'whois_protected'       => Yii::t('app', 'WHOIS'),
            'is_secured'            => Yii::t('app', 'Lock'),
            'is_holded'             => Yii::t('app', ' label'),
            'autorenewal'           => Yii::t('app', ' label'),
            'is_freezed'            => Yii::t('app', ' label'),
            'client_id'             => Yii::t('app', 'Client ID'),
            'client'                => Yii::t('app', 'Client'),
            'client_name'           => Yii::t('app', 'Client Name'),
            'seller_id'             => Yii::t('app', 'Seller ID'),
            'seller'                => Yii::t('app', 'Seller'),
            'seller_name'           => Yii::t('app', 'Seller Name'),
            'foa_sent_to'           => Yii::t('app', ' label'),
            'is_premium'            => Yii::t('app', ' label'),
            'prem_expires'          => Yii::t('app', ' label'),
            'prem_daysleft'         => Yii::t('app', ' label'),
            'premium_autorenewal'   => Yii::t('app', ' label'),
            'url_fwval'             => Yii::t('app', ' label'),
            'mailval'               => Yii::t('app', ' label'),
            'parkval'               => Yii::t('app', ' label'),
            'daysleft'              => Yii::t('app', ' label'),
            'is_expired'            => Yii::t('app', ' label'),
            'expires_soon'          => Yii::t('app', ' label'),
        ];
    }
}
