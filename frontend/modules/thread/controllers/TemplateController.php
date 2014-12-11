<?php

namespace app\modules\ticket\controllers;

use Yii;
use \app\modules\ticket\models\Template;
use \app\modules\ticket\models\TemplateSearch;

class TemplateController extends \yii\web\Controller
{
    public function actionIndex() {
        $searchModel = new TemplateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'=>$searchModel,
        ]);
    }
}