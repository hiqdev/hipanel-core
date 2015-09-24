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

    public function getRefs($gtype)
    {
        return $this->getCache()->getTimeCached(3600, [$gtype], function ($gtype) {
            return Ref::find()->where(['gtype' => $gtype, 'limit' => 'ALL'])->getList();
        });
    }

    public function getClassRefs($type)
    {
        return $this->getRefs($type . ',' . static::modelId('_'));
    }

    public function getBlockReasons()
    {
        static $blockReasons;
        if ($blockReasons  === null) $blockReasons = $this->getRefs('type,block');
        return $blockReasons;
    }

    public function getPriorities()
    {
        static $priorities;
        if ($priorities === null) $priorities = $this->getRefs('type,priority');
        return $priorities;
    }

}
