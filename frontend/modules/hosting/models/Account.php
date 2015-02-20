<?php
namespace frontend\modules\hosting\models;

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
            'mail_settings'
        ];
    }

    public function rules () {
        return [
            [['login'], 'required']
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
        ];
    }
}
