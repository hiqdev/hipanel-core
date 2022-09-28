<?php

use hipanel\grid\RefGridView;
use hipanel\modules\hipanel\grid\RefRepresentations;
use hipanel\widgets\IndexPage;

/**
 * @var \hiqdev\hiart\ActiveDataProvider $dataProvider
 * @var \yii\base\Model $model
 * @var \yii\base\View $this
 * @var \hipanel\models\IndexPageUiOptions $uiModel
 * @var RefRepresentations $representationCollection
 */

$this->title = Yii::t('hipanel', 'Refs');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $page = IndexPage::begin(['model' => $model, 'dataProvider' => $dataProvider]) ?>

    <?php $page->setSearchFormData(['uiModel' => $uiModel]) ?>

    <?php $page->beginContent('representation-actions') ?>
        <?= $page->renderRepresentations($representationCollection) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('table') ?>
    <?php $page->beginBulkForm() ?>
    <?= RefGridView::widget([
        'boxed' => false,
        'dataProvider' => $dataProvider,
        'filterModel'  => $model,
        'columns' => $representationCollection->getByName('common')->getColumns(),
    ]) ?>
    <?php $page->endBulkForm() ?>
    <?php $page->endContent() ?>

<?php $page->end() ?>
