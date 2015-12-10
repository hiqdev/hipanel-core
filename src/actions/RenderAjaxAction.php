<?php
/**
 * @link    http://hiqdev.com/hipanel-module-hosting
 * @license http://hiqdev.com/hipanel-module-hosting/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\actions;

use Yii;

/**
 * Class RenderAjaxAction
 *
 * @package hipanel\actions
 */
class RenderAjaxAction extends RenderAction
{
    public function run()
    {
        return $this->controller->renderAjax($this->getViewName(), $this->getParams());
    }
}
