<?php

namespace frontend\modules\hosting\controllers;

use frontend\modules\hosting\models\Account;
use frontend\modules\hosting\models\AccountSearch;
use frontend\controllers\HipanelController;
use frontend\models\Ref;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii;

class AccountController extends HipanelController
{
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

    public function actionCreate () {
        $model           = new Account();
        $model->scenario = 'insert';
        $model->load(Yii::$app->request->post());
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model'         => $model
        ]);
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
