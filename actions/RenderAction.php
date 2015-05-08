<?php
/**
 * @link    http://hiqdev.com/hipanel-module-hosting
 * @license http://hiqdev.com/hipanel-module-hosting/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\actions;

use Yii;

class RenderAction extends Action
{
    /**
     * @var string view to render.
     */
    public $view;

    /**
     * @var array
     */
    public $params;

    public function run()
    {
        return $this->controller->render($this->view, $this->params);
    }

}
