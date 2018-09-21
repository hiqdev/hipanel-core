<?php

namespace hipanel\tests\_support\Page;

use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\TestableInput;
use WebDriverKeys;

class IndexPage extends Authenticated
{
    /**
     * @param TestableInput[] $inputs example:
     * ```php
     *  [
     *      Input::asAdvancedSearch(Tester, 'Name'),
     *      Select2::asAdvancedSearch(Tester, 'Status')
     *  ]
     *```
     */
    public function containsFilters(array $inputs): void
    {
        $I = $this->tester;

        $I->see('Advanced search', 'h3');

        foreach ($inputs as $input) {
            $input->isVisible();
        }
        $I->see('Search', TestableInput::AS_BASE . "button[type='submit']");
        $I->see('Clear', TestableInput::AS_BASE  . "a");
    }

    /**
     * @param string[] $list array of legend list
     */
    public function containsLegend(array $list): void
    {
        $I = $this->tester;

        $I->see('Legend', 'h3');

        foreach ($list as $text) {
            $I->see($text, "//h3[text()='Legend']/../../div/ul/li");
        }
    }

    /**
     * @param string[] $buttons array of buttons
     */
    public function containsBulkButtons(array $buttons): void
    {
        $I = $this->tester;

        foreach ($buttons as $text) {
            $I->see($text, "//button[@type='submit' or @type='button']");
        }
    }

    /**
     * @param string[] $columnNames array of column names
     * @param string|null $representation the representation name
     * @throws \Codeception\Exception\ModuleException
     */
    public function containsColumns(array $columnNames, $representation = null): void
    {
        $I = $this->tester;
        $formId = $I->grabAttributeFrom("//form[contains(@id, 'bulk') " .
                                        "and contains(@id, 'search')]", 'id');

        if ($representation !== null) {
            $I->click("//button[contains(text(), 'View:')]");
            $I->click("//ul/li/a[contains(text(), '$representation')]");
            $I->waitForPageUpdate();
        }

        foreach ($columnNames as $column) {
            $I->see($column, "//form[@id='$formId']//table/thead/tr/th");
        }
    }

    /**
     * Filters index page table
     *
     * @param TestableInput $inputElement
     * @param $value
     * @throws \Codeception\Exception\ModuleException
     */
    public function filterBy(TestableInput $inputElement, $value)
    {
        $inputElement->setValue($value);
        if ($inputElement instanceof Input) {
            $this->tester->pressKey($inputElement->getSelector(),WebDriverKeys::ENTER);
        }
        $this->tester->waitForPageUpdate();
    }

    /**
     * Selects table row by its number
     *
     * @param int $n - number of the row that should be selected
     */
    public function selectTableRowByNumber(int $n): void
    {
        $I = $this->tester;

        $selector = "form tbody tr:nth-child($n) input[type=checkbox]";
        $I->click($selector);
    }

    /**
     * Opens table row menu by its number
     *
     * @param int $n - number of the row which menu should be opened
     */
    public function openRowMenuByNumber(int $n): void
    {
        $this->tester->click("form tbody tr:nth-child($n) button");
    }

    /**
     * Opens table row menu by item id
     *
     * @param string $id - id of item which menu should be opened
     */
    public function openRowMenuById(string $id)
    {
        $this->tester->click("tr[data-key='$id'] button");
    }

    /**
     * Clicks to row menu option
     *
     * @param $option - the name of option that should be clicked
     * @throws \Codeception\Exception\ModuleException
     */
    public function chooseRowMenuOption($option)
    {
        $this->tester->click("//ul[@class='nav']//a[contains(text(), '{$option}')]");
        $this->tester->waitForPageUpdate();
    }
}
