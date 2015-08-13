<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\grid;

use hipanel\widgets\Select2;
use yii\helpers\Url;
use yii\helpers\Html;

class EmailColumn extends DataColumn
{
    public function init () {
        parent::init();
        \Yii::configure($this,[
            'attribute'             => 'email',
            'label'                 => \Yii::t('app', 'Email'),
            'format'                => 'html',
            'value'                 => function ($model) {
                return Html::a($model->client ? : $model->login, ['/client/contact/view', 'id' => $model->client ? $model->client_id : $model->id ]);
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
