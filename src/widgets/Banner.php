<?php

namespace hipanel\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\StringHelper;

/**
 * Class Banner trying to display banner images with a link transition.
 * It expects that it will be configured by DI in the block `definitions` and there banners will be transferred
 * to the properties `sidebarItems` and `mainItems`. The data must be of the key is the transition link
 * and the value is the link to the banner image.
 *
 * Example:
 * // to `definitions`
 *
 * \hipanel\widgets\Banner::class => [
 *     'sidebarItems' => ['#' => 'https://cdn.hiqdev.com/hipanel/banners/cloud2.jpg', ...],
 *     'mainItems' => ['#' => 'https://cdn.hiqdev.com/hipanel/banners/cloud1.jpg', ...],
 * ],
 *
 * @package hipanel\widgets
 */
class Banner extends Widget
{
    /**
     * @var boolean
     */
    public $isSidebar = true;

    /**
     * @var array
     */
    public $sidebarItems = [];

    /**
     * @var array
     */
    public $mainItems = [];

    /**
     * @var array
     */
    public $sidebarImgOptions = [
        'class' => 'img-responsive hidden-xs hidden-sm',
    ];

    /**
     * @var array
     */
    public $mainImgOptions = [];

    /**
     * @var array
     */
    public $sidebarLinkOptions = [
        'target' => '_blank',
        'rel' => 'noopener noreferrer',
    ];

    /**
     * @var array
     */
    public $mainLinkOptions = [
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
            $html .= Html::a(Html::img($src, $this->getImgOptions()), $url, $this->getLinkOptions());
        }

        return $html;
    }

    /**
     * @return array
     */
    protected function getItems(): array
    {
        if ($this->isSidebar === false && !StringHelper::endsWith(Yii::$app->request->url, 'dashboard/dashboard')) {
            return [];
        }

        return $this->isSidebar ? $this->sidebarItems : $this->mainItems;
    }

    /**
     * @return array
     */
    private function getImgOptions(): array
    {
        return $this->isSidebar ? $this->sidebarImgOptions : $this->mainImgOptions;
    }

    /**
     * @return array
     */
    private function getLinkOptions(): array
    {
        return $this->isSidebar ? $this->sidebarLinkOptions : $this->mainLinkOptions;
    }
}
