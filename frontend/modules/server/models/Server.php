<?php
namespace frontend\modules\server\models;

use Yii;
use yii\base\NotSupportedException;

class Server extends \frontend\components\hiresource\ActiveRecord
{
    /**
     * @return array the list of attributes for this record
     */
    public function attributes () {
        return [
            'id',
            'name',
            'server_like',
            'seller',
            'seller_id',
            'client',
            'client_id',
            'panel',
            'parent_tariff',
            'tariff',
            'tariff_note',
            'discounts',
            'request_state',
            'request_state_label',
            'state_label',
            'status_time',
            'sale_time',
            'autorenewal',
            'state',
            'type',
            'expires',
            'block_reason_label',
            'ip',
            'ips',
            'os',
            'osimage',
            'rcp',
            'vnc',

            'statuses',
            'running_task'
        ];
    }

    public function rules () {
        return [
            [
                ['state'],
                'isOperable',
                'on' => [
                    'reinstall',
                    'reboot',
                    'reset',
                    'shutdown',
                    'power-off',
                    'power-on',
                    'boot-live',
                    'regen-root-password'
                ]
            ]
        ];
    }

    public function scenarios () {
        return [
            'reinstall'           => ['id', 'osimage', 'panel'],
            'boot-live'           => ['id', 'osimange'],
            'reboot'              => ['id'],
            'reset'               => ['id'],
            'shutdown'            => ['id'],
            'power-off'           => ['id'],
            'power-on'            => ['id'],
            'regen-root-password' => ['id'],
        ];
    }

    public function goodStates () {
        return ['ok', 'disabled'];
    }

    /**
     * @return bool
     */
    public function isOperable () {
        /// TODO: all is operable for admin
        if ($this->running_task || !in_array($this->state, $this->goodStates())) {
            return false;
        }

        return true;
    }

    /**
     * Returns true, if server supports VNC
     *
     * @return bool
     */
    public function isVNCSupported () {
        return $this->type != 'ovds';
    }

    public function isPwChangeSupported () {
        return $this->type == 'ovds';
    }

    public function isLiveCDSupported () {
        return $this->type != 'ovds';
    }

    /**
     * @return bool
     * @throws NotSupportedException
     */
    public function checkOperable () {
        if (!$this->isOperable()) throw new NotSupportedException(\Yii::t('app', 'Server already has a running task. Can not start new.'));

        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels () {
        return [
            'id'                  => Yii::t('app', 'ID'),
            'name'                => Yii::t('app', 'Name'),
            'seller_id'           => Yii::t('app', 'Reseller'),
            'seller'              => Yii::t('app', 'Reseller'),
            'client_id'           => Yii::t('app', 'Client'),
            'client'              => Yii::t('app', 'Client'),
            'panel'               => Yii::t('app', 'Panel'),
            'parent_tariff'       => Yii::t('app', 'Parent tariff'),
            'tariff'              => Yii::t('app', 'Tariff'),
            'tariff_note'         => Yii::t('app', 'Tariff note'),
            'discounts'           => Yii::t('app', 'Discounts'),
            'request_state'       => Yii::t('app', 'Request state'),
            'state_label'         => Yii::t('app', 'State'),
            'status_time'         => Yii::t('app', 'Last operation time'),
            'sale_time'           => Yii::t('app', 'Sale time'),
            'autorenewal'         => Yii::t('app', 'Autorenewal'),
            'state'               => Yii::t('app', 'State'),
            'expires'             => Yii::t('app', 'Expires'),
            'block_reason_label'  => Yii::t('app', 'Block reason label'),
            'request_state_label' => Yii::t('app', 'Request state label'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarioCommands () {
        return [
            'reinstall' => 'resetup'
        ];
    }
}
