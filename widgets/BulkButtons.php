<?php

namespace hipanel\widgets;

use hipanel\helpers\ArrayHelper;
use yii\base\Widget;
use yii\helpers\Html;

class BulkButtons extends Widget
{
    public $items = [];

    /**
     * @var [[\yii\base\Model]]
     */
    public $model;

    private $defaultHtmlOptions = ['class' => 'btn btn-default'];

    public function init()
    {

    }

    public function run()
    {
        $this->registerClintScripts();
        return $this->renderHtml();
    }

    private function registerClintScripts()
    {
        $view = $this->getView();
        $modelFormName = $this->model->formName();
        $modelPk = reset($this->model->primaryKey());

        $view->registerJs(<<<JS
            $( "button.bulk-buttons" ).on( "click", function() {
                var data = [],
                    attribute = $(this).data('attribute'),
                    value = $(this).data('value'),
                    url = $(this).data('url'),
                    keys = $( $('div[role=grid]') ).yiiGridView('getSelectedRows');
                jQuery.each(keys, function(k, id) {
                    var item = {};
                    item['$modelPk'] = id;
                    item[attribute] = value;
                    data.push(item);
                });
                console.log( data );
                jQuery.ajax({
                    type: 'POST',
                    data: {'$modelFormName': data},
                    url: url
                });
            });
JS
        );
    }

    private function renderHtml()
    {
        $result = '';
        foreach ($this->items as $item) {
            $htmlOptions =  ArrayHelper::merge($this->defaultHtmlOptions, $item['options'], [
                'data-attribute' => $item['attribute'],
                'data-value' => $item['value'],
                'data-url' => $item['url'],
            ]);

            // Applay default css class
            $htmlOptions['class'] = $htmlOptions['class'] . ' bulk-buttons';

            $result .= Html::button($item['label'], $htmlOptions) . '&nbsp;';
        }
        return rtrim($result, '&nbsp;');
    }
}

