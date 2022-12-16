<?php

declare(strict_types=1);

namespace hipanel\tests\_support\Page\Widget\Input;

class VueTreeSelect extends TestableInput
{
    protected function getSearchSelector(): string
    {
        return self::AS_BASE . "div[data-title='$this->title'] div.vue-treeselect";
    }

    protected function getFilterSelector(): string
    {
        return "//*[contains(@class, 'filters')]"
            . "//div[contains(@class, 'vue-treeselect__placeholder')][contains(text(), '$this->title')]"
            . "/following-sibling::div[contains(@class, 'vue-treeselect__input-container')]/input";
    }

    public function setValue(string $value): void
    {
        if (!empty($value)) {
            $this->selectOption($value);
        }
        $this->removeOption($value);
    }

    private function selectOption(string $value): void
    {
        $this->tester->click($this->selector);
        $this->tester->fillField($this->selector, $value);
        $this->tester->click("//*[@class='vue-treeselect__label'][text()='$value']");
    }

    private function removeOption(?string $value): void
    {
        // TODO: Implement if $value empty then clear all, otherwise remove one option
    }
}
