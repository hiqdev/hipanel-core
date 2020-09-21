<?php

namespace hipanel\actions;

use hipanel\modules\server\models\ServerSearch;
use Yii;

class ResourceListAction extends Action
{
    public string $modelClass;

    public string $modelSearchClass;

    public function run()
    {
        $action = Yii::$container->get(IndexAction::class, ['servers', $this->controller], [
            'originalContext' => $this->controller,
        ]);
        $action->setSearchModel(new ServerSearch());
        $action->perform();
//        $action->run();

        return $this->controller->render($this->id, [
            'dataProvider' => $action->getDataProvider(),
            'uiModel' => $action->getUiModel(),
            'searchModel' => $action->getSearchModel(),
        ]);
    }
}