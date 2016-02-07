<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\filters;

use common\models\File;
use hipanel\base\Err;
use Yii;
use yii\base\ActionFilter;

class FileAccessFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        $request = Yii::$app->request;
        if ($request->get('id') && $request->get('object_id') && $request->get('object_name')) {
            $canISee = (boolean) Err::not(File::perform('GetInfo', ['id' => $request->get('id'), 'object_id' => $request->get('object_id'), 'object' => $request->get('object_name')]));
            if ($canISee) {
                return parent::beforeAction($action);
            }
        }
        return false;
    }
}
