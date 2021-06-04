<?php
declare(strict_types=1);

namespace hipanel\controllers;

use hipanel\actions\IndexAction;
use hipanel\base\CrudController;
use hipanel\models\Ref;
use hipanel\models\RefSearch;
use hipanel\modules\client\models\query\ClientQuery;
use yii\base\Event;
use yii\filters\AccessControl;

class RefController extends CrudController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'loginRequired' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ]);
    }

    public function actions()
    {
        return array_merge(parent::actions(), [
            'index' => [
                'class' => IndexAction::class,
                'on beforePerform' => function (Event $event) {
                    /** @var ClientQuery $query */
                    $query = $event->sender->getDataProvider()->query;

                    $query->andWhere(['with_hierarchy' => true]);
                    $query->andWhere(['gtype' => RefSearch::DEFAULT_SEARCH_ATTRIBUTE]);
                },
            ],
        ]);
    }

    public function actionGetChild(string $parent)
    {
        return json_encode(Ref::getList($parent));
    }
}
