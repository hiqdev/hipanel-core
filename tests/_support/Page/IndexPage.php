<?php

namespace hipanel\tests\_support\Page;

use hipanel\tests\_support\Page\Widget\Input\TestableInput;

class IndexPage extends Authenticated
{
    /**
     * @param TestableInput[] $inputs Example:
     * ```php
     *  [
     *      Input::input('Domain name'),
     *      Textarea::input('Domain names (one per row)'),
     *      Select2::input('Status'),
     *  ]
     *```
     */
    public function containsFilters(array $inputs): void
    {
        $I = $this->tester;

        $I->see('Advanced search', 'h3');
        $formId = $I->grabAttributeFrom("//form[contains(@id, 'advancedsearch')]", 'id');

        foreach ($inputs as $input) {
            $input->isVisible($I, $formId);
        }
        $I->see('Search', "//form[@id='$formId']//button[@type='submit']");
        $I->see('Clear', "//form[@id='$formId']//a");
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
     * @param string $formId
     * @param string[] $columnNames array of column names
     */
    public function containsColumns(array $columnNames): void
    {
        $I = $this->tester;
        $formId = $I->grabAttributeFrom("//form[contains(@id, 'bulk') and contains(@id, 'search')]", 'id');

        foreach ($columnNames as $column) {
            $I->see($column, "//form[@id='$formId']//table/thead/tr/th");
        }
    }
}
