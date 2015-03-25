<?php
namespace frontend\modules\hosting\models;

use Yii;

class Request extends \frontend\components\Model
{

    use \frontend\components\ModelTrait;

    /** @inheritdoc */
    public function rules () {
        return [
            [['id'],                                'integer'],
            [['ip','objects_count', 'tags'],        'safe'],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels () {
        return [
            'id'                    => Yii::t('app', 'ID'),
            'state'                 => Yii::t('app', 'Status'),
            'hdomain'               => Yii::t('app', 'Domain Name'),
            'client_id'             => Yii::t('app', 'Client ID'),
            'client'                => Yii::t('app', 'Client'),
            'client_name'           => Yii::t('app', 'Client Name'),
            'seller_id'             => Yii::t('app', 'Seller ID'),
            'seller'                => Yii::t('app', 'Seller'),
            'seller_name'           => Yii::t('app', 'Seller Name'),
            'aliase'                => Yii::t('app', 'Aliase'),
            'account'               => Yii::t('app', 'Account'),
        ];
    }
}
