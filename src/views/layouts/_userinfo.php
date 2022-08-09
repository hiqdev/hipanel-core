<?php

use hipanel\modules\client\models\Client;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var Client $client
 */

if (Yii::$app->user->identity !== null) {
    $client = Client::findOne(Yii::$app->user->identity->id);
}

?>
<?php if (($client ?? null) !== null) : ?>
    <?php
    if ($client->balance > 0) {
        $balanceColor = 'text-success';
    } elseif ($client->balance < 0) {
        $balanceColor = 'text-danger';
    } else {
        $balanceColor = 'text-muted';
    }

    $this->registerCss('
        .user-panel > .info { padding-top: 0; }
        .user-panel > .info a:first-child { font-size: 16px; }
    ');
    $tooltip = [];
    if ($client->credit >= 0) {
        $tooltip = [
            'data' => [
                'toggle' => 'tooltip',
                'placement' => 'bottom',
                'title' => Yii::t('adminlte', 'Credit: {credit}', [
                    'credit' => Yii::$app->formatter->asCurrency($client->credit, $client->currency)
                ]),
            ],
        ];
    }

    ?>
    <?= Html::beginTag('a', array_merge([
        'href' => Yii::$app->user->can('deposit') ? Url::to('@pay/deposit') : '#',
    ], $tooltip)) ?>

    <i class="fa fa-circle <?= $balanceColor ?>"></i>
    <?= Yii::t('adminlte', 'Balance: {balance}', [
        'balance' => Yii::$app->formatter->asCurrency($client->balance, $client->currency),
    ]) ?>

    <?= Html::endTag('a') ?>
    <br/>
    <?= Html::a(Yii::t('hipanel', 'User profile'), ['/site/profile']) ?>
<?php endif ?>
