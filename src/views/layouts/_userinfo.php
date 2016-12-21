<?php

use hipanel\modules\client\models\Client;
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
</a>
<?php if ($client->credit > 0) : ?>
    <br />
    <a href="<?= Yii::$app->user->can('deposit') ? Url::to('@pay/deposit') : '#' ?>">
        <i class="fa fa-circle"></i> <?= Yii::t('adminlte', 'Credit: {credit}', ['credit' => Yii::$app->formatter->asCurrency($client->credit, 'USD')]) ?>
    </a>
<?php endif ?>
