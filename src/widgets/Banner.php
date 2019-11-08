<?php

namespace hipanel\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\StringHelper;

/**
 * Class Banner lookup `module.core.ad.banners.sidebar` or `module.core.ad.banners.main` params and trying to display
 * banner images with a link transition. It expects that in these params there will be an associative array in which
 * the key is the transition link and the value is the link to the banner image.
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
    public $items = [];

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

    /**
     * @return array
     */
    private function getItems(): array
    {
        $items = [];
        if ($this->isSidebar === false && !StringHelper::endsWith(Yii::$app->request->url, 'dashboard/dashboard')) {
            return [];
        }

        if (!empty($this->items)) {
            $items = $this->items;
        }

        if ($formParams = Yii::$app->params[sprintf('module.core.ad.banners.%s', $this->isSidebar ? 'sidebar' : 'main')]) {
            $items = $formParams;
        }

        return $items;
    }
}
