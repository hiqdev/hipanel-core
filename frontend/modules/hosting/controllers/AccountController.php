<?php

namespace frontend\modules\hosting\controllers;

use frontend\modules\hosting\models\Account;
use frontend\modules\hosting\models\AccountSearch;
use frontend\controllers\HipanelController;
use frontend\models\Ref;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class AccountController extends HipanelController
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
                    'create' => ['post'],
                    'update' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex () {
        $searchModel  = new AccountSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'states'       => $this->getStates(),
            'types'        => $this->getTypes(),
        ]);
    }

    public function actionView ($id) {
        $model = $this->findModel($id);

        return $this->render('view', compact('model', 'osimages', 'osimageslivecd', 'grouped_osimages', 'panels'));
    }

    /**
     * @param int $id
     *
     * @return \frontend\modules\server\models\Server|null
     * @throws NotFoundHttpException
     */
    protected function findModel ($id) {
        if (($model = Account::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getStates () {
        return Ref::getList('state,account');
    }

    protected function getTypes () {
        return Ref::getList('type,account');
    }

}
