<?php

use hipanel\modules\client\models\Client;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var Client $client
 */

$client = Client::find()
    ->where([
        'id' => Yii::$app->user->identity->id,
    ])
    ->addSelect(['purses'])
    ->withPurses()
    ->one();

if ($client->total_balance > 0) {
    $balanceColor = 'text-success';
} elseif ($client->total_balance < 0) {
    $balanceColor = 'text-danger';
} else {
    $balanceColor = 'text-muted';
}

$this->registerCss('
.user-panel { padding-bottom: 16px }
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
<?php if (is_countable($client->purses) && count($client->purses) > 1): ?>
    <?= Yii::t('adminlte', 'Total balance: {balance}', [
        'balance' => Yii::$app->formatter->asCurrency($client->total_balance, $client->currency),
    ]) ?>
<?php else: ?>
    <?= Yii::t('adminlte', 'Balance: {balance}', [
        'balance' => Yii::$app->formatter->asCurrency($client->total_balance, $client->currency),
    ]) ?>
<?php endif ?>

<?= Html::endTag('a') ?>
<br/>
<?php if (is_countable($client->purses) && count($client->purses) > 1): ?>
    <?php foreach ($client->purses ?? [] as $purse): ?>
        <?= Html::beginTag('a', array_merge([
            'href' => Yii::$app->user->can('deposit') ? Url::to('@pay/deposit') : '#',
        ], [])) ?>
            <i class="fa fa-circle <?= $balanceColor ?>"></i>
            <?= Yii::t('adminlte', 'Balance: {balance}', [
                'balance' => Yii::$app->formatter->asCurrency($purse->balance, $purse->currency),
            ]) ?>
        <?= Html::endTag('a') ?>
        <br/>
    <?php endforeach ?>
    <br />
<?php endif ?>
<?= Html::a(Yii::t('hipanel', 'User profile'), ['/site/profile']) ?>
