<?php

namespace hipanel\tests\_support\Page\Widget\Input;

use hipanel\tests\_support\AcceptanceTester;

class Dropdown extends TestableInput
{
    /**
     * @property array items
     */
    private $items;

    public function withItems(array $items)
    {
        $this->items = $items;
    }

    public function isVisible(AcceptanceTester $I, string $formId): void
    {
        $I->seeElement("//form[@id='$formId']//select", ['id' => $this->name]);
        if ($this->items) {
            foreach ($this->items as $item) {
                $I->see($item, "//form[@id='$formId']//select[@id='$this->name']/option");
            }
        }
    }
}
