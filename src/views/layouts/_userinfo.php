<?php

use hipanel\modules\client\models\Client;
use yii\helpers\Html;
use yii\helpers\Url;

$client = Client::findOne(Yii::$app->user->identity->id);

if ($client->balance > 0) {
    $balanceColor = 'text-success';
} elseif ($client->balance < 0) {
    $balanceColor = 'text-danger';
} else {
    $balanceColor = 'text-muted';
}

?>

<a href="<?= Yii::$app->user->can('deposit') ? Url::to('@pay/deposit') : '#' ?>">
    <i class="fa fa-circle <?= $balanceColor ?>"></i> <?= Yii::t('adminlte', 'Balance: {balance}', ['balance' => Yii::$app->formatter->asCurrency($client->balance, 'USD')]) ?>
    <?php if ($client->credit > 0) : ?>
        <?= Yii::t('adminlte', '({credit})', ['credit' => Yii::$app->formatter->asCurrency($client->credit, 'USD')]) ?>
    <?php endif ?>
</a>
<br />
<?= Html::a('<i class="fa fa-circle"></i> ' . Yii::t('hipanel', 'User profile'), ['/site/profile']) ?>
