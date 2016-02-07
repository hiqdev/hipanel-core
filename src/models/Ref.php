<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\models;

use Yii;
use yii\helpers\ArrayHelper;

class Ref extends \hiqdev\hiart\ActiveRecord
{
    public $gl_key;
    public $gl_value;

    public static function getList($name, $options = [], $translate = true)
    {
        return Yii::$app->get('cache')->getTimeCached(3600, [$name, $options, $translate], function ($name, $options, $translate) {
            $func = $translate ? function ($v) { return Yii::t('app', $v->gl_value); } : function ($v) { return $v->gl_value; };
            return ArrayHelper::map(self::find()->where(array_merge(['gtype' => $name], $options))->limit('ALL')->getList(false), 'gl_key', $func);
        });
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
