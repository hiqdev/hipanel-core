<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\actions;

use hiqdev\hiart\Collection;
use Yii;

/**
 * Class ClassValuesAction.
 */
class ClassValuesAction extends Action
{
    public $valuesClass;

    public $view;

    public $model;

    public function run($id)
    {
        $this->model = $this->controller->findModel($id);
        $this->model->scenario = $this->scenario;
        $request = Yii::$app->request;

        if ($request->isAjax && Yii::$app->request->isPost) {
            /// to be redone with native Action collection
            $this->model = (new Collection(['model' => $this->model]))->load()->first;

            $this->beforePerform();
            $this->model->perform('set-class-values', [
                'id'     => $id,
                'class'  => $this->valuesClass,
                'values' => $this->model->dirtyAttributes,
            ]);
            Yii::$app->end();
        }
        $this->model->setAttributes($this->model->perform('get-class-values', ['id' => $id, 'class' => $this->valuesClass]));

        return $this->controller->renderAjax($this->view, ['model' => $this->model]);
    }
}
