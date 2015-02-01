<?php

namespace app\modules\client\controllers;
use frontend\components\hiresource\HiResException;
use frontend\components\Re;
use frontend\components\Ref;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use Yii;


class DefaultController extends Controller {

    protected $class    = 'Default';
    protected $path     = '\app\modules\client\models';
    protected $tpl      = [];

    protected function objectGetParameters ($ref) {
        return ArrayHelper::map(Ref::find()->where(['gtype' => $ref . "," . strtolower($this->class)])->getList(), 'gl_key', function ($o) {
            return Re::l($o->gl_value);
        });
    }

    protected function objectGetStates () {
        return ArrayHelper::map(Ref::find()->where(['gtype' => "state," . strtolower($this->class)])->getList(), 'gl_key', function ($o) {
            return Re::l($o->gl_value);
        });
    }

    protected function objectGetBlockReason () {
        return ArrayHelper::map(Ref::find()->where(['gtype' => 'type,block'])->getList(), 'gl_key', function ($o) {
            return Re::l($o->gl_value);
        });
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
