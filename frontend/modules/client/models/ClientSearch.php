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

    public function rules()
    {
        return [
            [['id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = Client::find()->where(['with_contact'=>1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->andFilterWhere(['like', 'client_like', $this->client_like]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ids' => $this->id,
        ]);
        // $query->andFilterWhere(['like', 'subject', $this->title]);

        return $dataProvider;
    }
}
