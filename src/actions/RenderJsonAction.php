<?php
/**
 * @link    http://hiqdev.com/hipanel-module-hosting
 * @license http://hiqdev.com/hipanel-module-hosting/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\actions;

use Yii;

/**
 * Class RenderJsonAction
 *
 * @package hipanel\actions
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
