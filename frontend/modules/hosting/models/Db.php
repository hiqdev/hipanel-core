<?php
namespace frontend\modules\hosting\models;

use frontend\components\Model;
use frontend\components\ModelTrait;
use frontend\modules\hosting\validators\DbNameValidator;
use Yii;

class Db extends Model
{
    use ModelTrait;

    /** @inheritdoc */
    public function rules () {
        return [
            [['id', 'account_id', 'client_id', 'seller_id', 'service_id', 'device_id', 'server_id'], 'integer'],
            [['name', 'account', 'client', 'seller', 'service', 'device', 'server'], 'safe'],
            [['service_ip', 'description'], 'safe'],
            [['type', 'state', 'backuping_type', 'type_label', 'state_label', 'backuping_type_label'], 'safe'],
            /// Create
            [['server', 'account', 'service_id', 'name', 'password'], 'required', 'on' => 'create'],
            [['name'], DbNameValidator::className(), 'on' => 'create'],
            [
                ['password'],
                'match',
                'pattern' => '/^[\x20-\x7f]+$/',
                'message' => \Yii::t('app', '{attribute} should not contain non-latin characters'),
                'on'      => 'create'
            ],
        ];
    }

    public function scenarios () {
        return [
            'create'          => ['server', 'account', 'service_id', 'name', 'password', 'description'],
            'truncate'        => ['id'],
            'set-description' => ['id', 'description'],
            'set-password'    => ['id', 'password'],
            'delete'          => ['id'],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels () {
        return $this->margeAttributeLabels([
            'name'                 => Yii::t('app', 'DB name'),
            'service_ip'           => Yii::t('app', 'Service IP'),
            'service_id'           => Yii::t('app', 'Service'),
            'backuping_type'       => Yii::t('app', 'Type of backuping'),
            'backuping_type_label' => Yii::t('app', 'Backuping type label'),
        ]);
    }
}
