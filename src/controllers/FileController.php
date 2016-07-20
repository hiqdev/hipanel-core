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

use hipanel\helpers\FileHelper;
use Yii;
use yii\filters\HttpCache;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

class FileController extends Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'file-cache' => [
                'class' => HttpCache::class,
                'only' => ['view', 'get'],
                'lastModified' => function () {
                    $id = Yii::$app->request->get('id');
                    $nocache = Yii::$app->request->get('nocache', false);
                    if ($nocache || $id === null) {
                        return null;
                    }

                    $filename = Yii::$app->fileStorage->get($id);
                    return filemtime($filename);
                }
            ]
        ]);
    }

    public function actionView($id, $nocache = true)
    {
        $filename = Yii::$app->fileStorage->get($id, $nocache);

        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-type', FileHelper::getMimeType($filename));

        return file_get_contents($filename);
    }

    public function actionGet($id, $downloadName = null, $nocache = true)
    {
        $filename = Yii::$app->fileStorage->get($id, $nocache);

        if ($downloadName === null) {
            $file = Yii::$app->fileStorage->getFileModel($id);
            $downloadName = $file->filename;
        }

        Yii::$app->response->sendFile($filename, $downloadName)->send();
    }

    public function actionTempView($filename, $key)
    {
        $content = Yii::$app->fileStorage->getTemporary($filename, $key);

        Yii::$app->response->sendContentAsFile(file_get_contents($content), $filename)->send();
    }
}
