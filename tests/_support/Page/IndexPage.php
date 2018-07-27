<?php

namespace hipanel\tests\_support\Page;

class IndexPage extends Authenticated
{
    /**
     * @param string $formId
     * @param array[] $filters Example:
     * ```php
     *  [
     *      ['textarea' => ['placeholder' => 'Domain names (one per row)']],
     *      ['input' => [
     *          'id' => 'domainsearch-created_from',
     *          'name' => 'date-picker',
     *      ]],
     *  ]
     *```
     */
    public function containsFilters(string $formId, array $filters): void
    {
        $I = $this->tester;

        foreach ($filters as $filter) {
            foreach ($filter as $selector => $attributes) {
                $I->seeElement("//form[@id='$formId']//$selector", $attributes);
            }
        }
        $I->see('Search', "//form[@id='$formId']//button[@type='submit']");
        $I->see('Clear', "//form[@id='$formId']//a");
    }

    /**
     * @param array[] $buttons Example:
     * ```php
     *  [
     *      ["//button[@type='button']" => 'Set IPs'],
     *  ]
     *```
     */
    public function containsBulkButtons(array $buttons): void
    {
        $I = $this->tester;

        foreach ($buttons as $button) {
            foreach ($button as $selector => $text) {
                $I->see($text, $selector);
            }
        }
    }

    /**
     * @param string $formId
     * @param string[] $columnNames array of column names
     */
    public function containsColumns(string $formId, array $columnNames): void
    {
        $I = $this->tester;

        foreach ($columnNames as $column) {
            $I->see($column, "//form[@id='$formId']//table/thead/tr/th");
        }
    }
}
