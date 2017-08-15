<?php

namespace hipanel\models;

use hiqdev\hiart\ActiveRecord;

class Blocking extends ActiveRecord
{
    public function rules()
    {
        return [
            [['client_id', 'seller_id'], 'integer'],
            [['reason', 'reason_label', 'comment', 'time', 'client', 'seller'], 'string'],
        ];
    }
}


