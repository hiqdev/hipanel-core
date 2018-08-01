<?php

namespace hipanel\tests\_support\Page\Widget\Input;

use hipanel\tests\_support\AcceptanceTester;

class Dropdown extends TestableInput
{
    /**
     * @property string[]|null
     */
    private $items;

    /**
     * @param string[] $items array of items names
     * @return self
     */
    public function withItems(array $items): Dropdown
    {
        $this->items = $items;

        return $this;
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
