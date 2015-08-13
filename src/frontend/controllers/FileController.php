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
    public function behaviors() {
        return [
            'fileAccess' => [
                'class' => 'common\components\filters\FileAccessFilter',
                'only' => ['view', 'get'],
            ],
        ];
    }

    public function actionView($id, $object_id, $object_name) {
        return File::renderFile($id, $object_id, $object_name);
    }

    public function actionGet($id, $object_id, $object_name, $ext, $contentType) {
        Yii::$app->response->sendFile(File::renderFile($id, $object_id, $object_name, false), join('.', [$id, $ext]), ['mimeType' => $contentType]);
    }

    public function actionTempView($temp_file, $key) {
        if ($key == File::getHash($temp_file)) {
            Yii::$app->response->sendFile(File::getTempFolder() . DIRECTORY_SEPARATOR . $temp_file);
        }
        Yii::$app->end();
    }
}

