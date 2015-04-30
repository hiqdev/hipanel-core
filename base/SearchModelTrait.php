<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\base;

use hiqdev\hiar\ActiveQuery;
use hiqdev\hiar\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Trait SearchModelTrait
 * Basic behavior for search models.
 */
trait SearchModelTrait
{
    static $filterConditions = ['in', 'like'];

    public function attributes () {
        return $this->searchAttributes();
    }

    protected function searchAttributes () {
        $attributes = [];
        foreach (parent::attributes() as $k) {
            foreach ([''] + static::$filterConditions as $condition) {
                $attributes[] = $k . ($condition == '' ? '' : "_$condition");
            }
        };

        return $attributes;
    }

    public function rules () {
        $rules   = parent::rules();
        $rules[] = [$this->searchAttributes(), 'safe'];

        return $rules;
    }

    public function scenarios () {
        // bypass scenarios() implementation in the parent class
        return \yii\base\Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     * @param $params
     * @param array $dataProviderConfig
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function search ($params, $dataProviderConfig = []) {
        /**
         * @var ActiveRecord $this
         * @var ActiveRecord $class
         * @var ActiveQuery $query
         */
        $class        = get_parent_class();
        $query        = $class::find(); // $class::find()->orderBy($sort->orders)->all(); if $sort is Sort obj
        $dataProvider = new ActiveDataProvider(ArrayHelper::merge(compact('query'), $dataProviderConfig));

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        foreach ($this->attributes() as $attribute) {
            $value = $this->{$attribute};
            if (empty($value)) continue;
            /*
             * Extracts underscore suffix from the key.
             *
             * Examples:
             * client_id -> 0 - client_id, 1 - client, 2 - _id, 3 - id
             * server_owner_like -> 0 - server_owner_like, 1 - server_owner, 2 - _like, 3 - like
             */
            preg_match('/^(.*?)(_((?:.(?!_))+))?$/', $attribute, $matches);

            /// If the suffix is in the list of acceptable suffix filer conditions
            if ($matches[3] && in_array($matches[3], static::$filterConditions)) {
                $cmp       = $matches[3];
                $attribute = $matches[1];
            } else {
                $cmp = 'eq';
            }
            $query->andFilterWhere([$cmp, $attribute, $value]);
        }

        return $dataProvider;
    }
}
