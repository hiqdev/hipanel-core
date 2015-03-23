<?php

namespace frontend\modules\client\controllers;

class ClientController extends \frontend\components\CrudController {

    public function actionList ($format = 'json') {
        return $this->actionSearch();
    }

    private function actionUserList ($input) {
        $out = ['more' => true];
        $class = "{$this->path}\\{$this->class}";
        if (!is_null($input['search'])) {
            $data = $class::find()->where($input['search'])->getList();
            $res = [];
            foreach ($data as $key => $item) {
                $res[] = ['id' => $key, 'text' => $item];
            }
            $out['results'] = $res;
        } elseif ($input['id'] != 0) {
            $out['results'] = [
                'id' => $input['id'],
                'text' => $class::find()->where([
                    'id' => $input['id'],
                ])->one()->login
            ];
        } else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
        }
        return $this->renderJson($out);
    }

    public function actionClientAllList ($search = null, $id = null, $format = 'json') {
        $search = $search === null ? null : ['client_like' => $search];
        return $this->actionUserList(compact('search','id','format'));
    }

    public function actionClientList ($search = null, $id = null, $format = 'json') {
        $search = $search === null ? null : ['client_like' => $search, 'type' => 'client'];
        return $this->actionUserList(compact('search','id','format'));
    }

    public function actionManagerList ($search = null, $id = null, $format = 'json') {
        $search = $search === null ? null : ['client_like' => $search, 'type' => 'manager' ];
        return $this->actionUserList(compact('search','id','format'));
    }

    public function actionAdminList ($search = null, $id = null, $format = 'json') {
        $search = $search === null ? null : ['client_like' => $search, 'type' => 'admin' ];
        return $this->actionUserList(compact('search','id','format'));
    }

    public function actionSellerList ($search = null, $id = null, $format = 'json') {
        $search = $search === null ? null : ['client_like' => $search, 'type' => 'reseller' ];
        return $this->actionUserList(compact('search','id','format'));
    }

    public function actionCanManageList ($search = null, $id = null, $format = 'json') {
        $search = $search === null ? null : ['client_like' => $search, 'manager_only' => 'true' ];
        return $this->actionUserList(compact('search','id','format'));
    }

    private function actionPrepareRender ($params = [], $addFuncs) {
        $class = "{$this->path}\\{$this->class}Search";
        $searchModel = new $class();
        $dataProvider = $searchModel->search( $params );
        foreach ($addFuncs as $func => $param) {
            $additional[$func] = $this->{$func}($param);
        }
        return [
                'dataProvider'  => $dataProvider,
                'searchModel'   => $searchModel,
                'additional'    => $additional,
        ];
    }

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
