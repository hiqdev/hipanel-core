<?php
namespace app\modules\thread\controllers;

use app\modules\thread\models\Thread;
use app\modules\thread\models\ThreadSearch;
use frontend\components\hiresource\HiResException;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ThreadController extends Controller {
    public function actionIndex () {
        $searchModel  = new ThreadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
            ]);

    }

    private $_subscribeAction = [
        'subscribe'   => 'add_watchers',
        'unsubscribe' => 'del_watchers',
    ];

    private function getFilters ($name) {
        return ArrayHelper::map(\frontend\models\Ref::find()->where(['gtype' => 'type,' . $name])->getList(),
            'gl_key',
            function ($v) { return \frontend\components\Re::l($v->gl_value); });
    }

    public function actionView ($id) {
        return $this->render('view',
            [
                'model' => $this->findModel($id),
            ]);
    }

    /**
     * Creates a new Thread model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate () {
        $model = new Thread();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create',
                [
                    'model' => $model,
                ]);
        }
    }

    /**
     * Updates an existing Thread model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate ($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update',
                [
                    'model' => $model,
                ]);
        }
    }

    /**
     * Deletes an existing Thread model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete ($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionSubscribe ($id) {
        if ($this->_subscriptionOperations($id, 'subscribe'))
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'You subscibed!'));
        else
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'You do not subscibed!'));

        return $this->redirect(['view', 'id'=>$id]);
    }

    public function actionUnsubscribe ($id) {
        if ($this->_subscriptionOperations($id, 'unsubscribe'))
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'You unsubscibed!'));
        else
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'You do not unsubscibed!'));
        return $this->redirect(['view', 'id'=>$id]);
    }

    private function _subscriptionOperations ($id, $action) {
        if (!in_array($action, array_keys($this->_subscribeAction))) return false;
        $options[$id] = ['id'=>$id, $this->_subscribeAction[$action]=>\Yii::$app->user->identity->username];
        try {
            Thread::perform('Answer', $options, true);
        } catch (HiResException $e) {
            return false;
        }
        return true;
    }

    private function _threadChangeState($id, $state) {
        $options[$id] = ['id'=>$id, 'state'=>'close', 'is_private'=>1];
        try {
            Thread::perform('Answer', $options, true);
        } catch (HiResException $e) {
            return false;
        }
        return true;
    }

    public function actionClose($id) {
        if ($this->_threadChangeState($id, $this->action->id))
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Ticket is closed!'));
        else
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'Something goes wrong!'));
        return $this->redirect(['view', 'id'=>$id]);
    }

    public function actionOpen($id) {
        if ($this->_threadChangeState($id, $this->action->id))
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Ticket id open!'));
        else
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'Something goes wrong!'));
        return $this->redirect(['view', 'id'=>$id]);
    }

    public function actionSettings () {
        return $this->render('settings', []);
    }

    /**
     * Finds the Thread model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Thread the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel ($id) {
        if (($model = Thread::findOne(['id' => $id, 'with_answers' => 1])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /* AJAX */

    public function actionClientList ($search = null, $id = null) {
        $out = ['more' => false];
        if (!is_null($search)) {
            $data = \frontend\modules\client\models\Client::find()->where(['client_like' => $search])->getList(); // Http::get('clientsGetList',['client_like'=>$search]);
            $res  = [];
            foreach ($data as $item) {
                $res[] = ['id' => $item->gl_key, 'text' => $item->gl_value];
            }
            $out['results'] = $res;
        } elseif ($id != 0) {
            $out['results'] = ['id' => $id, 'text' => \frontend\modules\client\models\Client::find()->where(['id' => $id, 'with_contact' => 1])->one()->login];
        } else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
        }
        echo \yii\helpers\Json::encode($out);
    }

    public function actionManagerList ($search = null, $id = null) {
        $out = ['more' => false];
        if (!is_null($search)) {
            $data = \frontend\modules\client\models\Client::find()->where(['client_like'  => $search,
                                                                           'manager_only' => 1
            ])->getList(); // Http::get('clientsGetList',['client_like'=>$search]);
            $res  = [];
            foreach ($data as $item) $res[] = ['id' => $item->gl_key, 'text' => $item->gl_value];
            $out['results'] = $res;
        } elseif ($id != 0) {
            $out['results'] = ['id' => $id, 'text' => \frontend\modules\client\models\Client::find()->where(['id' => $id, 'with_contact' => 1])->one()->login];
        } else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
        }
        echo \yii\helpers\Json::encode($out);
    }

    public function actionStateList ($search = null, $id = null) {
        $out = ['more' => false];
        if (!is_null($search)) {
            $data = Ref::find()->where(['gtype' => 'state,ticket'])->getList();
            $res  = [];
            foreach ($data as $item) $res[] = ['id' => $item->gl_key, 'text' => $item->gl_value];
            $out['results'] = $res;
        } elseif ($id != 0) {
            $out['results'] = ['id' => $id, 'text' => \frontend\modules\client\models\Client::find()->where(['id' => $id, 'with_contact' => 1])->one()->login];
        } else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
        }
        echo \yii\helpers\Json::encode($out);
    }
}
