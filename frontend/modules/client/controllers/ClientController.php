<?php

namespace frontend\modules\client\controllers;

class ClientController extends \frontend\components\CrudController {


    public function actionSetCredit ($id = null, $ids = []) {
        return $this->performRequest([
            'id'        => $id,
            'ids'       => $ids,
            'action'    => 'SetCredit',
            'required'  => [ 'credit', 'id' ],
            'page'      => 'set-credit',
            'scenario'  => 'setcredit',
        ]);
    }

    /// TODO: implement
    public function actionSetLanguage ($id = null, $ids = []) {
        return $this->performRequest([
            'id'        => $id,
            'ids'       => $ids,
            'action'    => 'SetLanguage',
            'required'  => [ 'id', 'language' ],
            'page'      => 'language',
            'scenario'  => 'setlanguage',
        ]);
    }

    /// TODO: implement
    public function actionSetTariffs () {
        $this->checkException ($id, $ids, Yii::$app->request->post());
        return $this->renderPartial('set-tariffs', ['model' => $this->findModel($id)]);
    }

    /// TODO: implement
    public function actionChangeType () {
        $this->checkException ($id, $ids, Yii::$app->request->post());
        return $this->renderPartial('change-type', ['model' => $this->findModel($id)]);
    }

    /// TODO: implement
    public function actionChangeSeller () {
        $this->checkException ($id, $ids, Yii::$app->request->post());
        return $this->renderPartial('change-seller', ['model' => $this->findModel($id)]);
    }

    /// TODO: implement
    public function actionChangeLogin () {
        $this->checkException ($id, $ids, Yii::$app->request->post());
        return $this->renderPartial('change-login', ['model' => $this->findModel($id)]);
    }

    /// TODO: implement
    public function actionCheckLogin ($login) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $out;
    }

    public function actionSetSeller ($id = null, $ids = []) {
        return $this->performRequest([
            'id'        => $id,
            'ids'       => $ids,
            'action'    => "setSeller",
            'required'  => [ 'id', 'seller_id' ],
            'page'      => 'set-seller',
            'scenario'  => 'setseller',
            'add'       => ['actionUserList' => ['search' => ['type' => 'reseller', 'limit' => 'ALL'], 'format' => '']],
        ]);
    }

    private function actionDoBlock ($id = null, $ids = [], $action = 'enable') {
        return $this->performRequest([
            'id'        => $id,
            'ids'       => $ids,
            'action'    => ucfirst($action) . "Block",
            'subaction' => $action,
            'required'  => [ 'type', 'comment', 'id' ],
            'page'      => 'block',
            'scenario'  => 'setblock',
        ]);
   }

    public function actionEnableBlock ($id = null, $ids = []) {
        return $this->actionDoBlock($id, $ids, 'enable');
    }

    public function actionDisableBlock ($id = null, $ids = []) {
        return $this->actionDoBlock($id, $ids, 'disable');
    }

    private function actionDoPincode ($id = null, $ids = [], $action = 'enable') {

    }

    public function actionEnablePincode ($id = null, $ids = []) {
        return $this->actionDoPincode($id, $ids, 'enable');
    }

    public function actionDisablePincode ($id = null, $ids = []) {
        return $this->actionDoPincode($id, $ids, 'disable');
    }

    public function actionSetTmpPwd () {
    }

}
