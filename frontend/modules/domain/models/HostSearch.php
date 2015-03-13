<?php

namespace frontend\modules\domain\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * GallerySearch represents the model behind the search form about `app\models\Gallery`.
 */
class HostSearch extends \frontend\modules\domain\models\Host
{

    public $with_request;

    public function attributes () {
        return array_merge(parent::attributes(), [
            'ids',
            'domains','hosts',
            'domain_like','host_like','ip_like',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules () {
        return array_merge([
            [['ids'],                               'safe'], /// XXX should be ids
            [['domains','hosts'],                   'safe'], /// XXX should be ids
            [['domain_like','host_like','ip_like'], 'safe'], /// XXX should be label
        ], parent::rules());
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search ($params = []) {
        $query = Host::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ids'           => $this->ids ?: $this->id,
            'seller_ids'    => $this->seller_id,
            'client_ids'    => $this->client_id,
            'domain_ids'    => $this->domain_id,
            'sellers'       => $this->seller,
            'clients'       => $this->client,
            'domains'       => $this->domains,
            'hosts'         => $this->hosts,
            'domain_like'   => $this->domain_like ?: $this->domain,
            'host_like'     => $this->host_like ?: $this->host,
            'ip_like'       => $this->ip_like ?: $this->ips,
        ]);

        return $dataProvider;
    }
}
