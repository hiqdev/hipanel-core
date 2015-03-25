<?php
namespace frontend\modules\hosting\models;

use Yii;

class Backup extends \frontend\components\Model
{

    use \frontend\components\ModelTrait;

    /** @inheritdoc */
    public function rules () {
        return [
            [['id', 'service_id','object_id', 'server_id','account_id', 'client_id', 'ty_id', 'state_id'],    'integer'],
            [['time'],                                                                                                      'date'],
            [['size', 'size_gb'],                                                                                           'integer'],
            [['service', 'method', 'server', 'account', 'client', 'name', 'ty', 'state'],                                   'safe'],
            [['method_label', 'type_label', 'state_label'],                                                                 'safe'],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels () {
        return $this->margeAttributeLabels([
            'id'                    => Yii::t('app', 'ID'),
            'service_id'            => Yii::t('app', 'Service ID'),
            'object_id'             => Yii::t('app', 'Object ID'),
            'server_id'             => Yii::t('app', 'Server ID'),
            'account_id'            => Yii::t('app', 'Account ID'),
            'client_id'             => Yii::t('app', 'Client ID'),
            'ty_id'                 => Yii::t('app', 'Type ID'),
            'ty'                    => Yii::t('app', 'Type'),
            'type_label'            => Yii::t('app', 'Type label'),
            'state_id'              => Yii::t('app', 'State ID'),
            'state_label'           => Yii::t('app', 'State label'),
            'size_gb'               => Yii::t('app', 'Size in GB'),
            'method_label'          => Yii::t('app', 'Method label'),
        ]);
    }
}
