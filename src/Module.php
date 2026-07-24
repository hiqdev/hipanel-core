<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel;

use Yii;

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
