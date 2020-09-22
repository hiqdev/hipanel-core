<?php

use hipanel\grid\ResourceGridView;
use hipanel\helpers\ResourceConfigurator;
use hipanel\models\Resource;
use hipanel\models\ResourceSearch;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use yii\data\DataProviderInterface;
use yii\db\ActiveRecordInterface;
use yii\helpers\Html;

/** @var DataProviderInterface $dataProvider */
/** @var ActiveRecordInterface $originalModel */
/** @var ResourceConfigurator $configurator */

?>

<div class="row">
    <div class="col-md-6 col-sm-12">
            <?php $page = IndexPage::begin(['model' => new Resource(), 'layout' => 'resourceDetail']) ?>
                <?php $page->beginContent('title') ?>
                    <?= Yii::t('hipanel', 'Resources') ?>
                <?php $page->endContent() ?>
                <?php $page->beginContent('actions') ?>
                    <?= Html::a(Yii::t('hipanel', 'Go to the object'), [$configurator->getModelName(), 'id' => $originalModel->id], ['class' => 'btn btn-success btn-sm']) ?>
                <?php $page->endContent() ?>
                <?php $page->beginContent('table') ?>
                    <?php $page->beginBulkForm() ?>
                        <?= ResourceGridView::widget([
                            'boxed' => false,
                            'configurator' => $configurator,
                            'dataProvider' => $dataProvider,
                            'filterModel' => new ResourceSearch(),
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
        <?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => false])) ?>
        <?php Pjax::end() ?>
    </div>
</div>
