<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\widgets;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * GridActionButton displays link-buttons for GridView actions column
 *
 * Usage:
 * GridActionButton::widget([
 *      'url' => '/some/path/to',
 *      'icon' => '<i class="fa fa-icon"></i>',
 *      'label' => \Yii::t('app', 'Some label'),
 * ]);
 */
class GridActionButton extends Widget
{
    /**
     * @var string|array
     */
    public $url;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $icon;

    /**
     * @var int|bool 0 or 1
     */
    public $dataPjax = 0;

    /**
     * @var string
     */
    public $linkClasses = 'btn btn-default btn-xs';

    /**
     * @var array
     */
    public $buttonOptions = [];

    public function run () {
        parent::run();
        $this->renderButton();
    }

    private function renderButton () {
        $options = ArrayHelper::merge([
            'title'     => Html::encode($this->label),
            'class'     => $this->linkClasses,
            'data-pjax' => $this->dataPjax
        ], $this->buttonOptions);

        print Html::a(sprintf('%s&nbsp;&nbsp;%s', $this->icon, Html::encode($this->label)), $this->url, $options);
    }
}
