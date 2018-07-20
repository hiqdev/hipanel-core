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
    public function hasAction($id, $actions = null): bool
    {
        if ($actions === null) {
            $actions = $this->actions();
        }
        $method = 'action' . Inflector::id2camel($id);

        return isset($actions[$id]) || method_exists($this, $method);
    }

    public function getRefs($name, $translate = null, array $options = []): array
    {
        return Ref::getList($name, $translate, $options);
    }

    public function getClassRefs($type, $translate = null, array $options = []): array
    {
        return $this->getRefs($type . ',' . static::modelId('_'), $translate, $options);
    }

    public function getBlockReasons(): array
    {
        return $this->getRefs('type,block', 'hipanel');
    }

    public function getCurrencyTypes(): array
    {
        return $this->getRefs('type,currency', 'hipanel', ['orderby' => 'no_asc']);
    }
}
