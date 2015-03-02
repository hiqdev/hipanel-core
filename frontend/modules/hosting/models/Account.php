<?php
namespace frontend\modules\hosting\models;

use frontend\components\helpers\ArrayHelper;
use frontend\components\validators\IpAddressValidator;
use Yii;

class Account extends \frontend\components\hiresource\ActiveRecord
{
    /**
     * @return array the list of attributes for this record
     */
    public function attributes () {
        return [
            'id',
            'login',
            'password',
            'uid',
            'gid',
            'shell',
            'client_id',
            'client',
            'path',
            'home',
            'device_id',
            'device',
            'ip',
            'type',
            'type_label',
            'state',
            'state_label',
            'allowed_ips',
            'sshftp_ips',
            'objects_count',
            'request_state',
            'request_state_label',
            'mail_settings',
            'server',
            'server_id',
        ];
    }

    public function rules () {
        return [
            [
                [
                    'login',
                    'server_id',
                    'password',
                    'sshftp_ips',
                    'type',
                ],
                'safe',
                'on' => 'insert'
            ],
            [
                [
                    'login',
                    'server_id',
                    'password',
                    'sshftp_ips',
                    'type',
                ],
                'required',
                'on' => 'insert'
            ],
            [
                'password',
                'compare',
                'compareAttribute' => 'login',
                'message'          => Yii::t('app', 'Password must not be equal to login'),
                'operator'         => '!=',
                'on'               => ['insert'],
            ],
            ['login', 'match', 'pattern' => '/^[a-z][a-z0-9_]{2,31}$/', 'on' => 'insert'],
            [
                'login',
                'in',
                'range'   => ['root', 'toor'],
                'not'     => true,
                'on'      => 'insert',
                'message' => Yii::t('app', 'You can not use this login')
            ],
            [
                'sshftp_ips',
                'filter',
                'filter' => function ($value) { return ArrayHelper::csplit($value); },
                'on'     => 'insert'
            ],
            ['sshftp_ips', IpAddressValidator::className(), 'on' => ['insert', 'update'], 'exclusion' => true]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels () {
        return [
            'id'          => Yii::t('app', 'ID'),
            'login'       => Yii::t('app', 'Login'),
            'login_like'  => Yii::t('app', 'Login'),
            'shell'       => Yii::t('app', 'Shell'),
            'client'      => Yii::t('app', 'Client'),
            'path'        => Yii::t('app', 'Path'),
            'home'        => Yii::t('app', 'Home'),
            'device'      => Yii::t('app', 'Device'),
            'type_label'  => Yii::t('app', 'state'),
            'state_label' => Yii::t('app', 'state'),
            'allowed_ips' => Yii::t('app', 'Allowed IPs'),
            'sshftp_ips'  => Yii::t('app', 'SSH/FTP IPs'),
            'server_id'   => Yii::t('app', 'Server'),
        ];
    }

    public function getSshFtpIpsList () {
        return implode(', ', $this->sshftp_ips);
    }
}
