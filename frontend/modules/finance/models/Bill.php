<?php
namespace frontend\modules\finance\models;

use Yii;

class Bill extends \frontend\components\hiresource\ActiveRecord
{
    /** @inheritdoc */
    public function attributes () {
        return [
            'id',
            'seller_id','client_id',
            'seller','client',
            'sum','balance',
            'quantity',
            'type','gtype','currency',
            'label','descr','object','type_label',
            'time',
            'txn',
        ];
    }

    /** @inheritdoc */
    public function rules () {
        return [
            [['id'],                                    'safe'],
            [['seller_id','client_id'],                 'safe'],
            [['seller','client'],                       'safe'],
            [['sum','balance'],                         'safe'],
            [['quantity'],                              'safe'],
            [['type','gtype','currency'],               'safe'],
            [['label','descr','object','type_label'],   'safe'],
            [['time'],                                  'safe'],
            [['txn'],                                   'safe'],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels () {
        return [
            'id'                    => Yii::t('app', 'ID'),
            'seller_id'             => Yii::t('app', 'Reseller ID'),
            'client_id'             => Yii::t('app', 'Client ID'),
            'seller'                => Yii::t('app', 'Reseller'),
            'client'                => Yii::t('app', 'Client'),
            'state'                 => Yii::t('app', 'State'),
            'quantity'              => Yii::t('app', 'Quantity'),
            'time'                  => Yii::t('app', 'Time'),
            'label'                 => Yii::t('app', 'Label'),
            'descr'                 => Yii::t('app', 'Description'),
        ];
    }
}
