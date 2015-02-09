<?php

namespace app\modules\client\controllers;

use yii\web\Controller;
use Yii;
use \app\modules\client\models\Article;
use \app\modules\client\models\ArticleSearch;

class ArticleController extends DefaultController {

    protected $class    = 'Article';
    protected $path     = '\app\modules\client\models';

    public function beforeAction ($action) {
        if (isset($_POST['Article']['data'])) {
            $_POST['Article']['texts'] = $_POST['Article']['data'];
            unset($_POST['Article']['data']);
        }
        return parent::beforeAction($action);
    }

    public function actionCreate () {
        $model = new Article;
        if ( $model->load(Yii::$app->request->post()) && $model->save() ) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
}
