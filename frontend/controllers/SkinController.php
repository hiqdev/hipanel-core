<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 05.03.15
 * Time: 17:38
 */

namespace frontend\controllers;

use common\models\Skin;
use frontend\components\Controller;
use Yii;

class SkinController extends Controller
{
    /**
     * Skin form
     * @return string
     */
    public function actionIndex() {
        $model = new Skin();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->saveLayoutSettings()) {
                Yii::$app->session->setFlash('success', 'Layout settings saved.');
            } else {
                Yii::$app->session->setFlash('error', 'Layout settings not saved.');
            }
        } else {
            $model->loadLayoutSettings();
        }
        return $this->render('index', [
            'model' => $model,
        ]);

    }
}