<?php
namespace frontend\modules\ticket\controllers;

use frontend\modules\ticket\models\Thread;
use frontend\modules\ticket\models\ThreadSearch;
use common\models\File;
use frontend\components\hiresource\HiResException;
use frontend\components\Re;
use frontend\models\Ref;
use Yii;
use yii\base\Event;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use frontend\components\CrudController;

class TicketController extends CrudController {
    protected $class    = 'Ticket';
    protected $path     = 'frontend\modules\ticket\models';

    private $_subscribeAction = ['subscribe' => 'add_watchers', 'unsubscribe' => 'del_watchers'];

    private function _priorityData() {
        return Ref::find()->where(['gtype' => 'type,priority'])->getList();
    }

    public function actionIndex() {
        $searchModel = new ThreadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'topic_data' => $this->objectGetParameters('topic'),
            'priority_data' => $this->_priorityData(),
            'state_data' => $this->objectGetParameters('state'),
        ]);
    }

    private function getFilters($name) {
        return Ref::find()->where(['gtype' => 'type,' . $name])->getList();
    }

    public function actionView($id) {
        $model = $this->findModel($id);
        $model->scenario = 'answer';
        return $this->render('view', [
            'model' => $model,
            'topic_data' => $this->objectGetParameters('topic'),
            'priority_data' => $this->_priorityData(),
            'state_data' => $this->objectGetParameters('state'),
        ]);
    }

    /**
     * Creates a new Thread model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Thread();
        $model->scenario = 'insert';
        $model->load(Yii::$app->request->post());
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'topic_data' => objectGetParameters('topic'),
            'priority_data' => $this->_priorityData(),
            'state_data' => $this->objectGetParameters('state'),
        ]);
    }

    public function actionUpdate($id) {
        $model = Thread::findOne(['id' => $id]); //$this->findModel($id)
        $model->scenario = 'answer';
        $model->trigger($model::EVENT_BEFORE_UPDATE);
        $model->load(Yii::$app->request->post());
        $model->prepareSpentTime();
        $model->prepareTopic();
        if ($model->validate() && $this->_threadChange($model->getAttributes(), 'Answer', false)) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        throw new \LogicException('An error has occurred');
    }

    private function _fileUpload(Thread $model) {
        $model->file = UploadedFile::getInstances($model, 'file');
        \yii\helpers\VarDumper::dump($model->file, 10, true);
        die;
        if ($model->file && $model->validate()) {
            foreach ($model->file as $file) {
                $file->saveAs('uploads/' . Yii::$app->user->id . '_' . uniqid() . '.' . $file->extension);
            }
            return true;
        }
        else false;
    }

    public function actionGetfile() {
        File::getfile(Yii::$app->request->queryParams);
    }

    /**
     * Deletes an existing Thread model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionSubscribe($id) {
        if (!in_array($this->action->id, array_keys($this->_subscribeAction))) return false;
        $options[$id] = [
            'id' => $id,
            $this->_subscribeAction[$this->action->id] => \Yii::$app->user->identity->username
        ];
        if ($this->_threadChange($options)) \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'You have successfully subscribed!'));
        else
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'Some error occurred. You have not been subscribed!'));
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionUnsubscribe($id) {
        if (!in_array($this->action->id, array_keys($this->_subscribeAction))) return false;
        $options[$id] = [
            'id' => $id,
            $this->_subscribeAction[$this->action->id] => \Yii::$app->user->identity->username
        ];
        if ($this->_threadChange($options)) \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'You have successfully subscribed!'));
        else
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'Some error occurred. You have not been subscribed!'));
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionClose($id) {
        if ($this->_ticketChangeState($id, $this->action->id)) \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'The ticket has been closed!'));
        else
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'Some error occurred. The ticket has not been closed.'));
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionOpen($id) {
        if ($this->_ticketChangeState($id, $this->action->id)) \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'The ticket has been opened!'));
        else
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'Some error occurred! The ticket has not been opened.'));
        return $this->redirect(Yii::$app->request->referrer);
    }

    private function _ticketChangeState($id, $action) {
        $options[$id] = ['id' => $id, 'state' => $action];
        try {
            Thread::perform(ucfirst($action), $options, true);
        } catch (HiResException $e) {
            return false;
        }
        return true;
    }

    public function actionSettings() {
        return Yii::$app->request->isAjax
            ? $this->renderPartial('settings', [
                'settings'  => $this->actionGetClassValues('client,ticket_settings', 'frontend\modules\client\models\Client'),
            ])
            : $this->render('settings', [
                'settings'  => $this->actionGetClassValues('client,ticket_settings', 'frontend\modules\client\models\Client'),
            ]);
    }

    public function actionPriorityUp($id) {
        $options[$id] = ['id' => $id, 'priority' => 'high'];
        if ($this->_threadChange($options)) \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Priority has been changed to high!'));
        else
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'Some error occurred! Priority has not been changed to high.'));
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionPriorityDown($id) {
        $options[$id] = ['id' => $id, 'priority' => 'medium'];
        if ($this->_threadChange($options)) \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Priority has been changed to medium!'));
        else
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'Something goes wrong!'));
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Numerous ticket changes in one method, like BladeRoot did :)
     * @param array $options
     * @param string $apiCall
     * @param bool $bulk
     * @return bool
     */
    private function _threadChange($options = [], $apiCall = 'Answer', $bulk = true) {
        try {
            Thread::perform($apiCall, $options, $bulk);
        } catch (HiResException $e) {
            return false;
        }
        return true;
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
    protected function findModel($id) {
        if (($model = Thread::findOne(['id' => $id, 'with_answers' => 1, 'with_files' => 1])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /* AJAX */

//    public function actionClientList($search = null, $id = null) {
//        $out = ['more' => false];
//        if (!is_null($search)) {
//            $data = \app\modules\client\models\Client::find()->where(['client_like' => $search])->getList(); // Http::get('clientsGetList',['client_like'=>$search]);
//            $res = [];
//            foreach ($data as $item) {
//                $res[] = ['id' => $item->gl_key, 'text' => $item->gl_value];
//            }
//            $out['results'] = $res;
//        }
//        elseif ($id != 0) {
//            $out['results'] = [
//                'id' => $id,
//                'text' => \app\modules\client\models\Client::find()->where([
//                    'id' => $id,
//                    'with_contact' => 1
//                ])->one()->login
//            ];
//        }
//        else {
//            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
//        }
//        Yii::$app->response->format = Response::FORMAT_JSON;
//        return $out;
//    }
//
//    public function actionManagerList($search = null, $id = null) {
//        $out = ['more' => true];
//        if (!is_null($search)) {
//            $data = \app\modules\client\models\Client::find()->where([
//                'client_like' => $search,
//                'manager_only' => 1
//            ])->getList();
//            $res = [];
//            foreach ($data as $item) $res[] = ['id' => $item->gl_key, 'text' => $item->gl_value];
//            $out['results'] = $res;
//        }
//        elseif ($id != 0) {
//            $out['results'] = [
//                'id' => $id,
//                'text' => \app\modules\client\models\Client::find()->where([
//                    'id' => $id,
//                    'with_contact' => 1
//                ])->one()->login
//            ];
//        }
//        else {
//            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
//        }
//        Yii::$app->response->format = Response::FORMAT_JSON;
//        return $out;
//    }
//
//    public function actionStateList($search = null, $id = null) {
//        $out = ['more' => true];
//        if (!is_null($search)) {
//            $data = Ref::find()->where(['gtype' => 'state,ticket'])->getList();
//            $res = [];
//            foreach ($data as $item) $res[] = ['id' => $item->gl_key, 'text' => $item->gl_value];
//            $out['results'] = $res;
//        }
//        Yii::$app->response->format = Response::FORMAT_JSON;
//        return $out;
//    }
}
