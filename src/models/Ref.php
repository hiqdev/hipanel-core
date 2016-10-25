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
    use \hipanel\base\ModelTrait;

    public function rules()
    {
        return [
            [['id', 'no'], 'integer'],
            [['name', 'label'], 'safe'],
        ];
    }

    public static function getList($name, $translate = null, $options = [])
    {
        if ($translate === null) {
            $translate = 'hipanel';
        }
        $function = function ($model) use ($translate) {
            /** @var self $model */
            if ($translate !== false) {
                return Yii::t($translate, $model->label);
            }

            return $model->label;
        };
        $data = Yii::$app->get('cache')->getTimeCached(3600, [$name, $options, $translate], function ($name, $options, $translate) {
            $conditions = array_merge(['gtype' => $name], $options);
            $result = self::find()->where($conditions)->search();

            return $result;
        });
        $result = ArrayHelper::map($data, 'name', $function);

        return $result;
    }
}
