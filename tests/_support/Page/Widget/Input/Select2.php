<?php

namespace hipanel\tests\_support\Page\Widget\Input;

use hipanel\tests\_support\AcceptanceTester;

class Select2 extends TestableInput
{
    public function isVisible(AcceptanceTester $I, string $formId): void
    {
        $I->see($this->name, "//form[@id='$formId']//span[contains(@class, 'select2-selection__placeholder')]");
    }
}
