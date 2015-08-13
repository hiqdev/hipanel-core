<?php

namespace hipanel\actions;

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
            'id'          => $data['pk'],
            $data['name'] => $data['value'],
        ]]);
    }

    public function run()
    {
        $error = $this->perform($rule);
        if ($error) {
            Yii::$app->response->statusCode = 400;
        }
        return $error;
    }

}
