<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel;

use Yii;
use yii\helpers\Url;

/**
 * HiPanel Core Module.
 */
class Module extends \hipanel\base\Module
{
    public $notPanel;

    public $panelUrl;

    public function isPanel()
    {
        return !$this->notPanel;
    }

    public function redirectPanel()
    {
        $request = Yii::$app->request;
        $path = $request->getPathInfo();
        $vars = $request->get();

        $url = $this->panelUrl . '/' . $path . '?' . http_build_query($vars);

        Yii::$app->response->redirect($url);
        Yii::$app->end();
    }
}
