<?php

namespace hipanel\actions;

use hipanel\helpers\ArrayHelper;
use Yii;

/**
 * Class XEditableAction
 */
class XEditableAction extends Action
{
    public function loadCollection($data = null)
    {
        if (is_null($data)) {
            $data = \Yii::$app->request->post();
        }
        return parent::loadCollection([[
            'id'          => ArrayHelper::remove($data, 'pk'),
            $data['name'] => ArrayHelper::remove($data, 'value', []),
        ]]);
    }

    public function run()
    {
        $error = $this->perform();
        if ($error) {
            Yii::$app->response->statusCode = 400;
        }
        return $error;
    }

}
