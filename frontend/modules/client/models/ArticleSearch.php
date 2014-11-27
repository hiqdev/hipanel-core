<?php

namespace app\modules\client\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * GallerySearch represents the model behind the search form about `app\models\Gallery`.
 */
class ArticleSearch extends \app\modules\client\models\Article
{

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['article_name','post_date'],'safe'],
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
            'allModels' => \frontend\components\Http::get('articlesSearch', ['limit'=>'1000','show_unpublished'=>'1']),

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