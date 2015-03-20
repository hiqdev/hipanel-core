<?php
namespace frontend\modules\ticket\controllers;

use frontend\modules\ticket\models\TicketSettings;
use Yii;
use frontend\modules\ticket\models\Thread;
use frontend\modules\ticket\models\ThreadSearch;
use common\models\File;
use frontend\components\hiresource\HiResException;
use frontend\components\CrudController;
use yii\helpers\ArrayHelper;

class TicketController extends CrudController
{
    protected $class = 'Ticket';
    protected $path = 'frontend\modules\ticket\models';

    private $_subscribeAction = ['subscribe' => 'add_watchers', 'unsubscribe' => 'del_watchers'];

    static protected function mainModel() { return Thread::className(); }

    static protected function searchModel() { return ThreadSearch::className(); }

    protected function prepareRefs() {
        return [
            'topic_data' => $this->getRefs('topic,ticket'),
            'state_data' => $this->GetClassRefs('state'),
            'priority_data' => $this->getPriorities(),
        ];
    }

    public function actionIndex() {
        return parent::actionIndex($this->prepareRefs());
    }

    public function actionView($id) {
        $model = $this->findModel(ArrayHelper::merge(compact('id'), ['with_answers' => 1, 'with_files' => 1]));
        $model->scenario = 'answer';
        return $this->render('view', ArrayHelper::merge(compact('model'), $this->prepareRefs()));
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

        return $this->render('create', array_merge(compact('model'), $this->prepareRefs()));
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $model->scenario = 'answer';
        $model->trigger($model::EVENT_BEFORE_UPDATE);
        $model->load(Yii::$app->request->post());
        $model->prepareSpentTime();
        $model->prepareTopic();
        if ($model->validate() && $this->_ticketChange($model->getAttributes(), 'Answer', false)) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        throw new \LogicException('An error has occurred');
    }

    public function actionSubscribe($id) {
        if (!in_array($this->action->id, array_keys($this->_subscribeAction)))
            return false;
        $options[$id] = [
            'id' => $id,
            $this->_subscribeAction[$this->action->id] => \Yii::$app->user->identity->username
        ];
        if ($this->_ticketChange($options))
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'You have successfully subscribed!'));
        else
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'Some error occurred. You have not been subscribed!'));
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionUnsubscribe($id) {
        if (!in_array($this->action->id, array_keys($this->_subscribeAction)))
            return false;
        $options[$id] = [
            'id' => $id,
            $this->_subscribeAction[$this->action->id] => \Yii::$app->user->identity->username
        ];
        if ($this->_ticketChange($options))
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'You have successfully subscribed!'));
        else
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'Some error occurred. You have not been subscribed!'));
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionClose($id) {
        if ($this->_ticketChangeState($id, $this->action->id))
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'The ticket has been closed!'));
        else
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'Some error occurred. The ticket has not been closed.'));
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionOpen($id) {
        if ($this->_ticketChangeState($id, $this->action->id))
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'The ticket has been opened!'));
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

    /**
     * Ticket Settings form
     * @return string
     */
    public function actionSettings() {
        $model = new TicketSettings();
        $request = Yii::$app->request;
        if ($request->isAjax && $model->load($request->post()) && $model->validate()) {
            $model->setFormData();
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Ticket settings saved!'));
        } else {
            $model->getFormData();
        }

        return $this->render('settings', [
            'model' => $model,
        ]);
    }

    public function actionPriorityUp($id) {
        $options[$id] = ['id' => $id, 'priority' => 'high'];
        if ($this->_ticketChange($options))
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Priority has been changed to high!'));
        else
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'Some error occurred! Priority has not been changed to high.'));
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionPriorityDown($id) {
        $options[$id] = ['id' => $id, 'priority' => 'medium'];
        if ($this->_ticketChange($options))
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Priority has been changed to medium!'));
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
    private function _ticketChange($options = [], $apiCall = 'Answer', $bulk = true) {
        try {
            Thread::perform($apiCall, $options, $bulk);
        } catch (HiResException $e) {
            return false;
        }
        return true;
    }

    /**
     * Get quoted text by ajax
     * @return string
     * @throws HiResException
     * @throws \yii\base\ExitException
     */
    public function actionGetQuotedAnswer() {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $id = $request->post('id');
            if ($id != null) {
                $answer = Thread::perform('GetAnswer', ['id' => $id]);
                if (isset($answer['message'])) {
                    return '> ' . str_replace("\n", "\n> ", $answer['message']);
                }
            }
        }
        Yii::$app->end();
    }

    /**
     * @param $id
     * @param $object_id
     * @return array|bool
     */
    public function actionFileView($id, $object_id) {
        return File::renderFile($id, $object_id, 'thread', true);
    }
}
