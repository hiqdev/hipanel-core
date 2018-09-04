<?php

namespace hipanel\tests\_support\Page\Widget\Input;

use hipanel\tests\_support\AcceptanceTester;

/**
 * Class Input
 *
 * Represents text input element
 * @package hipanel\tests\_support\Page\Widget\Input
 */
class Input extends TestableInput
{
    /**
     * @return string
     */
    protected function getSearchSelector(): string
    {
        return self::AS_BASE . "input[id*={$this->auxName}]";
    }

    /**
     * @return string
     */
    protected function getFilterSelector(): string
    {
        return self::TF_BASE . "input[name*={$this->auxName}]";
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
        $I->seeElement("//form[@id='$formId']//input", ['placeholder' => $this->name]);
        // TODO: Change implementation
    }
}
