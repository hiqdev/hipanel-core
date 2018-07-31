<?php

namespace hipanel\tests\_support\Page\Widget\Input;

use hipanel\tests\_support\AcceptanceTester;

class Textarea extends TestableInput
{
    public function isVisible(AcceptanceTester $I, string $formId): void
    {
        $I->seeElement("//form[@id='$formId']//textarea", ['placeholder' => $this->name]);
    }
}
