<?php

namespace frontend\components;

use frontend\components\hiresource\ActiveQuery;
use frontend\components\hiresource\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Trait SearchModelTrait
 *
 * @package frontend\components
 */
trait SearchModelTrait
{
    static $filterConditions = ['in', 'like'];

    public function attributes () {
        return array_merge(parent::attributes(), $this->searchAttributes());
    }

    protected function searchAttributes () {
        $attributes = [];
        foreach (parent::attributes() as $k) {
            foreach (static::$filterConditions as $condition) {
                $attributes[$k] = $k . "_$condition";
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
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search ($params) {
        /**
         * @var ActiveRecord $this
         * @var ActiveRecord $class
         * @var ActiveQuery $query
         */
        $class        = get_parent_class();
        $query        = $class::find();
        $dataProvider = new ActiveDataProvider(compact('query'));

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        foreach ($this as $k => $v) {
            if (empty($v)) continue;
            preg_match('/^(.*?)(_((?:.(?!_))+))?$/', $k, $matches);
            if ($matches[3] && in_array($matches[3], static::$filterConditions)) {
                $cmp = $matches[3];
            } else {
                $cmp = 'eq';
            }
            $query->andFilterWhere([$cmp, $matches[1], $v]);
        }

        return $dataProvider;
    }
}
