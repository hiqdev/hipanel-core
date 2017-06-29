<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

class MainDetails extends \yii\base\Widget
{
    public $image;

    public $icon;

    public $title;

    public $subTitle;

    public $menu;

    public $background = false;

    public function run()
    {
        return $this->render('MainDetails', [
            'title' => $this->title,
            'icon' => $this->icon,
            'subTitle' => $this->subTitle,
            'menu' => $this->menu,
            'image' => $this->image,
            'background' => $this->background,
        ]);
    }
}
