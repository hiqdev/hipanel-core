<?php
namespace frontend\models;
class Ref extends \frontend\components\hiresource\ActiveRecord {

    public static function getList($name, $translate=true)
    {
        $func = ($translate) ? function ($v) { return \frontend\components\Re::l($v->gl_value); } : function ($v) { return $v->gl_value; } ;
        return \yii\helpers\ArrayHelper::map(self::find()->where(['gtype' => $name])->getList(false),
            'gl_key', $func);
    }

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
