<?php

use hipanel\grid\ResourceGridView;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use yii\data\DataProviderInterface;
use yii\db\ActiveRecordInterface;
use yii\helpers\Html;

/** @var DataProviderInterface $dataProvider */
/** @var ActiveRecordInterface $originalModel */
/** @var ActiveRecordInterface $searchModel */
/** @var ActiveRecordInterface $model */

?>

<div class="row">
    <div class="col-md-6 col-sm-12">
        <?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => false])) ?>
            <?php $page = IndexPage::begin(['model' => $model, 'layout' => 'resourceDetail']) ?>
                <?php $page->beginContent('title') ?>
                    <?= Yii::t('hipanel', 'Resources') ?>
                <?php $page->endContent() ?>
                <?php $page->beginContent('actions') ?>
                    <?= Html::a(Yii::t('hipanel', 'Go to the object'), ['@server/view', 'id' => $originalModel->id]) ?>
                <?php $page->endContent() ?>
                <?php $page->beginContent('table') ?>
                    <?php $page->beginBulkForm() ?>
                        <?= ResourceGridView::widget([
                            'boxed' => false,
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'tableOptions' => [
                                'class' => 'table table-striped table-bordered',
                            ],
                            'columns' => [
                                'type',
                                'date',
                                'total',
                            ],
                        ]) ?>
                    <?php $page->endBulkForm() ?>
                <?php $page->endContent() ?>
            <?php IndexPage::end() ?>
        <?php Pjax::end() ?>
    </div>
    <div class="col-md-6 col-sm-12"></div>
</div>
