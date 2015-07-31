<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\base;

use hipanel\actions\PerformAction;
use hipanel\helpers\ArrayHelper as AH;
use hipanel\models\Ref;
use Yii;
use yii\helpers\Inflector;

class CrudController extends Controller
{
//    public function actions () {
//        $actions = parent::actions();
//        $model   = static::newModel();
//        foreach ($model->scenarios() as $scenario => $attributes) {
//            if ($scenario == 'default') continue;
//            if (!$this->hasAction($scenario, $actions)) {
//                $actions[$scenario] = [
//                    'class'      => PerformAction::className(),
//                    'controller' => $this,
//                    'id'         => $scenario,
//                ];
//            };
//        };
//
//        return $actions;
//    }
//
//    public function behaviors () {
//        $behaviors = parent::behaviors();
//        $model     = static::newModel();
//        $verbs     = &$behaviors['verbs'];
//        if (!is_array($verbs)) $verbs = [
//            'class' => VerbFilter::className(),
//        ];
//        $actions = &$verbs['actions'];
//        foreach ($model->scenarios() as $scenario => $attributes) {
//            if ($scenario == 'default') continue;
//            if ($actions[$scenario]) continue;
//            $actions[$scenario] = ['post'];
//        };
//
//        return $behaviors;
//    }
//

    public function hasAction ($id, $actions = null) {
        if (is_null($actions)) $actions = $this->actions();
        $method = 'action' . Inflector::id2camel($id);

        return isset($actions[$id]) || method_exists($this, $method);
    }

    public function actionTest ($action) {
    }

    /**
     * Searches the data in the model
     *
     * @return mixed
     */
    public function actionSearch () {
        $result      = [];
        $search      = \Yii::$app->request->get() ?: \Yii::$app->request->post();
        $searchModel = static::searchModel();
        $formName    = $searchModel->formName();

        $props[static::modelClassName()] = AH::merge(AH::remove($search, 'return'), AH::remove($search, 'rename'));
        $searchCond[$formName]           = $search;

        $data = $searchModel->search($searchCond)->getModels();

        foreach ($data as $k => $v) {
            $result[$k] = AH::toArray($v, $props);
        }

        return $this->renderJson($result);
    }

    public function actionInfo () {
        return static::actionSearch();
    }

    public function actionList () {
        return static::actionSearch();
    }

    public function actionGetList () {
        $search      = \Yii::$app->request->get();
        $searchModel = static::searchModel();
        $formName    = $searchModel->formName();
        $searchCond  = [$formName => $search];

        $data = $searchModel->search($searchCond)->getList();
        d($data); /// TODO: XXX WTF?? 
    }

    /**
     * @param $id integer the ID of requested model
     * @param array $add - additional data to be passed to render
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView ($id, $add = []) { /// TODO: XXX REMOVE $add! VULNERABLE!
        $model = $this->findModel($id);

        return $this->render('view', AH::merge(compact('model'), $add));
    }

    /**
     * @param array $add - additional data to be passed to render
     * @return string
     */
    public function actionIndex ($add = []) { /// TODO: XXX REMOVE $add! VULNERABLE!
        $model        = static::searchModel();
        $searchModel  = $model; /// TODO: XXX remove use of searchModel
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', AH::merge(compact('model', 'searchModel', 'dataProvider'), $add));
    }

    /**
     * Deletes an existing object
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete ($id) {
        $this->newModel(compact('id'))->delete();

        return $this->redirect(['index']);
    }

    static public function getRefs ($gtype) {
        return Ref::find()->where(['gtype' => $gtype, 'limit' => 'ALL'])->getList();
    }

    static public function getClassRefs ($type) { return static::getRefs($type . ',' . static::modelId('_')); }

    static public function getBlockReasons () { return static::getRefs('type,block'); }

    static public function getPriorities () { return static::getRefs('type,priority'); }

}
