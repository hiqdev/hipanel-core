<?php

namespace hipanel\actions;

class ResourceListAction extends IndexAction
{
    public string $model;

    public function init()
    {
        $this->data = fn(Action $action): array => [
            'originalModel' => new $this->model,
        ];
        parent::init();
    }
}
