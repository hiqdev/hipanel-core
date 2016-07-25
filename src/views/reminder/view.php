<?php

use hipanel\grid\ReminderGridView;
use hipanel\widgets\Box;

$this->title = Yii::t('hipanel/reminder', "{0} ID #{1}", [Yii::t('hipanel/reminder', ucfirst($model->objectName)), $model->object_id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel/hosting', 'IP addresses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-md-12">
        <?php
        $box = Box::begin();
        echo ReminderGridView::detailView([
            'boxed' => false,
            'model' => $model,
            'columns' => [
                'periodicity',
                'message',
                'next_time',
            ],
        ]);
        $box->end();
        ?>
    </div>
</div>
