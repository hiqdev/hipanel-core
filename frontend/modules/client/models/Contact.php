<?php

namespace frontend\modules\client\models;

use Yii;

class Contact extends \frontend\components\hiresource\ActiveRecord
{
    /**
     * @return array the list of attributes for this record
     */
    public function attributes()
    {
        return [
            'id',
            'client_id', 'seller_id',
            'client', 'seller',
            'type', 'state',
            'create_time', 'update_time',
            'email', 'name', 'type_label'
        ];
    }

    public function rules()
    {
        return [
            [[ 'id', 'credit', ], 'integer', 'on' => 'setcredit' ],
            [[ 'id', 'type', 'comment', ], 'safe', 'on' => 'setblock' ],
            [[ 'id', 'language', ], 'safe', 'on' => 'setlanguage' ],
            [[ 'id', 'seller_id',], 'integer', 'on' => 'setseller' ],
        ];
    }

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
            'create_time'           => Yii::t('app', 'Create time'),
            'update_time'           => Yii::t('app', 'Update time'),
            'label'                 => Yii::t('app', 'Label'),
            'descr'                 => Yii::t('app', 'Description'),
        ];
    }

}
