<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\base;

use hipanel\models\Ref;
use yii\helpers\Inflector;

class CrudController extends Controller
{
    public function hasAction($id, $actions = null)
    {
        if (is_null($actions)) {
            $actions = $this->actions();
        }
        $method = 'action' . Inflector::id2camel($id);

        return isset($actions[$id]) || method_exists($this, $method);
    }

    public function actionTest($action)
    {
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
        if ($blockReasons  === null) {
            $blockReasons = $this->getRefs('type,block');
        }
        return $blockReasons;
    }

    public function getPriorities()
    {
        static $priorities;
        if ($priorities === null) {
            $priorities = $this->getRefs('type,priority');
        }
        return $priorities;
    }
}
