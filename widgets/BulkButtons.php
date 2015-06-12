<?php

namespace hipanel\widgets;

use hipanel\helpers\ArrayHelper;
use yii\base\Widget;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Html;
use yii\helpers\Json;

class BulkButtons extends Widget
{
    /**
     * @var [[\yii\base\Model]]
     */
    public $model;

    public $modelName;

    public $modelPk;

    public $items = [];

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
            $( ".bulk-action" ).on( "click", function(event) {
                event.preventDefault();
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
                //console.log( data );

                if ($.support.pjax) {
                    var container = $(this).closest('[data-pjax-contaiter]');
                    if (container) {
                        $.pjax({container: container, data: {'$modelFormName': data}, url: url, type: 'POST'})
                    }
                } else {
                        jQuery.ajax({
                        type: 'POST',
                        dataType: 'json',
                        data: {'$modelFormName': data},
                        url: url
                    });
                }
            });
JS
        );
    }

    protected function renderHtml()
    {
        return implode('&nbsp;', $this->items);
    }
}

