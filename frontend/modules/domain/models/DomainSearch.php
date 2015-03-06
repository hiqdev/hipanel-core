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
            'domains',
            'domain_like',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules () {
        return [
            [
                ['ids', 'client', 'client_id', 'seller', 'seller_id', 'domain', 'domain_like', 'state', 'note'],
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
            'seller'       => $this->seller,
            'seller_ids'   => $this->seller_id,
            'domains'      => $this->domains,
            'domain_like'  => $this->domain_like ?: $this->domain,
            'state'        => $this->state,
            'note'         => $this->note,
        ]);

        return $dataProvider;
    }
}
