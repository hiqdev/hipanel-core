<?php

namespace app\modules\client\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex($tpl='_tariff')
    {
        // Fetch clits data from API
        $data = \frontend\components\Http::get('clientsSearch', ['limit'=>'1000']);
        $provider = new \yii\data\ArrayDataProvider([
            'allModels' => $data,
            'sort' => [
                'attributes' => ['name'],
            ],
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        return $this->render('index', [
            'dataProvider'=>$provider,
            'tpl'=>$tpl
        ]);
    }

    public function actionView($id)
    {
        $data = \frontend\components\Http::get('clientGetInfo', ['id'=>$id]);
        $dataProvider =  new \yii\data\ArrayDataProvider([
            'allModels' => $data,
        ]);
        return $this->render('view', [
            'data'=>$data
        ]);
    }
}
