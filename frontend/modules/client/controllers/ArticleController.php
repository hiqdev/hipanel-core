<?php

namespace app\modules\client\controllers;

use yii\web\Controller;
use Yii;
use \app\modules\client\models\Article;
use \app\modules\client\models\ArticleSearch;

class ArticleController extends Controller
{
    public function beforeAction($action) {
        if (isset($_POST['Article']['data'])) {
            $_POST['Article']['texts'] = $_POST['Article']['data'];
            unset($_POST['Article']['data']);
        }
        return parent::beforeAction($action);
    }

    public function actionIndex($tpl='_tariff')
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'=>$searchModel,
        ]);
    }

    public function actionCreate()
    {
        $model = new Article;
//        $fields = array_merge($baseModel->attributes(), $baseModel->getArticleExtraFields());
//        $model = new \yii\base\DynamicModel($fields);
//        $model->addRule(array_merge($baseModel->activeValidators[0]->attributes, $baseModel->getArticleExtraFields()),'required');

        if ( $model->load(Yii::$app->request->post()) && $model->save() ) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

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

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Article::findOne(['id'=>$id,'with_data'=>1])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
