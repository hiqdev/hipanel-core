<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\actions;

/**
 * Class PrepareAjaxViewAction.
 */
class PrepareAjaxViewAction extends ViewAction
{
    /** {@inheritdoc} */
    protected function getDefaultRules()
    {
        $rule = [
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
        ];

        return array_merge(parent::getDefaultRules(), [
            'ajax' => $rule,
            'POST | GET | ajax' => $rule,
        ]);
    }
}
