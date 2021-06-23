<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\tests\_support\Page;

use hipanel\tests\_support\AcceptanceTester;
use hipanel\tests\_support\Page\Widget\Grid;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\TestableInput;

class IndexPage extends Authenticated
{
    /** @var string */
    protected $gridSelector = "//form[contains(@id, 'bulk') and contains(@id, 'search')]";

    /** @var Grid  */
    public $gridView;

    public function __construct(AcceptanceTester $I, string $gridSelector = null)
    {
        parent::__construct($I);

        $this->gridView = new Grid($I, $gridSelector ?? $this->gridSelector);
    }

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
        $I->see('Clear', TestableInput::AS_BASE . 'a');
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
        $this->gridView->containsColumns($columnNames, $representation);
    }

    /**
     * @see Grid::containsAmountOfRows()
     *
     * @param int $amount
     */
    public function containsAmountOfRows(int $amount): void
    {
        $this->gridView->containsAmountOfRows($amount);
    }

    /**
     * @see Grid::getRowDataKeyByNumber()
     *
     * @param int $rowNumber
     * @return string
     */
    public function getRowDataKeyByNumber(int $rowNumber): string
    {
        return $this->gridView->getRowDataKeyByNumber($rowNumber);
    }

    /**
     * @see Grid::filterBy()
     *
     * @param TestableInput $inputElement
     * @param string $value
     * @throws \Codeception\Exception\ModuleException
     */
    public function filterBy(TestableInput $inputElement, string $value): void
    {
        $this->gridView->filterBy($inputElement, $value);
    }

    /**
     * @see Grid::selectRowByNumber()
     *
     * @param int $n - number of the row that should be selected
     */
    public function selectTableRowByNumber(int $n): void
    {
        $this->gridView->selectRowByNumber($n);
    }

    /**
     * @see Grid::openRowMenuByNumber()
     *
     * @param int $n - number of the row which menu should be opened
     */
    public function openRowMenuByNumber(int $n): void
    {
        $this->gridView->openRowMenuByNumber($n);
    }

    /**
     * @see Grid::openRowMenuById()
     *
     * @param string $id - id of item which menu should be opened
     */
    public function openRowMenuById(string $id): void
    {
        $this->gridView->openRowMenuById($id);
    }

    /**
     * @see Grid::openRowMenuByColumnValue()
     *
     * @param string $column
     * @param string $value
     * @throws \Codeception\Exception\ModuleException
     */
    public function openRowMenuByColumnValue(string $column, string $value): void
    {
        $this->gridView->openRowMenuByColumnValue($column, $value);
    }

    /**
     * @see Grid::chooseRowMenuOption()
     *
     * @param $option - the name of option that should be clicked
     * @throws \Codeception\Exception\ModuleException
     */
    public function chooseRowMenuOption(string $option): void
    {
        $this->gridView->chooseRowMenuOption($option);
    }

    /**
     * @see Grid::countRowsInTableBody()
     *
     * @return int
     */
    public function countRowsInTableBody(): int
    {
        return $this->gridView->countRowsInTableBody();
    }

    /**
     * Checks whether filtering works properly.
     *
     * @param TestableInput $filterBy
     * @param string $name
     * @throws \Codeception\Exception\ModuleException
     */
    public function checkFilterBy(TestableInput $filterBy, string $name): void
    {
        $this->filterBy($filterBy, $name);
        $count = $this->countRowsInTableBody();
        for ($i = 1; $i <= $count; ++$i) {
            $this->tester->see($name, "//tbody/tr[$i]");
        }
    }

    /**
     * @see Grid::sortBy()
     *
     * @param string $columnName
     * @throws \Codeception\Exception\ModuleException
     */
    public function sortBy(string $columnName): void
    {
        $this->gridView->sortBy($columnName);
    }

    /**
     * @see Grid::checkSortingBy()
     *
     * @param string $sortBy
     * @throws \Codeception\Exception\ModuleException
     */
    public function checkSortingBy(string $sortBy): void
    {
        $this->gridView->checkSortingBy($sortBy);
    }

    public function openAndSeeBulkOptionByName(string $optionsName, array $optionData): void
    {
        $I = $this->tester;

        $I->click("//div[@class='mailbox-controls']//button[contains(text(), '" . $optionsName . "')]");

        foreach ($optionData as $element) {
            $I->seeElement("//ul//li//a[contains(text(),'$element')]");
        }
    }
}
