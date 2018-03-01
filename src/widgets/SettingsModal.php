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

use hipanel\base\Model;
use hipanel\helpers\FontIcon;
use yii\helpers\Html;

/**
 * Class SettingsModal. Render AjaxModal, created specially to render on view page.
 */
class SettingsModal extends AjaxModal
{
    /**
     * @var Model
     */
    public $model;

    /**
     * @var string text of toggle link
     */
    public $title;

    /**
     * @var string template for `label` attribute
     */
    public $labelTemplate = '{icon} {label}';

    /**
     * @var string icon class for toggle link
     * Will be passed through [[FontIcon::i()]] method
     */
    public $icon;

    public function init()
    {
        $this->header = Html::tag('h4', $this->title, ['class' => 'modal-title']);
        $this->actionUrl = [$this->scenario, 'id' => $this->model->id];
        $this->toggleButton = array_merge([
            'tag' => 'a',
            'label' => strtr($this->labelTemplate, ['{icon}' => FontIcon::i($this->icon), '{label}' => $this->title]),
            'class' => 'clickable',
        ], is_array($this->toggleButton) ? $this->toggleButton : []);
        parent::init();
    }
}
