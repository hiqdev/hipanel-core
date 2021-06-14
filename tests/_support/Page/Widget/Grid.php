<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\tests\_support\Page\Widget;

use Codeception\Scenario;
use hipanel\tests\_support\AcceptanceTester;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\TestableInput;
use Facebook\WebDriver\WebDriverKeys;

/**
 * Class Grid
 *
 * Represents GridView
 * @package hipanel\tests\_support\Page\Widget
 */
class Grid
{
    /** @var string $baseSelector selector of current gridView */
    private $baseSelector = "//form[contains(@id, 'bulk')]";

    /** @var AcceptanceTester */
    private $tester;

    const MENU_BUTTON_SELECTOR = "button[contains(@data-popover-content, '#menu')]";

    const ROW_CHECKBOX_SELECTOR = "input[contains(@class, 'grid-checkbox')]";

    /**
     * Grid constructor.
     * @param AcceptanceTester $tester
     * @param string|null $baseSelector
     */
    public function __construct(AcceptanceTester $tester, string $baseSelector = null)
    {
        $this->tester = $tester;
        if (!is_null($baseSelector)) {
            $this->baseSelector = $baseSelector;
        }
    }

    /**
     * Checks whether current table representation contains specified columns.
     *
     * @param string[] $columnNames array of column names
     * @param string|null $representation the representation name
     * @throws \Codeception\Exception\ModuleException
     */
    public function containsColumns(array $columnNames, $representation = null): void
    {
        $I = $this->tester;
        $formId = $I->grabAttributeFrom($this->baseSelector, 'id');

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
     * Filters gridView by specified value.
     *
     * @param TestableInput $inputElement
     * @param string $value
     * @throws \Codeception\Exception\ModuleException
     */
    public function filterBy(TestableInput $inputElement, string $value): void
    {
        $inputElement->setValue($value);
        if ($inputElement instanceof Input) {
            $this->tester->pressKey($inputElement->getSelector(), WebDriverKeys::ENTER);
        }
        $this->tester->waitForPageUpdate();
    }

    /**
     * Sorts grid by specified column.
     *
     * @param string $columnName
     * @throws \Codeception\Exception\ModuleException
     */
    public function sortBy(string $columnName): void
    {
        $this->tester->click("//th/a[contains(text(), '$columnName')]");
        $this->tester->waitForPageUpdate();
    }

    /**
     * Checks amount of the data rows in the table.
     *
     * @param int $amount
     */
    public function containsAmountOfRows(int $amount): void
    {
        $this->tester->seeNumberOfElements($this->baseSelector . '//tbody/tr', $amount);
    }

    /**
     * Returns data-key value for specified row in the table.
     *
     * @param int $rowNumber - number of the row in the table
     * @return string
     */
    public function getRowDataKeyByNumber(int $rowNumber): string
    {
        $selector = $this->baseSelector . "//tbody//tr[$rowNumber]";

        return $this->tester->grabAttributeFrom($selector, 'data-key');
    }

    public function ensureSeeValueInColumn(string $columnNumber, string $tableValue): void
    {
        $this->tester->see($tableValue, "//table//tbody//td[$columnNumber]//a[contains(text(), '$tableValue')]");
    }

    public function getColumnNumber(string $columnName): int
    {
        $columnNumber = 2;
        $headElements = $this->tester->grabMultiple('//th[not(./input)]');
        foreach ($headElements as $currentColummName) {
            if($columnName === $currentColummName) {
                return $columnNumber;
            }
            $columnNumber++;
        }

       throw new \Exception("failed detect column with name $columnName");
    }

    public function ensureBillViewContainData(array $billData): void
    {
        foreach ($billData as $billInfo) {
            $this->tester->see($billInfo, '//table');
        }
    }

    /**
     * Selects table row by its number.
     *
     * @param int $n - number of the row that should be selected
     */
    public function selectRowByNumber(int $n): void
    {
        $selector = $this->baseSelector . "//tbody//tr[{$n}]//" .
                    self::ROW_CHECKBOX_SELECTOR;

        $this->tester->click($selector);
    }

    public function selectRowById(int $id): void
    {
        $selector = $this->baseSelector . "//tbody//tr[@data-key={$id}]//" .
            self::ROW_CHECKBOX_SELECTOR;

        $this->tester->click($selector);
    }

    /**
     * Opens table row menu by its number.
     *
     * @param int $n - number of the row which menu should be opened
     */
    public function openRowMenuByNumber(int $n): void
    {
        $selector = $this->baseSelector . "//tbody//tr[{$n}]//" .
                    self::MENU_BUTTON_SELECTOR;

        $this->tester->click($selector);
    }

    /**
     * Opens table row menu by item id (data-key).
     *
     * @param string $id - id of item which menu should be opened
     */
    public function openRowMenuById(string $id): void
    {
        $selector = $this->baseSelector . "//tbody//tr[@data-key={$id}]//" .
                    self::MENU_BUTTON_SELECTOR;

        $this->tester->click($selector);
    }

    /**
     * Opens table row menu by the column name and value in it.
     *
     * ```php
     *  $indexPage->openRowMenuByColumnValue('Name', 'test_item');
     * ```
     * Will open the menu for the row, which has value 'test_item' in column 'Name'.
     *
     * @param string $column
     * @param string $value
     * @throws \Codeception\Exception\ModuleException
     */
    public function openRowMenuByColumnValue(string $column, string $value): void
    {
        $setSelector = 'thead th';
        $elementSelector = "th:contains('{$column}')";

        $elementIndex = $this->tester->indexOf($elementSelector, $setSelector);
        $elementIndex++;

        $selector = "//tr//td[{$elementIndex}]/descendant-or-self::" .
            "*[contains(text(), '{$value}')]//ancestor::tr";
        $rowId = $this->tester->grabAttributeFrom($selector, 'data-key');

        $this->openRowMenuById($rowId);
    }

    /**
     * Clicks to the specified row menu option.
     *
     * @param $option - the name of option that should be clicked
     * @throws \Codeception\Exception\ModuleException
     */
    public function chooseRowMenuOption(string $option): void
    {
        $selector = "//div[contains(@class, 'popover')]" .
            "//a[contains(text(), '{$option}')]";
        $this->tester->click($selector);
        $this->tester->waitForPageUpdate();
    }

    /**
     * Returns amount of rows in table.
     *
     * @return int
     */
    public function countRowsInTableBody(): int
    {
        return count($this->tester->grabMultiple('//tbody/tr[contains(@data-key,*)]'));
    }

    public function countColumnInTableBody(): int
    {
        return count($this->tester->grabMultiple('//thead//th'));
    }

    /**
     * Checks whether sorting works properly.
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
                break;
            }
            ++$whereNeedle;
        }
        $whereNeedle += 2;
        /**
         *  $whereNeedle += 2 && $i = 1 because xpath elements starting from 1.
         */
        $arrayForSort = [];
        for ($i = 1; $i <= $count; ++$i) {
            $arrayForSort[$i] = $this->tester->grabTextFrom("//tbody/tr[$i]/td[$whereNeedle]");
        }
        /**
         *  After sort() function arrayForSort start index = 0, but xpath elements starting from 1.
         */
        sort($arrayForSort, SORT_NATURAL | SORT_FLAG_CASE);
        for ($i = 1; $i <= $count; ++$i) {
            $this->tester->see($arrayForSort[$i - 1], "//tbody/tr[$i]/td[$whereNeedle]");
        }
    }
}
