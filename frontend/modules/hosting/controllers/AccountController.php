<?php

namespace frontend\modules\hosting\controllers;

use yii\web\NotFoundHttpException;
use Yii;
use \frontend\modules\hosting\models\Account;

class AccountController extends \frontend\components\CrudController
{
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

}
