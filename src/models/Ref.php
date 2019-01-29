<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
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
            [['name', 'oname', 'label'], 'safe'],
        ];
    }

    /**
     * Finds models by specified $name and $options parameters,
     * transforms them to key-value structure.
     * Name: attribute `name`
     * Value: attribute `label`.
     * You can change name of returning attributes `name` and `label`
     * You should define `mapOptions` in `options` array
     * and define keys `from` for Name and `to` for Value.
     *
     * @param string $name
     * @param string|false $translate
     * @param array $options
     * @return array
     */
    public static function getList($name, $translate = null, $options = [])
    {
        $mapOptions = ArrayHelper::remove($options, 'mapOptions');
        $models = static::findCached($name, $translate, $options);

        $from = ArrayHelper::remove($mapOptions, 'from', 'name');
        $to = ArrayHelper::remove($mapOptions, 'to', 'label');
        $group = ArrayHelper::remove($mapOptions, 'group', null);

        return ArrayHelper::map($models, $from, $to, $group);
    }

    public static function getListRecursively($name, $translate = null, $options = [])
    {
        return self::getList($name, $translate, array_merge($options, ['with_recursive' => true]));
    }

    public static function findCached($name, $translate = null, $options = [])
    {
        if ($translate === null) {
            $translate = 'hipanel';
        }

        $data = Yii::$app->get('cache')->getOrSet([__METHOD__, $name, $options], function () use ($name, $options) {
            $conditions = array_merge(['gtype' => $name], $options);
            $result = self::find()->where($conditions)->all();

            return $result;
        }, 3600);

        return array_map(function ($model) use ($translate) {
            /** @var self $model */
            if ($translate !== false) {
                $model->label = Yii::t($translate, $model->label);
            }

            return $model;
        }, $data);
    }
}
