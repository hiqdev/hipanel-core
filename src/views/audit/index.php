<?php

use hipanel\assets\audit\AuditAsset;
use yii\web\View;

/**
 * @var View $this
 * @var string|null $table
 * @var string $id
 * @var string $data // json data
 */

$this->title = Yii::t('hipanel', 'History log');
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['subtitle'] = sprintf('%s %s', $table ?? '', $id);

$this->registerJsVar('__audit_data__', $data, View::POS_HEAD);
AuditAsset::register($this);

?>

<div id="app"></div>
