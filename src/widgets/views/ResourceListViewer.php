<?php

use hipanel\grid\ResourceGridView;
use hipanel\helpers\ResourceConfigurator;
use hipanel\models\IndexPageUiOptions;
use hipanel\modules\server\grid\ServerGridView;
use hipanel\widgets\IndexPage;
use yii\base\ViewContextInterface;
use yii\data\DataProviderInterface;
use yii\db\ActiveRecordInterface;

/** @var DataProviderInterface $dataProvider */
/** @var ViewContextInterface $originalContext */
/** @var ActiveRecordInterface $model */
/** @var ActiveRecordInterface $searchModel */
/** @var IndexPageUiOptions $uiModel */
/** @var ResourceConfigurator $configurator */

?>

<?php $page = IndexPage::begin([
    'model' => $configurator->getSearchModel(),
    'dataProvider' => $dataProvider,
    'originalContext' => $originalContext,
    'searchView' => $configurator->getSearchView(),
]) ?>
    <?php $page->setSearchFormOptions(['action' => ['servers']]) ?>
    <?php $page->setSearchFormData(['uiModel' => $uiModel]) ?>
    <?php $page->beginContent('sorter-actions') ?>
        <?= $page->renderSorter([
            'attributes' => ['id']
        ]) ?>
    <?php $page->endContent() ?>
    <?php $page->beginContent('actions') ?>
        <div class="form-group has-feedback">
            <input type="text" class="form-control" id="date-range" name="date-range"/>
            <span class="glyphicon glyphicon-calendar form-control-feedback text-muted" aria-hidden="true"></span>
        </div>
    <?php $page->endContent() ?>
    <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm() ?>
            <?= call_user_func([$configurator->getGridClassName(), 'widget'], [
                'boxed' => false,
                'dataProvider' => $dataProvider,
                'filterModel' => $configurator->getSearchModel(),
                'columns' => ResourceGridView::getColumns($configurator),
            ]) ?>
        <?php $page->endBulkForm() ?>
    <?php $page->endContent() ?>
<?php $page::end() ?>
