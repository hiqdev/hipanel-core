<?php

namespace frontend\components\actions;

use frontend\components\widgets\RequestState;
use yii\base\Action;

class RequestStateAction extends Action {

    /**
     * @var \frontend\components\hiresource\ActiveRecord
     */
    public $model;

    public function run (array $ids) {
        $model = $this->model;
        $data = $model::find()->where(['id' => $ids, 'with_request' => true])->all();

        foreach ($data as $item) {
            $res[$item->id] = [
                'id'   => $item->id,
                'name' => $item->name,
                'html' => RequestState::widget([
                    'module' => 'server',
                    'model'  => $item
                ])
            ];
        }

        return $this->controller->renderJson($res);
    }
}