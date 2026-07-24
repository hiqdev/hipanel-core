<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Json;
use yii\helpers\Url;

class AsyncLoader extends Widget
{
    public $route;

    public $containerSelector;

    public function init()
    {
        parent::init();

        if (!isset($this->route)) {
            throw new InvalidConfigException('The "url" property is not set');
        }

        if (!isset($this->containerSelector)) {
            throw new InvalidConfigException('The "containerSelector" property is not set');
        }
    }

    public function run()
    {
        $url = Json::htmlEncode(Url::to($this->route));
        $selector = Json::htmlEncode($this->containerSelector);

        $this->view->registerJs("$.get($url, function (data) {
            $($selector).replaceWith(data);
        });");

        return $this->renderOverlay();
    }

    private function renderOverlay()
    {
        return '<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>';
    }
}
