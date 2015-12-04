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

    public function init()
    {
        $this->addItems([
            'html | pjax' => [
                'save' => false,
                'flash' => false,
                'success' => [
                    'class'  => RenderAction::class,
                    'view'   => $this->view,
                    'data'   => $this->data,
                    'params' => function () {
                        return [
                            'model'        => $this->getSearchModel(),
                            'dataProvider' => $this->getDataProvider(),
                        ];
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

}
