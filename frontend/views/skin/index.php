<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 06.03.15
 * Time: 18:56
 */
$this->title = \Yii::t('app', 'Layout Settings');
$this->params['breadcrumbs'][] = ['url' => '/hipanel', 'label' => \Yii::t('app', 'Dashboard')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-6">
        <div class="box">
            <div class="box-body">
                <?= $this->render('_form', ['model' => $model]); ?>
            </div>
        </div>
    </div>
</div>