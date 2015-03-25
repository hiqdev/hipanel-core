<?php

namespace frontend\modules\client\models;

use Yii;

class Client extends \frontend\components\Model {

    use \frontend\components\ModelTrait;

    public function rules()
    {
        return [
            [[ 'id', 'credit', 'balance', 'seller_id', 'state_id', 'type_id', 'state_id', 'tariff_id', 'profile_id'],   'integer'],
            [[ 'login', 'seller', 'state', 'type', 'tariff', 'profile'],                                                'safe'],
            [[ 'count', 'confirm_url', 'language', 'comment' ], 'safe'],
            [[ 'create_time', 'update_time'], 'date'],
            [[ 'email'], 'email'],
            [[ 'id', 'credit', ],           'required', 'on' => 'set-credit' ],
            [[ 'id', 'type', 'comment', ],  'required', 'on' => 'set-block' ],
            [[ 'id', 'language', ],         'required', 'on' => 'set-language' ],
            [[ 'id', 'seller_id',],         'required', 'on' => 'set-seller' ],
        ];
    }

    public function attributeLabels () {
        return $this->margeAttributeLabels([]);
    }

}
