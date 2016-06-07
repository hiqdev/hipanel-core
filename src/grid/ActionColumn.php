<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\grid;

use hiqdev\higrid\FeaturedColumnTrait;
use Yii;
use yii\helpers\Html;

class ActionColumn extends \yii\grid\ActionColumn
{
    use FeaturedColumnTrait {
        init as traitInit;
    }

    public $attribute;

    /**
     * @var
     */
    public $buttonOptions = [];

    /**
     * @var integer count of visible buttons that will be shown without spoiler
     */
    public $visibleButtonsCount = 1;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if ($this->header === null) {
            $this->header = Yii::t('hipanel', 'Actions');
        }
        $this->traitInit();
        $this->registerBtnGroupDirectionFix();
        $this->getCountButtons();
        $this->template = ($this->getCountButtons() > $this->visibleButtonsCount) ? '<div class="btn-group btn-group-fix">' . $this->template . '</ul></div>' : '<div class="btn-group btn-group-fix">' . $this->template . '</div>';
    }

    public function getCountButtons()
    {
        return preg_match_all('/\\{([\w\-\/]+)\\}/', $this->template);
    }

    public function registerBtnGroupDirectionFix()
    {
        Yii::$app->view->registerJs('
        $(function () {
            var $gridRows = $( ".btn-group.btn-group-fix" );
            if ($gridRows.length > 2) {
                $gridRows.slice(-2).addClass("dropup");
            } else {
                $gridRows.parents("table").eq(0).css({"height": "150pt"});
            }
        });
        ');
    }

    public function renderFirstButton($item, $index)
    {
        return ($this->getCountButtons() > $this->visibleButtonsCount) ? $item . '<button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu pull-right" role="menu">' : $item;
    }

    public function renderOtherButtons($item)
    {
        return '<li>' . $item . '</li>';
    }

    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title'      => Yii::t('app', 'Details'),
                    'aria-label' => Yii::t('app', 'Details'),
                    'data-pjax'  => '0',
                    'class'      => 'btn btn-default btn-xs',
                ], $this->buttonOptions);

                return Html::a('<i class="fa fa-bars"></i>&nbsp;' . Yii::t('app', 'Details'), $url, $options);
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title'      => Yii::t('app', 'Update'),
                    'aria-label' => Yii::t('app', 'Update'),
                    'data-pjax'  => '0',
                ], $this->buttonOptions);

                return Html::a('<i class="fa fa-pencil"></i>&nbsp;' . Yii::t('app', 'Update'), $url, $options);
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

                return Html::a('<i class="fa fa-trash-o"></i>&nbsp;' . Yii::t('app', 'Delete'), $url, $options);
            };
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($model, $key, $index) {
            static $renderedCount = 0;
            $name = $matches[1];

            if (isset($this->visibleButtons[$name])) {
                $isVisible = $this->visibleButtons[$name] instanceof \Closure
                    ? call_user_func($this->visibleButtons[$name], $model, $key, $index)
                    : $this->visibleButtons[$name];
            } else {
                $isVisible = true;
            }

            if ($isVisible && isset($this->buttons[$name])) {
                $url          = $this->createUrl($name, $model, $key, $index);
                $renderedItem = call_user_func($this->buttons[$name], $url, $model, $key);
                $result       = ($renderedCount < $this->visibleButtonsCount) ? $this->renderFirstButton($renderedItem, $index) : $this->renderOtherButtons($renderedItem);
                ++$renderedCount;

                return $result;
            } else {
                return '';
            }
        }, $this->template);
    }
}
