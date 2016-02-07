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

/**
 * Class RenderJsonAction.
 */
class RenderJsonAction extends Action
{
    /**
     * @var callback|array view to render.
     */
    public $return;

    public function run()
    {
        $data = $this->return instanceof \Closure ? call_user_func($this->return, $this) : $this->return;
        return $this->controller->renderJson($data);
    }
}
