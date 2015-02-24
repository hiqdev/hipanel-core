<?php
use yii\helpers\Html;
use yii\widgets\Pjax;

/**
 * @var \frontend\modules\server\models\Server $model
 */


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
    echo ($hide_leftTime ? '' : Yii::t('app', 'VNC will be disabled ') . \Yii::$app->formatter->asRelativeTime($model->vnc['endTime']));
} else {
    echo Html::beginForm(['enable-vnc', 'id' => $model->id], "POST", ['data' => ['pjax' => 1], 'class' => 'inline']);
    echo Html::submitButton(
        Yii::t('app', 'Enable'),
        [
            'class'             => 'btn btn-success',
            'data-loading-text' => Yii::t('app', 'Enabling...'),
            'onClick'           => new \yii\web\JsExpression("$(this).closest('form').submit(); $(this).button('loading')"),
            'disabled'          => !$model->isOperable() || !$model->isVNCSupported(),
        ]
    );
    echo ' ';
    if (!$model->isVNCSupported()) {
        echo Yii::t('app', 'VNC is supported only on XEN');
    }
    echo Html::endForm();
}
