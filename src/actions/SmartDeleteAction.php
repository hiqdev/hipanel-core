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
 * Class SmartDeleteAction.
 */
class SmartDeleteAction extends SmartPerformAction
{
    public function loadCollection($data = null)
    {
        // Fixes delete buttons in GridView.
        // When button in being pressed, the system submits POST request which contains
        // POST model ClassSearch and GET attribute with ID
        $data = Yii::$app->request->get() ? [Yii::$app->request->get()] : null;
        parent::loadCollection($data);
    }

    /** {@inheritdoc} */
    protected function getDefaultRules()
    {
        return array_merge([
            'POST html | POST pjax' => [
                'save'    => true,
                'success' => [
                    'class' => RedirectAction::class,
                    'url'   => 'index',
                ],
            ],
        ], parent::getDefaultRules());
    }
}
