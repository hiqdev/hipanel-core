<?php

use hipanel\grid\RefGridView;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;

/**
 * @var \hiqdev\hiart\ActiveDataProvider $dataProvider
 * @var \yii\base\Model $model
 * @var \yii\base\View $this
 */

$this->title = Yii::t('hipanel', 'Refs');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
    <?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>

        <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm() ?>
        <?= RefGridView::widget([
            'boxed' => false,
            'dataProvider' => $dataProvider,
            'filterModel'  => $model,
            'columns' => [
                'name', 'label',
            ],
        ]) ?>
        <?php $page->endBulkForm() ?>
        <?php $page->endContent() ?>

    <?php $page->end() ?>
<?php Pjax::end() ?>
