<?php

use yii\helpers\Html;

?>

<?php if (Yii::$app->user->can('deposit')) : ?>
    <div class="col-xs-6 text-center">
        <?= Html::a(Yii::t('adminlte', 'Recharge account'), ['@pay/deposit'], ['class' => 'btn btn-default btn-xs btn-flat']) ?>
    </div>
<?php endif ?>
<div class="col-xs-6 text-center">
    <?= Html::a(Yii::t('adminlte', 'Create ticket'), ['@ticket/create'], ['class' => 'btn btn-default btn-xs btn-flat']) ?>
</div>
