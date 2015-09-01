<?php

namespace hipanel\actions;

use Closure;
use hipanel\base\Model;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class IndexAction
 *
 */
class IndexAction extends SearchAction
{
    /**
     * @var string view to render.
     */
    public $view = 'index';

    /**
     * @var array|Closure additional data passed to view
     */
    public $data = [];

    public function init()
    {
        $this->addItems([
            'html | pjax' => [
                'save' => false,
                'flash' => false,
                'success' => [
                    'class' => 'hipanel\actions\RenderAction',
                    'view' => $this->view,
                    'params' => function () {
                        return ArrayHelper::merge([
                            'model' => $this->getSearchModel(),
                            'dataProvider' => $this->getDataProvider(),
                        ], $this->prepareData());
                    }
                ]
            ]
        ]);

        parent::init();

        $this->dataProviderOptions = ArrayHelper::merge($this->dataProviderOptions, [
            'pagination' => [
                'pageSize' => Yii::$app->request->get('per_page') ?: 25
            ]
        ]);
    }

    public function prepareData()
    {
        if ($this->data instanceof Closure) {
            return call_user_func($this->data, $this);
        } else {
            return (array)$this->data;
        }
    }
}
