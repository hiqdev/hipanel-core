<?php
namespace app\modules\ticket\models;

use Yii;

class Tag extends \yii\db\ActiveRecord
{
    public $client_id;
    public $client;
    public $name;
    public $type;
    public $type_level;
    public $descr;
    public $is_public;
    public $state_label;
    public $state;
    public $fullname;
    public $color;
    public $bgcolor;

    public function rules()
    {
        return [
            [[
                'client_id',
                'client',
                'name',
                'type',
                'type_level',
                'descr',
                'is_public',
                'state_label',
                'state',
                'fullname',
                'color',
                'bgcolor',
            ],'safe'],
        ];
    }

}