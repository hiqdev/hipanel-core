<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\grid;

use Yii;
use yii\helpers\Html;

class ActionColumn extends \yii\grid\ActionColumn
{
    public $attribute;

    /**
     * @var
     */
    public $buttonOptions = [];

    /**
     * @inheritdoc
     */
    public function init () {
        parent::init();
        $this->getCountButtons();
        $this->template = ($this->getCountButtons() > 1) ? '<div class="btn-group btn-group-fix">' . $this->template . '</ul></div>' : '<div class="btn-group btn-group-fix">' . $this->template . '</div>';
    }

    public function getCountButtons () {
        return preg_match_all('/\\{([\w\-\/]+)\\}/', $this->template);
    }

    public function renderFirstButton ($item) {
        return ($this->getCountButtons() > 1) ? $item . '<button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu pull-right" role="menu">' : $item;
    }

    public function renderOtherButtons ($item) {
        return '<li>' . $item . '</li>';
    }

    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons () {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title'      => Yii::t('app', 'View'),
                    'aria-label' => Yii::t('app', 'View'),
                    'data-pjax'  => '0',
                    'class'      => 'btn btn-default btn-xs',
                ], $this->buttonOptions);

                return Html::a('<i class="fa fa-eye"></i>&nbsp;' . Yii::t('app', 'View'), $url, $options);
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title'      => Yii::t('app', 'Update'),
                    'aria-label' => Yii::t('app', 'Update'),
                    'data-pjax'  => '0',
                ], $this->buttonOptions);

                return Html::a('<i class="fa fa-pencil"></i>' . Yii::t('app', 'Update'), $url, $options);
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title'        => Yii::t('app', 'Delete'),
                    'aria-label'   => Yii::t('app', 'Delete'),
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method'  => 'POST',
                        'data-pjax' => '0',
                    ],

                ], $this->buttonOptions);

                return Html::a('<i class="fa fa-trash-o"></i>' . Yii::t('app', 'Delete'), $url, $options);
            };
        }
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent ($model, $key, $index) {
        return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($model, $key, $index) {
            static $isFirst = true;
            $name = $matches[1];
            if (isset($this->buttons[$name])) {
                $url          = $this->createUrl($name, $model, $key, $index);
                $renderedItem = call_user_func($this->buttons[$name], $url, $model, $key);
                $result       = ($isFirst == true) ? $this->renderFirstButton($renderedItem) : $this->renderOtherButtons($renderedItem);
                $isFirst      = false;

                return $result;
            } else {
                return '';
            }

        }, $this->template);
    }
}
