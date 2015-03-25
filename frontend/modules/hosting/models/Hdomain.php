<?php
namespace frontend\modules\hosting\models;

use Yii;

class Hdomain extends \frontend\components\Model
{

    use \frontend\components\ModelTrait;

    /** @inheritdoc */
    public function rules () {
        return [
            [['id', 'server_id', 'client_id', 'seller_id', 'account_id', 'hdomain_id', 'state_id','type_id'],   'integer'],
            [['server', 'client', 'seller', 'account', 'hdomain', 'state', 'type', 'ip','aliase'],              'safe'],
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
