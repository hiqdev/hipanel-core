<?php

namespace hipanel\filters;

use hipanel\base\Err;
use common\models\File;
use Yii;
use yii\base\ActionFilter;

class FileAccessFilter extends ActionFilter
{
    public function beforeAction($action) {
        $request = Yii::$app->request;
        if ($request->get('id') && $request->get('object_id') && $request->get('object_name')) {
            $canISee = (boolean)Err::not(File::perform('GetInfo', ['id' => $request->get('id'), 'object_id' => $request->get('object_id'), 'object' => $request->get('object_name')]));
            if ($canISee)
                return parent::beforeAction($action);
        }
        return false;
    }
}
