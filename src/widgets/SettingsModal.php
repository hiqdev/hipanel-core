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

use hipanel\base\Model;
use hipanel\helpers\FontIcon;
use yii\helpers\Html;

/**
 * Class SettingsModal. Render AjaxModal, created specially to render on view page.
 */
class SettingsModal extends AjaxModal
{
    public Model $model;

    /** Header and text of toggle link */
    public string $title = '';

    /** template for `label` attribute */
    public string $labelTemplate = '{icon} {label}';

    /**
     * icon class for toggle link
     * Will be passed through [[FontIcon::i()]] method
     */
    public ?string $icon = null;

    /** Priority text of toggle link */
    public ?string $toggleText = null;

    public function init()
    {
        $this->header = Html::tag('h4', $this->title, ['class' => 'modal-title']);
        $this->actionUrl = [$this->scenario, 'id' => $this->model->id];
        $this->icon = $this->icon ? FontIcon::i($this->icon) : '';
        $this->toggleText = $this->toggleText ?? $this->title;
        $this->toggleButton = array_merge([
            'tag' => 'a',
            'label' => strtr($this->labelTemplate, ['{icon}' => $this->icon, '{label}' => $this->toggleText]),
            'class' => 'clickable',
        ], is_array($this->toggleButton) ? $this->toggleButton : []);
        parent::init();
    }
}
