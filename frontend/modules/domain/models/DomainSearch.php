<?php

namespace frontend\modules\domain\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * GallerySearch represents the model behind the search form about `app\models\Gallery`.
 */
class DomainSearch extends \frontend\modules\domain\models\Domain
{

    public $with_request;

    public function attributes () {
        return array_merge(parent::attributes(), [
            'ids',
            'domain_like',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules () {
        return [
            [
                ['ids', 'client', 'client_id', 'seller', 'seller_id', 'name', 'domain_like', 'state', 'tariff_note', 'with_request'],
                'safe'
            ],
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
        $query = Domain::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ids'          => $this->ids,
            'clients'      => $this->client,
            'client_ids'   => $this->client_id,
            'name'         => $this->name,
            'domain_like'  => $this->domain_like,
            'seller'       => $this->seller,
            'seller_ids'   => $this->seller_id,
            'domains'      => $this->name,
            'states'       => $this->state,
            'tariff_note'  => $this->tariff_note,
            'with_request' => true,
        ]);

        return $dataProvider;
    }
}
