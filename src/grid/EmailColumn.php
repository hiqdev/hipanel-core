<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\grid;

use hipanel\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;

class EmailColumn extends DataColumn
{
    public function init()
    {
        parent::init();
        \Yii::configure($this, [
            'attribute'             => 'email',
            'label'                 => \Yii::t('app', 'Email'),
            'format'                => 'html',
            'value'                 => function ($model) {
                return Html::a($model->client ?: $model->login, ['/client/contact/view', 'id' => $model->client ? $model->client_id : $model->id]);
            },
            'filterInputOptions'    => ['email' => 'email'],
            'filter'                => Select2::widget([
                'attribute' => 'email',
                'model'     => $this->grid->filterModel,
                'url'       => Url::toRoute(['/client/contact/email-list']),
            ]),
        ]);
    }
}
