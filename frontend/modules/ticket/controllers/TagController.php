<?php

namespace app\modules\ticket\controllers;

use Yii;
use \app\modules\ticket\models\Tag;
use \app\modules\ticket\models\TagSearch;

class TagController extends \yii\web\Controller
{
    public function actionIndex() {
        $searchModel = new TagSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'=>$searchModel,
        ]);
    }

    public function actionCreate()
    {
        // $model = Himodels::ticketModel();
        $model = new Tag();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
}