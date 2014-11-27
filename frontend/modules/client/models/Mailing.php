<?php
namespace app\modules\client\models;

use Yii;

class Mailing extends \yii\db\ActiveRecord
{
    public $from;
    public $subject;
    public $message;
    public $types;

    public function rules()
    {
        return [
            [['from','subject','message','types'],'required'],
        ];
    }

    public function attributeLabels ()
    {
        return [
            'from' => 'From',
            'subject' => 'Subject',
            'message' => 'Message',
            'types' => 'Type',
        ];
    }
}