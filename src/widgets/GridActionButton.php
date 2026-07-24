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

use hipanel\helpers\ArrayHelper;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * GridActionButton displays link-buttons for GridView actions column.
 *
 * Usage:
 * GridActionButton::widget([
 *      'url' => '/some/path/to',
 *      'icon' => '<i class="fa fa-icon"></i>',
 *      'label' => Yii::t('hipanel', 'Some label'),
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

    public function run()
    {
        parent::run();
        $this->renderButton();
    }

    private function renderButton()
    {
        $options = ArrayHelper::merge([
            'title'     => Html::encode($this->label),
            'class'     => $this->linkClasses,
            'data-pjax' => $this->dataPjax,
        ], $this->buttonOptions);

        echo Html::a(sprintf('%s&nbsp;&nbsp;%s', $this->icon, Html::encode($this->label)), $this->url, $options);
    }
}
