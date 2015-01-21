<?php

namespace app\modules\client\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * GallerySearch represents the model behind the search form about `app\models\Gallery`.
 */
class ClientSearch extends \app\modules\client\models\Client {

    public function rules () {
        return [
            [['id','seller_id'], 'integer'],
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
    public function search($params) {
        $query = Client::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ids'           => $this->id,
            'seller_ids'    => $this->seller_id,
            'type'          => $this->type,
            'state'         => $this->state,
            'with_contact'  => true,

        ]);
        $query->andFilterWhere([ 'like', 'client_like', $this->client_like ]);

        return $dataProvider;
    }
}
