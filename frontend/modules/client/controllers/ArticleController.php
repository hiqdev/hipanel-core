<?php

namespace app\modules\client\controllers;

use yii\web\Controller;
use Yii;
use \app\modules\client\models\Article;
use \app\modules\client\models\ArticleSearch;

class ArticleController extends Controller
{
    public function actionIndex($tpl='_tariff')
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'=>$searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new Article;

        return $this->render('create', [
            'model' => $model,
        ]);
    }
}
