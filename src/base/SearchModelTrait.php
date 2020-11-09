<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\base;

use hiqdev\hiart\ActiveQuery;
use hiqdev\hiart\ActiveRecord;
use hiqdev\hiart\ActiveDataProvider;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Trait SearchModelTrait
 * Basic behavior for search models.
 */
trait SearchModelTrait
{
    public static $filterConditions = ['in', 'ne', 'like', 'ilike', 'gt', 'ge', 'lt', 'le', 'ni'];

    public function attributes()
    {
        return $this->searchAttributes();
    }

    protected function searchAttributes()
    {
        static $attributes = [];

        if ($attributes === []) {
            foreach (parent::attributes() as $attribute) {
                $attributes = array_merge($attributes, $this->buildAttributeConditions($attribute));
            }
        }

        return $attributes;
    }

    /**
     * Builds all possible $attribute names using [[$filterConditions]].
     *
     * @param $attribute
     * @return array
     */
    protected function buildAttributeConditions($attribute)
    {
        $attributes = [];
        foreach (array_merge([''], static::$filterConditions) as $condition) {
            $attributes[] = $attribute . ($condition === '' ? '' : "_$condition");
        }

        return $attributes;
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [$this->searchAttributes(), 'safe'];

        return $rules;
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return \yii\base\Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied.
     * @param $params
     * @param array $dataProviderConfig
     * @throws \yii\base\InvalidConfigException
     * @return ActiveDataProvider
     */
    public function search($params, $dataProviderConfig = [])
    {
        /**
         * @var ActiveRecord
         * @var ActiveRecord $class
         * @var ActiveQuery $query
         */
        $class = get_parent_class();
        $query = $class::find(); // $class::find()->orderBy($sort->orders)->all(); if $sort is Sort

        $dataProvider = Yii::createObject(ArrayHelper::merge([
            'class' => ActiveDataProvider::class,
            'query' => $query
        ], $dataProviderConfig));

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        foreach ($this->attributes() as $attribute) {
            if (($value = $this->{$attribute}) === null) {
                continue;
            }
            /*
             * Extracts underscore suffix from the key.
             *
             * Examples:
             * client_id -> 0 - client_id, 1 - client, 2 - _id, 3 - id
             * server_owner_like -> 0 - server_owner_like, 1 - server_owner, 2 - _like, 3 - like
             */
            preg_match('/^(.*?)(_((?:.(?!_))+))?$/', $attribute, $matches);

            /// If the suffix is in the list of acceptable suffix filer conditions
            if ($matches[3] && in_array($matches[3], static::$filterConditions, true)) {
                $cmp = $matches[3];
                $attribute = $matches[1];
            } else {
                $cmp = 'eq';
            }
            $query->andFilterWhere([$cmp, $attribute, $value]);
        }

        return $dataProvider;
    }

    /**
     * Returns default labels.
     * Solves infinite recursion problem.
     * @return array
     */
    public function defaultAttributeLabels()
    {
        /// XXX parent::attributeLabels() creates infinite recursion
        $class = parent::class;

        return (new $class())->attributeLabels();
    }
}
