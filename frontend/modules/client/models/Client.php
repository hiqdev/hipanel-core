<?php
namespace frontend\modules\client\models;

use Yii;

class Client extends \frontend\components\hiresource\ActiveRecord
{
    /**
     * @return array the list of attributes for this record
     */
    public function attributes()
    {
        return [
            'id', 'seller_id',
            'client', 'seller',
            'type', 'state',
            'type_id', 'state_id',
            'tariff_id',
            'balance',
            'credit',
            'tariff_name',
            'create_time',
            // create/update
            'email',
            'referer',
            'language',
            'confirm_url',
            // with_contact
            'login',
            'name',
            'first_name',
            'last_name',
            // count
            'count',
            'contact',
            'comment',
            'login_like',
        ];
    }

    public function rules()
    {
        return [
            [[ 'id', 'credit', 'seller_id', 'state_id', 'type_id'], 'integer'],
            [[ 'id', 'credit', ], 'required', 'on' => 'set-credit' ],
            [[ 'id', 'type', 'comment', ], 'safe', 'on' => 'set-block' ],
            [[ 'id', 'language', ], 'safe', 'on' => 'set-language' ],
            [[ 'id', 'seller_id',], 'integer', 'on' => 'set-seller' ],
            [[ 'type' ], 'safe' ],
            [[ 'client', 'seller' ], 'safe' ],
        ];
    }

}
