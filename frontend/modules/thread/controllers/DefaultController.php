<?php
namespace app\modules\Thread\controllers;

use app\modules\thread\models\Thread;
use app\modules\thread\models\ThreadSearch;
use frontend\components\Http;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new ThreadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    private function getFilters ($name) {
        return ArrayHelper::map(\frontend\models\Ref::find()->where(['gtype' => 'type,'.$name])->getList(),
            'gl_key',
            function ($v) { return \frontend\components\Re::l($v->gl_value); });
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Thread model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Thread();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Thread model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
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
     * Deletes an existing Thread model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    public function actionSettings()
    {
        return $this->render('settings', []);
    }

    /**
     * Finds the Thread model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Thread the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Thread::findOne(['id'=>$id,'with_answers'=>1])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionClientList($search = null, $id = null) {
        $out = ['more' => false];
        if (!is_null($search)) {
            $data = \frontend\modules\client\models\Client::find()->where(['client_like'=>$search])->getList();// Http::get('clientsGetList',['client_like'=>$search]);
            $res = [];
            foreach ($data as $item) $res[] =  ['id' => $item->gl_key, 'text' => $item->gl_value];
            $out['results'] = $res;
        }
        elseif ($id != 0) {
            $out['results'] = ['id' => $id, 'text' => \frontend\modules\client\models\Client::find()->where(['id'=>$id,'with_contact'=>1])->one()->login];
        }
        else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
        }
        echo \yii\helpers\Json::encode($out);
    }

    public function actionManagerList($search = null, $id = null) {
        $out = ['more' => false];
        if (!is_null($search)) {
            $data = \frontend\modules\client\models\Client::find()->where(['client_like'=>$search, 'manager_only'=>1])->getList();// Http::get('clientsGetList',['client_like'=>$search]);
            $res = [];
            foreach ($data as $item) $res[] =  ['id' => $item->gl_key, 'text' => $item->gl_value];
            $out['results'] = $res;
        }
        elseif ($id != 0) {
            $out['results'] = ['id' => $id, 'text' => \frontend\modules\client\models\Client::find()->where(['id'=>$id,'with_contact'=>1])->one()->login];
        }
        else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
        }
        echo \yii\helpers\Json::encode($out);
    }

    public function actionStateList($search = null, $id = null) {
        $out = ['more' => false];
        if (!is_null($search)) {
            $data = Ref::find()->where(['gtype'=>'state,ticket'])->getList();
            $res = [];
            foreach ($data as $item) $res[] =  ['id' => $item->gl_key, 'text' => $item->gl_value];
            $out['results'] = $res;
        }
        elseif ($id != 0) {
            $out['results'] = ['id' => $id, 'text' => \frontend\modules\client\models\Client::find()->where(['id'=>$id,'with_contact'=>1])->one()->login];
        }
        else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
        }
        echo \yii\helpers\Json::encode($out);
    }
}
