<?php
/**
 * @link    http://hiqdev.com/hipanel-module-hosting
 * @license http://hiqdev.com/hipanel-module-hosting/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\actions;

use Yii;

class RedirectAction extends Action
{

    /**
     * @var string|array url to redirect to.
     */
    public $url;

    public function run()
    {
        return $this->controller->redirect($this->url);
    }

}
