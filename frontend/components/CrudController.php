<?php

namespace frontend\components;

use frontend\components\hiresource\ActiveRecord;
use frontend\components\hiresource\HiResException;
use frontend\components\hiresource\Collection;
use frontend\models\Ref;
use frontend\components\helpers\ArrayHelper;
use Yii;


class CrudController extends Controller
{
    protected $class = 'Default';
    protected $path  = '\frontend\modules\client\models';
    protected $tpl   = [];

    /**
     * Performs operations for some editable field
     *
     * @param $config - config, which will be passed to [[Collection]]
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see Collection
     */
    public function performEditable ($config) {
        $config = ArrayHelper::merge([
            'model'         => static::newModel(),
            'errorMessage'  => Yii::t('app', 'Unknown error')
        ], $config);

        $errorMessage = ArrayHelper::remove($config, 'errorMessage');

        return $this->renderJson([
            'message' => (new Collection($config))->load()->save() ? '' : $errorMessage
        ]);
    }

    /**
     * @param array $add - additional data to be passed to render
     */
    public function actionView ($id,$add=[]) {
        $model = $this->findModel($id);
        return $this->render('view', ArrayHelper::merge(compact('model'),$add));
    }

    /**
     * @param array $add - additional data to be passed to render
     */
    public function actionIndex ($add=[]) {
        $searchModel  = Yii::CreateObject(static::searchModel());
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', ArrayHelper::merge(compact('searchModel','dataProvider'),$add));
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

    /**
     * Deletes an existing object
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete ($id) {
        $this->newModel(compact('id'))->delete();
        return $this->redirect(['index']);
    }

    static public function getRefs ($gtype) {
        return Ref::find()->where(compact('gtype'))->getList();
    }

    static public function getClassRefs     ($type) { return static::getRefs($type.','.static::idName('_')); }
    static public function getBlockReasons  () { return static::getRefs('type,block'); }
    static public function getPriorities    () { return static::getRefs('type,priority'); }

    protected function actionGetClassValues ($class = "", $values, $path = "", $id = "") {
        $id         = $id ?: Yii::$app->user->id;
        $call_class = $path ? "{$path}\\" . ucfirst($class) : "{$this->path}\\{$this->class}";

        return $call_class::Perform("GetClassValues", ["class" => "{$class},{$values}"], false);
    }

    public function BR_actionIndex ($tpl = null) {
        // Fetch nessessary data from API
        $class       = "{$this->path}\\{$this->class}Search";
        $searchModel = new $class();
        if (!Yii::$app->request->queryParams['clear']) {
            $queryParams = ArrayHelper::merge(\Yii::$app->getSession()
                                                        ->get("{$class}[query]") ?: [], Yii::$app->request->queryParams ?: []);
        } else {
            $queryParams = [];
        }
        \Yii::$app->getSession()->set("{$class}[query]", $queryParams);
        $dataProvider = $searchModel->search($queryParams);
        $tpl          = $tpl ?: \Yii::$app->getSession()->get('client[tpl]');
        $tpl          = $this->tpl[$tpl] ?: (empty($this->tpl) ? '' : array_shift($this->tpl));
        \Yii::$app->getSession()->set("{$class}[tpl]", $tpl);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
            'tpl'          => $tpl,
        ]);
    }

    protected function prepareDataToUpdate ($action, $params, $scenario) {
        $data  = [];
        $class = $this->class;
        foreach ($params['ids'] as $id => $values) {
            if (is_array($values)) {
                foreach ($values as $key => $value) {
                    $data[$id][$key] = $value;
                }
            }
            $models[$id]           = $class::findOne(compact('id'));
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
            if (\yii\helpers\BaseArrayHelper::keyExists($field, $array)) return true; else {
                foreach ($array as $key => $value) {
                    if (is_array($value)) $res = $res ?: $this->recursiveSearch($value, $field);
                }

                return $res;
            }
        }

        return false;
    }

    protected function checkException ($id, $ids, $post) {
        if (!$id && !$ids && !$post['id'] && !$post['ids']) throw new NotFoundHttpException('The requested page does not exist.');

        return true;
    }

    protected function renderingPage ($page, $queryParams, $action = [], $addFunc = []) {
        return Yii::$app->request->isAjax ? $this->renderPartial($page, ArrayHelper::merge($this->actionPrepareRender($queryParams, $addFunc), $action)) : $this->render($page, ArrayHelper::merge($this->actionPrepareRender($queryParams, $addFunc), $action));
    }

    protected function performRequest ($row) {
        $this->checkException($row['id'], $row['ids'], Yii::$app->request->post());
        $id  = $row['id'] ?: Yii::$app->request->post('id');
        $ids = $row['ids'] ?: Yii::$app->request->post('ids');
        if (Yii::$app->request->isAjax && !$id) {
            if ($this->prepareDataToUpdate($row['action'], Yii::$app->request->post(), $row['scenario'])) {
                return ['state' => 'success', 'message' => \Yii::t('app', $row['action'])];
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
                \Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'Something wrong'));
            }

            return $this->redirect(Yii::$app->request->referrer);
        }
        $ids         = $ids ?: ['id' => $id];
        $queryParams = ['ids' => implode(',', $ids)];

        return $this->renderingPage($row['page'], $queryParams, ['action' => $row['subaction']], $row['add']);
    }
}
