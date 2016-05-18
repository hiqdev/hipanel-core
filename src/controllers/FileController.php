<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\controllers;

use common\models\File;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class FileController extends Controller
{
    /* XXX ask sol
    public function behaviors() {
        return [
            'fileAccess' => [
                'class' => 'common\components\filters\FileAccessFilter',
                'only' => ['view', 'get'],
            ],
        ];
    }
    */

    public function actionView($id, $object_id = null, $object_name = null, $nocache = false)
    {
        return File::renderFile($id, $object_id, $object_name, true, $nocache);
    }

    public function actionGet($id, $object_id, $object_name, $ext, $contentType)
    {
        Yii::$app->response->sendFile(File::renderFile($id, $object_id, $object_name, false), implode('.', [$id, $ext]), ['mimeType' => $contentType]);
    }

    public function actionTempView($temp_file, $key)
    {
        if ($key === File::getHash($temp_file)) {
            Yii::$app->response->sendFile(File::getTempFolder() . DIRECTORY_SEPARATOR . $temp_file);
        }
        Yii::$app->end();
    }
}
