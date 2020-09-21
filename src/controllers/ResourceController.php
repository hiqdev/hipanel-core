<?php

namespace hipanel\controllers;

use DateTime;
use hipanel\actions\Action;
use hipanel\actions\IndexAction;
use hipanel\base\CrudController;
use hipanel\helpers\ResourceHelper;
use hipanel\models\Resource;
use hipanel\models\ResourceSearch;
use hipanel\modules\server\models\Server;
use hipanel\modules\server\models\ServerSearch;
use hipanel\modules\server\widgets\ResourceConsumption;
use http\Exception\RuntimeException;
use Yii;
use yii\base\DynamicModel;
use yii\base\Event;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ResourceController extends CrudController
{
    public function actions()
    {
        return [
            'servers' => [
                'class' => IndexAction::class,
                'searchModel' => ServerSearch::class,
                'view' => 'servers',
            ],
            'server' => [
                'class' => IndexAction::class,
                'searchModel' => ResourceSearch::class,
                'on beforePerform' => function (Event $event) {
                    $query = $event->sender->getDataProvider()->query;
                    $query->andWhere([
                        'time_from' => (new DateTime())->modify('first day of last month')->format('Y-m-d'),
                        'time_till' => (new DateTime())->modify('last day of last month')->format('Y-m-d'),
                    ]);
                    $query->andWhere([
                        'object_id' => $this->request->get('id'),
                        'groupby' => 'server_traf_day',
                    ]);
                },
                'view' => 'server',
                'data' => static fn(Action $action): array => [
                    'originalModel' => Server::findOne($action->controller->request->get('id')),
                ],
            ],
        ];
    }

    public function actionFetchResources()
    {
        $request = Yii::$app->request;
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        if ($request->isPost) {
            $resources = $this->getResources([
                'object_ids' => $request->post('object_ids'),
                'time_from' => $request->post('time_from'),
                'time_till' => $request->post('time_till'),
                'groupby' => 'server_traf_month',
            ]);

            return ResourceHelper::aggregateByObject($resources);
        }

        return [];
    }

    public function actionDrawChart()
    {
        $post = Yii::$app->request->post();
        $types = array_merge(['server_traf', 'server_traf95'], array_keys(ResourceConsumption::types()));
        if (!in_array($post['type'], $types, true)) {
            throw new NotFoundHttpException();
        }

        $searchModel = new ResourceSearch();
        $dataProvider = $searchModel->search([]);
        $dataProvider->pagination = false;
        $dataProvider->query->action('get-uses');
        $dataProvider->query->andWhere($post);
        $models = $dataProvider->getModels();

        [$labels, $data] = ResourceHelper::groupResourcesForChart($models);

        return $this->renderAjax('_chart', [
            'labels' => $labels,
            'data' => $data,
            'consumptionBase' => $post['type'],
        ]);
    }

    private function getResources(array $params): array
    {
        $options = DynamicModel::validateData([
            'object_ids' => $params['object_ids'],
            'time_from' => $params['time_from'] ?? (new DateTime())->modify('first day of last month')->format('Y-m-d'),
            'time_till' => $params['time_till'] ?? (new DateTime())->modify('last day of last month')->format('Y-m-d'),
            'groupby' => $params['groupby'],
        ], [
            [['object_ids', 'time_from', 'time_till', 'groupby'], 'required'],
            ['object_ids', 'string'],
            [['time_from', 'time_till'], 'datetime', 'format' => 'php:Y-m-d'],
            ['groupby', 'in', 'range' => ['server_traf_month', 'server_traf_week', 'server_traf_day']],
        ]);
        if ($options->hasErrors()) {
            throw new RuntimeException($options->getErrors()[0]);
        }

        return Resource::find()
            ->where([
                'object_id' => explode(',', $options->object_ids),
                'time_from' => $options->time_from,
                'time_till' => $options->time_till,
                'groupby' => $options->groupby,
            ])
            ->limit(-1)
            ->all();
    }
}
