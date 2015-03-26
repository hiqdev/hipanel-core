<?php

namespace frontend\components\grid;

use frontend\components\widgets\Select2;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;

class ClientColumn extends DataColumn
{
    public $attribute = 'client_id';

    public $nameAttribute = 'client';

    public $format = 'html';

    public $listAction = '/client/client/list';

    public $clientType = '';

    public function init () {
        parent::init();
        if (is_null($visible)) {
            $visible = \Yii::$app->user->identity->type!='client';
        };
        if (!$this->filterInputOptions['id']) {
            $this->filterInputOptions['id'] = $this->attribute;
        };
        if (!$this->filter) {
            $this->filter = Select2::widget([
                'attribute' => $this->getFilterAttribute(),
                'model'     => $this->grid->filterModel,
                'url'       => Url::toRoute([$this->listAction]),
                'settings'  => [
                    'ajax' => [
                        'data' => new JsExpression('function(term,page) { return {
                            "rename[text]": "login",
                            "wrapper":      "results",
                            "type":         "'.$this->clientType.'",
                            "client_like":  term
                        }; }'),
                    ],
                    'initSelection' => new JsExpression('function (elem, callback) {
                        var id=$(elem).val();
                        $.ajax("' . Url::toRoute(['/client/client/list']) . '?id=" + id, {
                            dataType: "json",
                            data : {"rename[text]":"login",wrapper:"results" }
                        }).done(function(data) {
                            callback(data.results[0]);
                        });
                    }'),
                ],
            ]);
        };
    }

    public function getDataCellValue ($model, $key, $index) {
        return Html::a($model->{$this->nameAttribute}, ['/client/client/view', 'id' => $model->{$this->attribute}]);
    }
}
