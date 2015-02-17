<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 10.02.15
 * Time: 18:12
 */
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\File;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class FileController extends Controller
{

    public function actionIndex() {
        print 'Hello world!';
    }

    public function actionView($id) {
        $model = $this->findModel($id);
        $response = Yii::$app->getResponse();
        $response->format = Response::FORMAT_RAW;
        $response->getHeaders()->add('content-type', $model->type);
        return file_get_contents($model->filename);
    }

    public function actionTempView($temp_file, $key) {
        if ($key == File::getHash($temp_file)) {
            Yii::$app->response->sendFile(File::getTempFolder() . DIRECTORY_SEPARATOR . $temp_file);
        }
        Yii::$app->end();
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    protected function findModel($id) {
        if (($model = File::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

