<?php

namespace frontend\controllers;

use frontend\components\Controller;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * HiPanel controller
 */
class HipanelController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

}
