<?php

namespace app\modules\client\controllers;

use yii\web\Controller;
use Yii;


class MailingController extends Controller {
    public function actionIndex()
    {
        return $this->render('index',['model'=>new \app\modules\client\models\Mailing]);
    }
}
