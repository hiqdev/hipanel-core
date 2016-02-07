<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\actions;

use hipanel\widgets\RequestState;
use yii\base\Action;

class RequestStateAction extends Action
{
    /**
     * @var \hiqdev\hiart\ActiveRecord
     */
    public $model;

    public function init()
    {
        if (!$this->model) {
            $this->model = $this->controller->newModel();
        }
    }

    public function run(array $ids)
    {
        $data = $this->model->find()->where(['id' => $ids, 'with_request' => true])->all();

        foreach ($data as $item) {
            $res[$item->id] = [
                'id'   => $item->id,
                'name' => $item->name,
                'html' => RequestState::widget([
                    'module' => 'server',
                    'model'  => $item,
                ]),
            ];
        }

        return $this->controller->renderJson($res);
    }
}
