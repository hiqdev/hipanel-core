<?php

namespace hipanel\tests\_support\Page\Widget\Input;

use hipanel\tests\_support\AcceptanceTester;

class Textarea extends TestableInput
{
    /**
     * @return string
     */
    protected function getSearchSelector(): string
    {
        // TODO: Implement getSearchSelector() method.
    }

    /**
     * @return string
     */
    protected function getFilterSelector(): string
    {
        // TODO: Implement getFilterSelector() method.
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->tester->fillField($this->selector, $value);
    }

    /**
     * @param AcceptanceTester $I
     * @param string $formId
     */
    public function isVisible(AcceptanceTester $I, string $formId): void
    {
        $I->seeElement("//form[@id='$formId']//textarea", ['placeholder' => $this->name]);
        // TODO: Change implementation
    }
}
