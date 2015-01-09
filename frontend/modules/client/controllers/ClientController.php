<?php

namespace app\modules\client\controllers;

use yii\web\Controller;
use Yii;
use \app\modules\client\models\Client;
use \app\modules\client\models\ClientSearch;

class ClientController extends DefaultController
{
    public function actionIndex($tpl='_tariff')
    {
        // Fetch clients data from API
        $data = \frontend\components\Http::get('clientsSearch', ['limit'=>'ALL']);
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
