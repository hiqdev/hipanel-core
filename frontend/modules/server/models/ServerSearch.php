<?php

namespace app\modules\server\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * GallerySearch represents the model behind the search form about `app\models\Gallery`.
 */
class ServerSearch extends \app\modules\server\models\Server {

    /**
     * @inheritdoc
     */
    public function rules () {
        return [
            [['client', 'seller', 'name', 'state', 'tariff_note'], 'safe'],
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
    public function search ($params = array()) {
        $query = Server::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'clients'        => $this->client,
            'seller'         => $this->seller,
            'servers'        => $this->name,
            'states'         => $this->state,
            'tariff_note'    => $this->tariff_note,
        ]);

        return $dataProvider;
    }
}
