<?php
use kartik\builder\Form;
use yii\helpers\Html;
use yii\widgets\Pjax;

Pjax::begin(['timeout' => 0, 'enablePushState' => false]);

//\yii\helpers\VarDumper::dump($model, 10, true);

if ($model->vnc['enabled']) {
    echo Html::tag('span',
        Html::tag('i', '', ['class' => 'glyphicon glyphicon-ok']) . ' ' . Yii::t('app', 'Enabled'),
        ['class' => 'label label-success']);

    $fields = [
        'IP'       => $model->vnc['vnc_ip'],
        'Port'     => $model->vnc['vnc_port'],
        'Password' => $model->vnc['vnc_password']
    ];
    ?>
    <dl class="dl-horizontal">
        <?php foreach ($fields as $name => $value) { ?>
            <dt><?= $name ?></dt>
            <dd><?= $value ?></dd>
        <?php
        } ?>
    </dl>
    <?php
    echo Yii::t('app', 'VNC will be disabled ') ?> <?= \Yii::$app->formatter->asRelativeTime($model->vnc['endTime']);
} else {
    echo Html::a(
        Yii::t('app', 'Enable'),
        ['enable-vnc', 'id' => $model->id],
        [
            'class'             => 'btn btn-success',
            'data-loading-text' => Yii::t('app', 'Enabling...'),
            'onClick'           => new \yii\web\JsExpression("$(this).button('loading')")
        ]
    );
}

Pjax::end();
