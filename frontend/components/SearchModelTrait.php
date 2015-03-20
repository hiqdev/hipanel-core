<?php

namespace frontend\components;

use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Model;

trait SearchModelTrait
{
    public $additional;
    
    public function attributes () {
        return array_merge(parent::attributes(),$this->search_attributes());
    }

    protected function search_attributes () {
        $res = [];
        foreach (parent::attributes() as $k) {
            $res[] = $k.'_in';
            $res[] = $k.'_like';
        };
        return $res;
    }

    public function rules () {
        $rules = parent::rules();
        $rules[] = [$this->search_attributes(),'safe'];
        return $rules;
    }

    public function scenarios () {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
