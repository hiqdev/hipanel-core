<?php

namespace hipanel\widgets;

/**
 * Class SidebarAdBanner trying to display banner images with a link transition in sidebar.
 * It expects that it will be configured by DI in the block `definitions` and there banners will be transferred
 * to the properties `items`. The data must be of the key is the transition link
 * and the value is the link to the banner image.
 *
 * Example:
 * // to `definitions`
 *
 * \hipanel\widgets\SidebarAdBanner::class => [
 *     'items' => $params['ad-banner.sidebar.items'],
 * ],
 *
 * @package hipanel\widgets
 */
class SidebarAdBanner extends AdBanner
{
    /** {@inheridoc} */
    public $imgOptions = [
        'class' => 'img-responsive hidden-xs hidden-sm',
    ];
}
