<?php

namespace hipanel\tests\_support\Page;

use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\TestableInput;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;

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
            $I->waitForPageUpdate(120);
        }

        foreach ($columnNames as $column) {
            $I->see($column, "//form[@id='$formId']//table/thead/tr/th");
        }
    }

    /**
     * @param int $amount
     */
    public function containsAmountOfRows(int $amount): void
    {
        $this->tester->seeNumberOfElements('//tbody/tr', $amount);
    }

    public function getRowDataKeyByNumber(int $rowNumber): string
    {
        $selector = "form tbody tr:nth-child($rowNumber)";

        return $this->tester->grabAttributeFrom($selector, 'data-key');
    }

    /**
     * Filters index page table
     *
     * @param TestableInput $inputElement
     * @param $value
     * @throws \Codeception\Exception\ModuleException
     */
    public function filterBy(TestableInput $inputElement, string $value): void
    {
        $inputElement->setValue($value);
        if ($inputElement instanceof Input) {
            $this->tester->pressKey($inputElement->getSelector(),WebDriverKeys::ENTER);
        }
        $this->tester->wait(1);
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
    public function openRowMenuById(string $id): void
    {
        $this->tester->click("tr[data-key='$id'] button");
    }

    /**
     * Clicks to row menu option
     *
     * @param $option - the name of option that should be clicked
     * @throws \Codeception\Exception\ModuleException
     */
    public function chooseRowMenuOption(string $option): void
    {
        $this->tester->click("//ul[@class='nav']//a[contains(text(), '{$option}')]");
        $this->tester->waitForPageUpdate();
    }

    /**
     * Parse tbody, count td and return result
     *
     * @return int
     */
    public function countRowsInTableBody(): int
    {
        return count($this->tester->grabMultiple('//tbody/tr[contains(@data-key,*)]'));
    }

    /**
     * Checks whether filtering works properly
     *
     * @param string $filterBy
     * @param string $name
     * @throws \Codeception\Exception\ModuleException
     */
    public function checkFilterBy(string $filterBy, string $name): void
    {
        $this->filterBy(new Dropdown($this->tester, "tr.filters select[name*=$filterBy]"), $name);
        $count = $this->countRowsInTableBody();
        for ($i = 1 ; $i <= $count; ++$i) {
            $this->tester->see($name, "//tbody/tr[$i]");
        }
    }

    /**
     * Checks whether sorting works properly
     *
     * Method find column by $sortBy, parse data, call default sort by $sortBy
     * and compare data in table with sort(copy_data_from_table)
     *
     * @param string $sortBy
     * @throws \Codeception\Exception\ModuleException
     */
    public function checkSortingBy(string $sortBy): void
    {
        $this->tester->click("//button[contains(text(),'Sort')]");
        $this->tester->click("//ul//a[contains(text(),'$sortBy')]");
        $this->tester->waitForPageUpdate();
        $tableWithNeedle = $this->tester->grabMultiple('//th/a');
        $whereNeedle = 0;
        $count = $this->countRowsInTableBody();
        while ($whereNeedle < count($tableWithNeedle)) {
            if ($tableWithNeedle[$whereNeedle] === $sortBy) {
                break ;
            }
            $whereNeedle++;
        }
        $whereNeedle += 2;
        /**
         *  $whereNeedle += 2 && $i = 1 because xpath elements starting from 1
         */
        $arrayForSort = [];
        for ($i = 1 ; $i <= $count; ++$i) {
            $arrayForSort[$i] = $this->tester->grabTextFrom("//tbody/tr[$i]/td[$whereNeedle]");
        }
        /**
         *  After sort() function arrayForSort start index = 0, but xpath elements starting from 1
         */
        sort($arrayForSort, SORT_NATURAL | SORT_FLAG_CASE);
        for ($i = 1 ; $i <= $count; ++$i) {
            $this->tester->see($arrayForSort[$i - 1], "//tbody/tr[$i]/td[$whereNeedle]");
        }
    }
}
