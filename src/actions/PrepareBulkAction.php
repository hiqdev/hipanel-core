<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\actions;

use Yii;

/**
 * Class PrepareBulkAction.
 */
class PrepareBulkAction extends ViewAction
{
    /** {@inheritdoc} */
    public function run($id = NULL)
    {
        $this->setId(Yii::$app->request->get('selection', []));

        return parent::run();
    }

    /** {@inheritdoc} */
    protected function getDefaultRules()
    {
        return array_merge(parent::getDefaultRules(), [
            'ajax' => [
                'save' => true,
                'flash' => false,
                'success' => [
                    'class' => RenderAjaxAction::class,
                    'view' => $this->view,
                    'data' => function () {
                        return $this->prepareData();
                    },
                    'params' => function () {
                        foreach ($this->collection->models as $model) {
                            $model->scenario = $this->scenario;
                        }

                        return [
                            'models' => $this->collection->models,
                            'model' => $this->collection->first,
                        ];
                    },
                ],
            ],
        ]);
    }
}
