<?php

namespace hipanel\tests\_support\Page\Widget\Input;

use hipanel\tests\_support\AcceptanceTester;

/**
 * Class Dropdown
 *
 * Represents dropdown input element
 * @package hipanel\tests\_support\Page\Widget\Input
 */
class Dropdown extends TestableInput
{
    /**
     * @property string[]|null
     */
    private $items;

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
        return self::TF_BASE . "select[id*={$this->auxName}]";
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->tester->selectOption($this->selector, $value);
    }

    /**
     * @param string[] $items array of items names
     * @return self
     */
    public function withItems(array $items): Dropdown
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @param AcceptanceTester $I
     * @param string $formId
     */
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
