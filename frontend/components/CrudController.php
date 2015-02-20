<?php

namespace frontend\components;

use frontend\components\Controller;
use frontend\components\hiresource\HiResException;
use frontend\components\Re;
use frontend\models\Ref;
use yii\helpers\ArrayHelper;
use Yii;


class CrudController extends Controller {

    protected $class    = 'Default';
    protected $path     = '\frontend\modules\client\models';
    protected $tpl      = [];

    protected function objectGetParameters ($ref) {
        return Ref::find()->where(['gtype' => $ref . "," . strtolower($this->class)])->getList();
    }

    protected function objectGetBlockReason () {
        return Ref::find()->where(['gtype' => 'type,block'])->getList();
    }

    protected function actionGetClassValues ($class = "", $call_class = "") {
        $call_class = $call_class ? : "{$this->path}\\{$this->class}";
        return $call_class::find()->where(['class' => $class])->getList();
    }

    public function actionIndex ($tpl = null) {
        // Fetch nessessary data from API
        $class = "{$this->path}\\{$this->class}Search";
        $searchModel = new $class();
        if (!Yii::$app->request->queryParams['clear']) {
            $queryParams = ArrayHelper::merge(\Yii::$app->getSession()->get("{$class}[query]") ? : [], Yii::$app->request->queryParams ? : []);
        } else {
            $queryParams = [];
        }
        \Yii::$app->getSession()->set("{$class}[query]", $queryParams);
        $dataProvider = $searchModel->search($queryParams);
        $tpl = $tpl ? : \Yii::$app->getSession()->get('client[tpl]');
        $tpl = $this->tpl[$tpl] ? : (empty($this->tpl) ? '' : array_shift($this->tpl));
        \Yii::$app->getSession()->set("{$class}[tpl]", $tpl);
        return $this->render('index', [
            'dataProvider'  => $dataProvider,
            'searchModel'   => $searchModel,
            'tpl'           => $tpl,
        ]);
    }

    public function actionView ($id) {
        return $this->render('view', ['model' => $this->findModel($id, $params),]);
    }

    public function actionUpdate ($id) {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete ($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel ($id, $params = []) {
        $class = "{$this->path}\\{$this->class}";
        if (($model = $class::findOne(ArrayHelper::merge($params, [ 'id'=>$id ]))) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
