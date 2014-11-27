<?
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>

<? $form = ActiveForm::begin() ?>
    <?= $form->field($model, 'is_published')->widget(kartik\widgets\SwitchInput::className(),[

    ]); ?>

    <?= $form->field($model, 'article_name'); ?>
    <?= $form->field($model, 'post_date')->widget(kartik\widgets\DatePicker::className(),[
    'value' => date('d-M-Y', strtotime('+2 days')),
    ]); ?>

    <?= $form->field($model, 'type')->dropDownList(['news'=>'News','promos'=>'Promos','mail'=>'Mail']); ?>



    <div role="tabpanel">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#ru" aria-controls="home" role="tab" data-toggle="tab">Russian</a></li>
            <li role="presentation"><a href="#en" aria-controls="profile" role="tab" data-toggle="tab">English</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="ru">
                <div class="form-group">
                    <label for="exampleInputEmail1">HTML title</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">HTML keywords</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Title</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Short text</label>
                    <textarea class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Text</label>
                    <textarea class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="en">
                <div class="form-group">
                    <label for="exampleInputEmail1">HTML title</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">HTML keywords</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Title</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Short text</label>
                    <textarea class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Text</label>
                    <textarea class="form-control" rows="3"></textarea>
                </div>
            </div>
        </div>

    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
    </div>

<? $form::end(); ?>