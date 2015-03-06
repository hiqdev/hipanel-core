<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 05.03.15
 * Time: 17:38
 */

namespace frontend\controllers;

use frontend\components\Controller;

class SkinController extends Controller
{
    public function actionView() {
        return $this->render('view');
    }
}