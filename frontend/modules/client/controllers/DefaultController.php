<?php

namespace app\modules\client\controllers;

use frontend\components\hiresource\HiResException;
use yii\helpers\ArrayHelper;

use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex($tpl='_tariff')
    {
        // Fetch clits data from API

        return $this->render('index', [
            'dataProvider'  => [],
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

    protected function findModel ($id, $class, $params = []) {
        $class = "\app\modules\client\models\\$class";
        if (($model = $class::findOne(ArrayHelper::merge($params, [ 'id'=>$id ]))) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
