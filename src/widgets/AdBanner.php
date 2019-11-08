<?php

namespace hipanel\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\StringHelper;

/**
 * Class AdBanner trying to display banner images with a link transition.
 * It expects that it will be configured by DI in the block `definitions` and there banners will be transferred
 * to the properties `items`. The data must be of the key is the transition link
 * and the value is the link to the banner image.
 *
 * Example:
 * // to `definitions`
 *
 * \hipanel\widgets\AdBanner::class => [
 *     'items' => $params['ad-banner.dashboard.items'],
 * ],
 *
 * @package hipanel\widgets
 */
class AdBanner extends Widget
{
    /**
     * @var array
     */
    public $items = [];

    /**
     * @var array
     */
    public $imgOptions = [];

    /**
     * @var array
     */
    public $linkOptions = [
        'target' => '_blank',
        'rel' => 'noopener noreferrer',
    ];

    /**
     * @return string
     */
    public function run(): string
    {
        $html = '';
        foreach ($this->getItems() as $url => $src) {
            $html .= Html::a(Html::img($src, $this->imgOptions), $url, $this->linkOptions);
        }

        return $html;
    }

    /**
     * @return array
     */
    protected function getItems(): array
    {
        if (!($this instanceof SidebarAdBanner) && !StringHelper::endsWith(Yii::$app->request->url, 'dashboard/dashboard')) {
            return [];
        }

        return $this->items;
    }
}
