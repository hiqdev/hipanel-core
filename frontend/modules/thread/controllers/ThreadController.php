<?php
namespace app\modules\thread\controllers;

use app\modules\thread\models\Thread;
use app\modules\thread\models\ThreadSearch;
use common\models\File;
use frontend\components\hiresource\HiResException;
use frontend\models\Ref;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class ThreadController extends Controller
{

    public function actionIndex() {
        $searchModel = new ThreadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'state_data' => \yii\helpers\ArrayHelper::map(Ref::find()->where(['gtype' => 'state,ticket'])->getList(), 'gl_key', function ($v) {
                return \frontend\components\Re::l($v->gl_value);
            }),
            'priority_data' => Ref::getList('priority'),
        ]);
    }

    private $_subscribeAction = ['subscribe' => 'add_watchers', 'unsubscribe' => 'del_watchers',];

    private function getFilters($name) {
        return ArrayHelper::map(Ref::find()->where(['gtype' => 'type,' . $name])->getList(), 'gl_key', function ($v) { return \frontend\components\Re::l($v->gl_value); });
    }

    public function actionView($id) {
        return $this->render('view', ['model' => $this->findModel($id),]);
    }

    /**
     * Creates a new Thread model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Thread();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstances($model, 'file');

            if ($model->file) { // && $model->validate()
                foreach ($model->file as $file) {
                    $filename = Yii::$app->user->id . '_' . uniqid() . '.' . $file->extension;
                    $url = File::makeThreadFileUrl($filename); // File::makeThreadFileUrl($file->tempName);
                    $file->saveAs('uploads/' . $filename);
                    $fileId = File::perform('Put', ['url' => $url, 'filename' => $filename]);
                    $model->file_ids .= $fileId . ',';
                }
            }
            \yii\helpers\VarDumper::dump($model->load(Yii::$app->request->post()), 10, true);die;
            if ($model->save()) return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('create', ['model' => $model]);
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
     * Updates an existing Thread model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $model->scenario = 'answer';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else {
            return $this->render('update', ['model' => $model,]);
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
        if ($this->_threadChange($options)) \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'You subscibed!'));
        else
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'You do not subscibed!'));
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionUnsubscribe($id) {
        if (!in_array($this->action->id, array_keys($this->_subscribeAction))) return false;
        $options[$id] = [
            'id' => $id,
            $this->_subscribeAction[$this->action->id] => \Yii::$app->user->identity->username
        ];
        if ($this->_threadChange($options)) \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'You unsubscibed!'));
        else
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'You do not unsubscibed!'));
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionClose($id) {
        $options[$id] = ['id' => $id, 'state' => $this->action->id, 'is_private' => 1];
        if ($this->_threadChange($options)) \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Ticket is closed!'));
        else
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'Something goes wrong!'));
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionOpen($id) {
        $options[$id] = ['id' => $id, 'state' => $this->action->id, 'is_private' => 1];
        if ($this->_threadChange($options)) \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Ticket id open!'));
        else
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'Something goes wrong!'));
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSettings() {
        return $this->render('settings', []);
    }

    public function actionPriorityUp($id) {
        $options[$id] = ['id' => $id, 'priority' => 'high'];
        if ($this->_threadChange($options)) \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Change priority to high!'));
        else
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'Something goes wrong!'));
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionPriorityDown($id) {
        $options[$id] = ['id' => $id, 'priority' => 'medium'];
        if ($this->_threadChange($options)) \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Change priority to medium!'));
        else
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'Something goes wrong!'));
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Numerous thread changes in one method, like BladeRoot did :)
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
        if (($model = Thread::findOne(['id' => $id, 'with_answers' => 1])) !== null) {
            return $model;
        }
        else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /* AJAX */

    public function actionClientList($search = null, $id = null) {
        $out = ['more' => false];
        if (!is_null($search)) {
            $data = \app\modules\client\models\Client::find()->where(['client_like' => $search])->getList(); // Http::get('clientsGetList',['client_like'=>$search]);
            $res = [];
            foreach ($data as $item) {
                $res[] = ['id' => $item->gl_key, 'text' => $item->gl_value];
            }
            $out['results'] = $res;
        }
        elseif ($id != 0) {
            $out['results'] = [
                'id' => $id,
                'text' => \app\modules\client\models\Client::find()->where([
                    'id' => $id,
                    'with_contact' => 1
                ])->one()->login
            ];
        }
        else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
        }
        echo \yii\helpers\Json::encode($out);
    }

    public function actionManagerList($search = null, $id = null) {
        $out = ['more' => false];
        if (!is_null($search)) {
            $data = \app\modules\client\models\Client::find()->where([
                'client_like' => $search,
                'manager_only' => 1
            ])->getList();
            $res = [];
            foreach ($data as $item) $res[] = ['id' => $item->gl_key, 'text' => $item->gl_value];
            $out['results'] = $res;
        }
        elseif ($id != 0) {
            $out['results'] = [
                'id' => $id,
                'text' => \app\modules\client\models\Client::find()->where([
                    'id' => $id,
                    'with_contact' => 1
                ])->one()->login
            ];
        }
        else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
        }
        echo \yii\helpers\Json::encode($out);
    }

    public function actionStateList($search = null, $id = null) {
        $out = ['more' => false];
        if (!is_null($search)) {
            $data = Ref::find()->where(['gtype' => 'state,ticket'])->getList();
            $res = [];
            foreach ($data as $item) $res[] = ['id' => $item->gl_key, 'text' => $item->gl_value];
            $out['results'] = $res;
        }
        echo \yii\helpers\Json::encode($out);
    }
}
