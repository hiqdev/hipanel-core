<?php

namespace hipanel\tests\_support\Page\Widget\Input;

/**
 * Class CheckBox.
 *
 * Represents checkbox element
 */
class CheckBox extends TestableInput
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
        $checkBoxState = $this->getCheckBoxState();
        $boolState = $value === 'true' ? true : false;
        if ($checkBoxState === $boolState) {
            return;
        }
        $this->clickCheckBox();
    }

    private function clickCheckBox(): void
    {
        $this->tester->executeJS(<<<JS
document.querySelector(arguments[0]).click();
JS
            , [$this->selector]);
    }

    private function getCheckBoxState(): bool
    {
        $state = $this->tester->executeJS(<<<JS
return document.querySelector(arguments[0]).checked;
JS
            , [$this->selector]);

        return $state;
    }
}
