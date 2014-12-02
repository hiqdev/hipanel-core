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

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Article::findOne(['id'=>$id,'with_data'=>1])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
