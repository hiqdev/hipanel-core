<?php
declare(strict_types=1);

namespace hipanel\widgets;

use hipanel\models\CustomAttribute;
use hiqdev\hiart\ActiveRecord;
use yii\base\Widget;
use yii\widgets\ActiveForm;

class CustomAttributesForm extends Widget
{
    public ActiveForm $form;

    public ActiveRecord $owner;

    public function run(): string
    {
        $this->view->registerJs(/** @lang JavaScript */ '
            $(".remove-attribute").click(() => {
              if ($(".attribute-item").length === 1) {
                $(".container-items :input").val("");
              }
            });
        ');
        $customAttributes = empty($this->owner->getCustomAttributes()) ? [new CustomAttribute()] : $this->owner->getCustomAttributes();

        return $this->render('CustomAttributesForm', [
            'form' => $this->form,
            'customAttributes' => $customAttributes,
            'dropDownList' => $this->owner->getCustomAttributesList(),
        ]);
    }
}
