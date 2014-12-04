<?php
namespace frontend\models;
class Ref extends \frontend\components\hiresource\ActiveRecord {

    public function attributes()
    {
        // path mapping for '_id' is setup to field 'id'
        return [
            'id',
            'value',
            'name',
            'label',
            'no',
        ];
    }
}
