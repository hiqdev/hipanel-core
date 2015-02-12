<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 10.02.15
 * Time: 18:12
 */
namespace frontend\controllers;

use yii\web\Controller;
use common\models\File;

class FileController extends Controller
{
    public function actionView($id) {
        File::perform('Search', ['ids' => $id]);
        die();
        return File::getFile($id);
    }
}

