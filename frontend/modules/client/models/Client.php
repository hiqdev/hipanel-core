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
            'tariff_id',
            'type_id','state_id',
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
        ];
    }

    public function rules()
    {
        return [
            [[ 'id', 'credit', ], 'integer', 'on' => 'setcredit' ],
            [[ 'id', 'type', 'comment', ], 'safe', 'on' => 'setblock' ],
            [[ 'id', 'language', ], 'safe', 'on' => 'setlanguage' ],
            [[ 'id', 'seller_id',], 'integer', 'on' => 'setseller' ],
            [[ 'type' ], 'safe' ],
            [[ 'client', 'seller' ], 'safe' ],
            [[ 'id','seller_id' ], 'integer'],
        ];
    }

}
