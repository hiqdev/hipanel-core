<?php

namespace frontend\modules\hosting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * GallerySearch represents the model behind the search form about `app\models\Gallery`.
 */
class AccountSearch extends \frontend\modules\hosting\models\Account
{
    public function attributes () {
        return array_merge(parent::attributes(), [
            'ids',
            'device_like',
            'login_like',
            'with_request',
            'with_mail_settings',
            'with_counters',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules () {
        return [
            [
                [
                    'ids',
                    'client',
                    'client_id',
                    'device',
                    'device_id',
                    'login',
                    'login_like',
                    'state',
                    'type',
                    'with_request',
                    'with_mail_settings',
                    'with_counters',
                ],
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
        $query = Account::find();

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
            'login'        => $this->login,
            'login_like'   => $this->login_like,
            'devices'      => $this->device,
            'device_like'  => $this->device_like,
            'device_ids'   => $this->device_id,
            'types'        => $this->type,
            'states'       => $this->state,
            'with_request' => true,
        ]);

        return $dataProvider;
    }
}
