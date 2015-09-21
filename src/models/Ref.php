<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\models;

use hipanel\base\Re;
use Yii;
use yii\helpers\ArrayHelper;

class Ref extends \hiqdev\hiart\ActiveRecord
{
    public $gl_key;
    public $gl_value;

    public static function getList($name, $options = [], $translate=true)
    {
        $func = $translate ? function ($v) { return Yii::t('app', $v->gl_value); } : function ($v) { return $v->gl_value; } ;
        #$func = function ($v) { return $v->gl_value; };
        return ArrayHelper::map(self::find()->where(array_merge(['gtype' => $name], $options))->getList(false), 'gl_key', $func);
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
