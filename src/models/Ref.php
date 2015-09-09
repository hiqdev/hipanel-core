<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\models;

use hipanel\base\Re;

class Ref extends \hiqdev\hiart\ActiveRecord
{

    public static function getList($name, $options = [], $translate=true)
    {
        $func = ($translate) ? function ($v) { return Re::l($v->gl_value); } : function ($v) { return $v->gl_value; } ;
        return \yii\helpers\ArrayHelper::map(self::find()->where(array_merge(['gtype' => $name], $options))->getList(false),
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
