<?php

namespace frontend\modules\domain\controllers;

use yii\filters\VerbFilter;
use frontend\modules\domain\models\Host;
use frontend\modules\domain\models\HostSearch;

class HostController extends \frontend\components\CrudController
{
    /**
     * All of security-aware methods are allowed only with POST requests
     *
     * @return array
     */
    public function behaviors () {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'update'               => ['post'],
                ],
            ],
        ];
    }

    static protected function mainModel     () { return Host::className(); }
    static protected function searchModel   () { return HostSearch::className(); }

    public function actionUpdate () {
        return $this->performEditable(['scenario' => 'update', 'attributes' => ['id', 'ips']]);
    }

}
