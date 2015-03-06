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

    protected function objectGetPriority () {
        return Ref::find()->where(['gtype' => 'type, priority'])->getList();
    }

    protected function actionGetClassValues ($class = "", $values, $path = "", $id = "") {
        $id = $id ? : Yii::$app->user->id;
        $call_class = $path ? "{$path}\\" . ucfirst($class) : "{$this->path}\\{$this->class}";
        return $call_class::Perform("GetClassValues", ["class" => "{$class},{$values}"], false);
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
        $params = [];
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

    protected function prepareDataToUpdate ($action, $params, $scenario) {
        $data = [];
        $class = $this->class;
        foreach ($params['ids'] as $id => $values) {
            if (is_array($values)) {
                foreach ($values as $key => $value) $data[$id][$key] = $value;
            }
            $models[$id] = $class::findOne(compact('id'));
            $models[$id]->scenario = $scenario;
        }
        try {
            foreach ($models as $id => $model) {
                if (!$model->load($data[$id]) || !$model->validate()) {
                    unset($data[$id]);
                }
            }
            if (!empty($data)) {
                $class::perform($action, $data, true);
            } else {
                return false;
            }
        } catch (HiResException $e) {
            return false;
        }
        return true;
    }

    protected function recursiveSearch ($array, $field) {
        if (is_array($array)) {
            if (\yii\helpers\BaseArrayHelper::keyExists($field, $array)) return true;
            else {
                foreach ($array as $key => $value) {
                    if (is_array($value)) $res = $res ? : $this->recursiveSearch($value, $field);
                }
                return $res;
            }
        }
        return false;
    }

    protected function checkException ($id, $ids, $post) {
        if (!$id && !$ids &&!$post['id'] && !$post['ids']) throw new NotFoundHttpException('The requested page does not exist.');
        return true;
    }

    protected function renderingPage ($page, $queryParams, $action = [], $addFunc = []) {
        return Yii::$app->request->isAjax
            ? $this->renderPartial($page, ArrayHelper::merge($this->actionPrepareRender($queryParams, $addFunc), $action))
            : $this->render($page, ArrayHelper::merge($this->actionPrepareRender($queryParams, $addFunc), $action));
    }

    protected function performRequest ($row) {
        $this->checkException ($row['id'], $row['ids'], Yii::$app->request->post());
        $id = $row['id'] ? : Yii::$app->request->post('id');
        $ids = $row['ids'] ? : Yii::$app->request->post('ids');
        if (Yii::$app->request->isAjax && !$id) {
            if ($this->prepareDataToUpdate($row['action'] , Yii::$app->request->post(), $row['scenario'])) {
                return ['state' => 'success', 'message' => \Yii::t('app', $row['action']) ];
            } else {
                return ['state' => 'error', 'message' => \Yii::t('app', 'Something wrong')];
            }
        }
        $check = true;
        foreach ($row['required'] as $required) {
            if (!$this->recursiveSearch(Yii::$app->request->post(), $required)) {
                $check = false;
                break;
            }
        }
        if (!$id && $check) {
            if ($this->prepareDataToUpdate($row['action'], Yii::$app->request->post(), $row['scenario'])) {
                \Yii::$app->getSession()->setFlash('success', \Yii::t('app', '{0} was successful', $row['action']));
            } else {
                \Yii::$app->getSession()->setFlash('error',  \Yii::t('app', 'Something wrong'));
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        $ids = $ids ? : [ 'id' => $id ];
        $queryParams = [ 'ids' => implode(',', $ids) ];
        return $this->renderingPage($row['page'], $queryParams, ['action' => $row['subaction']], $row['add']);
    }
}
