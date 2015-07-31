<?php

namespace hipanel\actions;

use Yii;

/**
 * Class SmartDeleteAction
 */
class SmartDeleteAction extends SwitchAction
{
    public function loadCollection($data = null)
    {
        if (is_null($data)) {
            $data = \Yii::$app->request->post();
        }
        foreach ($data['selection'] ?: [$data['id']] as $id) {
            $rows[] = compact('id');
        }
        return parent::loadCollection($rows);
    }

    public function init()
    {
        parent::init();
        $this->setItems([
            'POST' => [
                'save'    => true,
                'success' => [
                    'class' => 'hipanel\actions\RedirectAction',
                    'url'   => ['index'],
                ],
                'error'   => [
                    'class' => 'hipanel\actions\RedirectAction',
                    'url'   => ['index'],
                ],
            ],
            'GET' => [
                'class' => 'hipanel\actions\RedirectAction',
                'url'   => ['index'],
            ],
        ]);
    }
}
