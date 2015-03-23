<?php

namespace frontend\modules\finance\models;

use Yii;

class Tariff extends \frontend\components\hiresource\ActiveRecord
{
    /** @inheritdoc */
    public function attributes () {
        return [
            'id',
            'seller_id','client_id',
            'seller','client',
            'name',
            'note','included_in',
            'type','state',
            'label','descr','type_label',
            'used',
        ];
    }

    /** @inheritdoc */
    public function rules () {
        return [
            [['id'],                                    'safe'],
            [['seller_id','client_id'],                 'safe'],
            [['seller','client'],                       'safe'],
            [['name'],                                  'safe'],
            [['note','included_in'],                    'safe'],
            [['type','state'],                          'safe'],
            [['label','descr','type_label'],            'safe'],
            [['used'],                                  'safe'],
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
            'type'                  => Yii::t('app', 'Type'),
            'type_label'            => Yii::t('app', 'Type'),
            'state'                 => Yii::t('app', 'State'),
            'quantity'              => Yii::t('app', 'Quantity'),
            'time'                  => Yii::t('app', 'Time'),
            'label'                 => Yii::t('app', 'Label'),
            'descr'                 => Yii::t('app', 'Description'),
        ];
    }
}
