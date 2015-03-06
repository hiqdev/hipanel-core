<?php

namespace frontend\modules\hosting\controllers;

use frontend\components\hiresource\Collection;
use frontend\modules\hosting\models\Account;
use frontend\modules\hosting\models\AccountSearch;
use frontend\controllers\HipanelController;
use frontend\models\Ref;
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

        return $this->render('view', compact('model'));
    }

    public function actionCreateFtp () {
        return $this->actionCreate('ftponly');
    }

    public function actionCreate ($type = 'user') {
        if (!in_array($type, ['user', 'ftponly'])) throw new NotFoundHttpException('Account type is unknown');
        $model = new Account(['scenario' => 'insert-' . $type]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'type'  => $type,
        ]);
    }

    public function actionSetPassword ($id) {
        $model           = $this->findModel($id);
        $model->scenario = 'set-password';
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            \Yii::$app->getSession()->addFlash('success', [
                'title' => $model->login,
                'text'  => \Yii::t('app', 'Password changing task has been successfully added to queue'),
            ]);

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            /// TODO: do
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * @param $id
     * @return string|yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSetAllowedIps ($id) {
        $model           = $this->findModel($id);
        $model->scenario = 'set-allowed-ips';
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            $flash = [
                'type' => 'success',
                'text' => \Yii::t('app', 'Allowed IPs changing task has been successfully added to queue')
            ];
        } else {
            $flash['type'] = 'error';
            if ($model->hasErrors()) {
                $flash['text'] = $model->getFirstError('sshftp_ips');
            } else {
                $flash['text'] = \Yii::t('app', 'An error occurred when trying to change allowed IPs');
            }
        }

        \Yii::$app->getSession()->addFlash($flash['type'], $flash['text']);

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionDelete ($id) {
        $model = $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     *
     * @return \frontend\modules\hosting\models\Account|null
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

    public function actionTest () {
        $search   = new AccountSearch();
        $accounts = $search->search(['AccountSearch' => ['login_like' => 'asdf', 'device' => 'AVDS123860']])
                           ->getModels();

        foreach ($accounts as &$account) {
            /* @var $account Account */
            $account->password = 'newtestpassword';
        }

        $collection = new Collection(['scenario' => 'set-password', 'attributes' => ['id', 'password']]);
        $collection->load($accounts)->save();

        return $this->renderJson(['ok' => 'aha']);
    }
}
