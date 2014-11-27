<?php

namespace app\modules\ticket\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * GallerySearch represents the model behind the search form about `app\models\Gallery`.
 */
class TicketSearch extends \app\modules\ticket\models\Ticket
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['subject','create_time'], 'safe'],
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
        // $query = Ticket::find();

//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//        ]);
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => \frontend\components\Http::get('ticketsSearch', ['limit'=>'1000']),

        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

//        $query->andFilterWhere([
//            'id' => $this->id,
//            'create_time' => $this->create_time,
//        ]);
//        $query->andFilterWhere(['like', 'subject', $this->title]);

        return $dataProvider;
    }
}