<?php
use frontend\components\widgets\Box;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = \yii\helpers\Inflector::titleize($model->name, true);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Clients'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//\yii\helpers\VarDumper::dump($model, 10, true);
?>

<div class="row">
    <div class="col-md-3">
        <?php Box::begin(); ?>
        <div class="profile-user-img text-center">
            <?= $this->render('//layouts/gravatar', ['email' => $model->email, 'size' => 120]); ?>
        </div>
        <p class="text-center">
            <span class="profile-user-name"><?= $this->title; ?></span>
            <br>
            <span class="profile-user-role"><?= $model->type; ?></span
        </p>
        <div class="profile-usermenu">
            <ul class="nav">
                <li>
                    <?= Html::a('<i class="ion-unlocked"></i>'.Yii::t('app', 'Change your password'), '#'); ?>
                </li>
                <li>
                    <?= Html::a('<i class="ion-network"></i>'.Yii::t('app', 'Changing the IP address restrictions'), '#'); ?>
                </li>
                <li>
                    <?= Html::a('<i class="ion-lock-combination"></i>'.Yii::t('app', 'Pincode'), '#'); ?>
                </li>
                <li>
                    <?= Html::a('<i class="ion-at"></i>'.Yii::t('app', 'Mailings'), '#'); ?>
                </li>
                <li>
                    <?= Html::a('<i class="ion-person"></i>'.Yii::t('app', 'Change reseller'), '#'); ?>
                </li>
                <li>
                    <?= Html::a('<i class="ion-compose"></i>'.Yii::t('app', 'Change login'), '#'); ?>
                </li>
                <li>
                    <?= Html::a('<i class="ion-wrench"></i>'.Yii::t('app', 'Change type'), '#'); ?>
                </li>
            </ul>
        </div>
        <?php Box::end(); ?>
    </div>

    <div class="col-md-9">
        <div class="row">
            <div class="col-md-6">
                <?php $box = Box::begin(['renderBody' => false]); ?>
                <?php $box->beginHeader(); ?>
                <?= $box->renderTitle(Yii::t('app', 'Contact information')); ?>
                <?php $box->beginTools(); ?>
                <?= Html::a(Yii::t('app', 'Change contact information'), '#', ['class' => 'btn btn-default btn-xs']); ?>
                <?php $box->endTools(); ?>
                <?php $box->beginBody(); ?>
                <?= DetailView::widget([
                    'model' => $model->contact,
                    'attributes' => [
                        //
                        'country_name',
                        'province',
                        'province_name',
                        'postal_code',
                        'city',
                        'street1',
                        'street2',
                        'street3',
                        //
                        'passport_no',
                        'passport_date',
                        'passport_by',
                        'organization',
                        //
                        'voice_phone',
                        'email',
                        'abuse_email',
                        'skype',
                        'jabber',
                        'icq',
                    ]
                ]) ?>
                <?php $box->endBody(); ?>
                <?php $box->endHeader(); ?>
                <?php $box::end(); ?>
            </div>
            <div class="col-md-6">

                <?php $box = Box::begin(['renderBody' => false]); ?>
                <?php $box->beginHeader(); ?>
                <?= $box->renderTitle(Yii::t('app', 'Billing information'), '47 items'); ?>
                <?php $box->beginTools(); ?>
                <?= Html::a(Yii::t('app', 'Recharge account'), '#', ['class' => 'btn btn-default btn-xs']); ?>
                <?php $box->endTools(); ?>
                <?php $box->beginBody(); ?>
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'state',
                        'balance',
                        'credit',
                        'tariff',
                        'type',
                    ]
                ]) ?>
                <?php $box->endBody(); ?>
                <?php $box->endHeader(); ?>
                <?php $box::end(); ?>
            </div>
        </div>
    </div>
</div>