<?php
namespace frontend\components\widgets;

use yii\helpers\Html;
use Yii;
/**
 * Class Box
 * @package vova07\themes\admin\widgets
 * Theme Box widget.
 */
class HiBox extends Box
{

    protected function initButtons()
    {
        parent::initButtons();
        if (!isset($this->buttons['collapse'])) {
            $this->buttons['collapse'] = [
                'icon' => 'fa-minus',
                'options' => [
                    'class' => 'btn-default',
                    'title' => Yii::t('app', 'collapse'),
                    'data-widget'=>'collapse',
                ]
            ];
        }
        if (!isset($this->buttons['remove'])) {
            $this->buttons['remove'] = [
                'icon' => 'fa-times',
                'options' => [
                    'class' => 'btn-default',
                    'title' => Yii::t('app', 'remove'),
                    'data-widget'=>'remove',
                ]
            ];
        }
    }
    /**
     * Render widget tools button.
     */
    protected function renderButtons()
    {
        // Box tools
        if ($this->buttonsTemplate !== null && !empty($this->buttons)) {
            // Begin box tools
            echo Html::beginTag('div', ['class' => 'box-tools pull-right']);
            echo preg_replace_callback(
                '/\\{([\w\-\/]+)\\}/',
                function ($matches) {
                    $name = $matches[1];
                    if (isset($this->buttons[$name])) {
                        $label = isset($this->buttons[$name]['label']) ? $this->buttons[$name]['label'] : '';

                        $icon = isset($this->buttons[$name]['icon']) ? Html::tag(
                                                                           'i',
                                                                               '',
                                                                               ['class' => 'fa ' . $this->buttons[$name]['icon']]
                        ) : '';
                        $label = $icon . ' ' . $label;
                        $this->buttons[$name]['options']['class'] = isset($this->buttons[$name]['options']['class']) ? 'btn btn-sm ' . $this->buttons[$name]['options']['class'] : 'btn btn-sm';
                        return Html::button($label, $this->buttons[$name]['options']);
                    } else {
                        return '';
                    }
                },
                $this->buttonsTemplate
            );
            // End box tools
            echo Html::endTag('div');
        }
    }
}