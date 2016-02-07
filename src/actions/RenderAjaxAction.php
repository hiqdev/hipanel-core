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
 * Class RenderAjaxAction.
 */
class RenderAjaxAction extends RenderAction
{
    public function run()
    {
        return $this->controller->renderAjax($this->getViewName(), $this->getParams());
    }
}
