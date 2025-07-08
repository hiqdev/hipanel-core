<?php

declare(strict_types=1);

namespace hipanel\controllers;

use hipanel\filters\EasyAccessControl;
use hipanel\hiart\hiapi\HiapiConnectionInterface;
use hiqdev\hiart\ResponseInterface;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Application;
use yii\web\Controller;

class AuditController extends Controller
{
    public function __construct(
        $id,
        Application $application,
        private readonly HiapiConnectionInterface $api,
        array $config = []
    )
    {
        parent::__construct($id, $application, $config);
    }

    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => EasyAccessControl::class,
                'actions' => [
                    '*' => [
                        'audit.read',
                    ],
                ],
            ],
        ]);
    }

    public function actionIndex(string $table, string $id): string
    {
        $data = $this->prepareData($this->api->get(sprintf('api/v1/audit/table/public/%s/%s', $table, $id)));

        return $this->render('index', [
            'table' => $table,
            'id' => $id,
            'data' => $data,
        ]);
    }

    public function actionDiff(string $table, string $id, string $version): string
    {
        $data = $this->prepareData($this->api->get(sprintf('api/v1/audit/diff/public/%s/%s/%s', $table, $id, $version)));

        return $this->render('index', [
            'table' => $table,
            'id' => $id,
            'data' => $data,
        ]);
    }

    public function actionTrace(string $id): string
    {
        $data = $this->prepareData($this->api->get(sprintf('api/v1/audit/trace/%s', $id)));

        return $this->render('index', [
            'id' => $id,
            'data' => $data,
        ]);
    }

    private function prepareData(ResponseInterface $response): string
    {
        $data = $response->getData();
        foreach ($data as $k => &$datum) {
            $datum['key'] = $k;
            $datum['link'] = Url::to(
                [
                    '@audit/index',
                    'table' => $datum['table'],
                    'id' => $datum['entity_id'],
                    '#' => $datum['id'],
                ]
            );
            $datum['user']['link'] = Url::toRoute(['@client/view', 'id' => $datum['user']['id']]);
            $datum['request']['link'] = Url::toRoute(['@audit/trace', 'id' => $datum['request']['trace_id']]);
        }

        return Json::encode($data);
    }
}
