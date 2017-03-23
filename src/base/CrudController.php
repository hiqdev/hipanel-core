<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
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

    public function getRefs($name, $translate = null, $options = [])
    {
        return Ref::getList($name, $translate, $options);
    }

    public function getClassRefs($type, $translate = null, $options = [])
    {
        return $this->getRefs($type . ',' . static::modelId('_'), $translate, $options);
    }

    public function getBlockReasons()
    {
        return $this->getRefs('type,block', 'hipanel');
    }

    public function getCurrencyTypes()
    {
        return $this->getRefs('type,currency', 'hipanel', ['orderby' => 'no_asc']);
    }
}
