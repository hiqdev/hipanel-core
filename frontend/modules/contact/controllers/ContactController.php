<?php

namespace frontend\modules\contact\controllers;

use frontend\components\CrudController;
use yii\base\Model;
use frontend\modules\client\models\ClientSearch;
use frontend\modules\client\models\Client;
use frontend\components\hiresource\HiResException;
use yii\helpers\ArrayHelper;
use Yii;
use yii\web\Response;
use yii\web\NotFoundHttpException;

class ContactController extends CrudController {

    protected $class    = 'Contact';
    protected $path     = 'frontend\modules\contact\models';
    protected $tpl      = [];

    public function actionCopy () {
    }
}
