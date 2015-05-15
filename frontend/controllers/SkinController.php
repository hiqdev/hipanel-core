<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 05.03.15
 * Time: 17:38
 */

namespace frontend\controllers;

use common\models\Skin;
use hipanel\base\Controller;
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

    public function actionChangeTheme($theme) {
        if (in_array($theme, ['adminlte', 'adminlte2'])) {
            Yii::$app->session->set('user.theme', $theme);
        }
        $this->redirect(['/skin/index']);
    }

    public function actionCollapsedSidebar()
    {
        if (Yii::$app->request->isAjax) {
            $model = new Skin();
            $model->collapsed_sidebar = Yii::$app->request->post('collapsed_sidebar');
            $model->saveLayoutSettings();
        }
    }
}