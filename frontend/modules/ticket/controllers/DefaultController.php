<?php

namespace app\modules\ticket\controllers;

use app\modules\ticket\models\Ticket;
use app\modules\ticket\models\TicketSearch;
use frontend\components\Http;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use frontend\components\Himodels;

class DefaultController extends Controller
{
    public function actionIndex()
    {
//        $data = \frontend\components\Http::get('ticketsSearch', ['limit'=>'1000']);
//        $dataProvider = new \yii\data\ArrayDataProvider([
//            'allModels' => $data,
//            'sort' => [
//                // 'attributes' => ['name'],
//            ],
//            'pagination' => [
//                'pageSize' => 25,
//            ],
//        ]);
//
//        return $this->render('index',['dataProvider'=>$dataProvider]);
        $searchModel = new TicketSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionView($id)
    {
        $data = \frontend\components\Http::get('ticketGetInfo', ['id'=>$id]);
        $dataProvider =  new \yii\data\ArrayDataProvider([
            'allModels' => $data,
        ]);
        return $this->render('view', [
            'data'=>$data
        ]);
    }

    /**
     * Creates a new Ticket model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // $model = Himodels::ticketModel();
        $model = new Ticket();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Ticket model.
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
     * Deletes an existing Ticket model.
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
     * Finds the Ticket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ticket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ticket::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionRecipientsList($search = null, $id = null) {
        $out = ['more' => false];
        if (!is_null($search)) {
            $data = Http::get('clientsGetList',['client_like'=>$search]);
            $res = [];
            foreach ($data as $k=>$v) $res[] =  ['id' => $k, 'text' => $v];
            $out['results'] = $res;
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => City::find($id)->name];
        }
        else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
        }
        echo \yii\helpers\Json::encode($out);
    }
}
