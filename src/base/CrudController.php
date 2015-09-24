<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\base;

use hipanel\actions\PerformAction;
use hipanel\helpers\ArrayHelper as AH;
use hipanel\models\Ref;
use Yii;
use yii\helpers\Inflector;

class CrudController extends Controller
{
    public function hasAction ($id, $actions = null) {
        if (is_null($actions)) $actions = $this->actions();
        $method = 'action' . Inflector::id2camel($id);

        return isset($actions[$id]) || method_exists($this, $method);
    }

    public function actionTest ($action) {
    }

    static public function getRefs ($gtype) {
        return Ref::find()->where(['gtype' => $gtype, 'limit' => 'ALL'])->getList();
    }

    static public function getClassRefs ($type) { return static::getRefs($type . ',' . static::modelId('_')); }

    static public function getBlockReasons () {
        static $blockReason;
        if ($blockReason === null) $blockReason = static::getRefs('type,block');
        return $blockReason;
    }

    static public function getPriorities () { return static::getRefs('type,priority'); }

}
