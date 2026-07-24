<?php
declare(strict_types=1);

use hipanel\models\CustomAttribute;
use hipanel\widgets\DynamicFormWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var CustomAttribute[] $customAttributes
 * @var ActiveForm $form
 * @var array $dropDownList
 */

?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_wrapper',
    'widgetBody' => '.container-items',
    'widgetItem' => '.attribute-item',
    'insertButton' => '.add-attribute',
    'deleteButton' => '.remove-attribute',
    'model' => reset($customAttributes),
    'formId' => $form->getId(),
    'formFields' => [
        'name',
        'value',
    ],
]) ?>
<table class="table table-striped table-condensed">
    <thead>
    <tr>
        <th><?= Yii::t('hipanel:finance', 'Name') ?></th>
        <th><?= Yii::t('hipanel:finance', 'Value') ?></th>
        <th class="text-center" style="width: 90px;">
            <button type="button" class="add-attribute btn bg-olive btn-sm"
                    title="<?= Yii::t('hipanel', 'Add new') ?>">
                <?= Html::tag('span', null, ['class' => 'fa fa-fw fa-plus']) ?>
            </button>
        </th>
    </tr>
    </thead>
    <tbody class="container-items">
    <?php foreach ($customAttributes as $idx => $attribute): ?>
        <tr class="attribute-item">
            <td class="text-center" style="vertical-align: middle">
                <?php if (empty($dropDownList)) : ?>
                    <?= $form->field($attribute, "[$idx]name")->textInput(['maxlength' => true])->label('') ?>
                <?php else : ?>
                    <?= $form->field($attribute, "[$idx]name")->dropDownList($dropDownList, ['prompt' => '--'])->label('') ?>
                <?php endif ?>
            </td>
            <td class="text-center" style="vertical-align: middle">
                <?= $form->field($attribute, "[$idx]value")->label('')->textInput(['maxlength' => true, 'value' => $attribute->asInputValue()]) ?>
            </td>
            <td class="text-center" style="vertical-align: middle">
                <button type="button" class="remove-attribute btn btn-danger btn-sm"
                        title="<?= Yii::t('hipanel', 'Remove') ?>">
                    <?= Html::tag('span', null, ['class' => 'fa fa-fw fa-minus']) ?>
                </button>
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>
<?php DynamicFormWidget::end() ?>
