<?php

namespace frontend\components;

use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

trait SearchModelTrait
{
    public function attributes () {
        return array_merge(parent::attributes(),$this->search_attributes());
    }

    protected function search_attributes () {
        $attributes = [];
        foreach (parent::attributes() as $k) {
            $attributes[] = $k.'_in';
            $attributes[] = $k.'_like';
        };
        return $attributes;
    }

    public function rules () {
        $rules = parent::rules();
        $rules[] = [$this->search_attributes(),'safe'];
        return $rules;
    }

    public function scenarios () {
        // bypass scenarios() implementation in the parent class
        return \yii\base\Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search ($params) {
        $class = get_parent_class();
        $query = $class::find();
        $dataProvider = new ActiveDataProvider(compact('query'));

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        foreach (parent::attributes() as $k) {
            foreach (['eq','in','like'] as $cmp) {
                $name = $k.($cmp=='eq' ? '' : '_'.$cmp);
                $v = ArrayHelper::getValue($this,$name);
                if (is_null($v)) continue;
                $vals[$name] = $v;
                $query->andFilterWhere([$cmp,$k,$v]);
            };
        };

        return $dataProvider;
    }
}
