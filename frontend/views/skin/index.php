<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 06.03.15
 * Time: 18:56
 */
use yii\helpers\Html;

$this->title = \Yii::t('app', 'Layout Settings');
$this->params['breadcrumbs'][] = ['url' => '/hipanel', 'label' => \Yii::t('app', 'Dashboard')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-6">
        <div class="box">
            <div class="box-header">
                <?= Html::a('Admin LTE 1', ['/skin/change-theme', 'theme' => 'adminlte'], ['class' => 'btn btn-default']); ?>&nbsp;
                <?= Html::a('Admin LTE 2', ['/skin/change-theme', 'theme' => 'adminlte2'], ['class' => 'btn btn-default']); ?>
            </div>
            <div class="box-body">
                <?= $this->render('_form', ['model' => $model]); ?>
            </div>
        </div>
    </div>
</div>