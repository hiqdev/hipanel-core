<?php

namespace frontend\modules\client\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * GallerySearch represents the model behind the search form about `app\models\Gallery`.
 */
class ClientSearch extends \frontend\modules\client\models\Client
{

    public function attributes () {
        return array_merge(parent::attributes(), [
            'login_like'
        ]);
    }

    public function rules () {
        return [
            [['id', 'seller_id'], 'integer'],
            [['type', 'state'], 'string'],
            [['create_time'], 'date'],
            [['login_like'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios () {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search ($params = []) {
        $query        = Client::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!($this->load($params) && $this->validate())) {
            $query->andFilterWhere([
                'ids' => $params['ids'],
            ]);

            return $dataProvider;
        }
        $query->andFilterWhere([
            'ids'          => $this->id ?: $this->ids,
            'client_like'  => $this->client_like,
            'seller_ids'   => $this->seller_id ?: $this->seller_ids,
            'type'         => $this->type ?: $this->types,
            'state'        => $this->state ?: $this->states,
            'with_contact' => true,

        ]);
        $query->andFilterWhere(['like', 'client_like', $this->client_like]);

        return $dataProvider;
    }
}
