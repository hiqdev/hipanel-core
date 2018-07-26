<?php

namespace hipanel\tests\_support\Page;

class IndexPage extends Authenticated
{
    /**
     * @param string $formId
     * @param array $filters Example:
     * ```php
     *  [
     *      ['input' => ['placeholder' => 'Domain name']],
     *      ['textarea' => ['placeholder' => 'Domain names (one per row)']],
     *      ['input' => ['placeholder' => 'Notes']],
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
    }

    /**
     * @param array $buttons Example:
     * ```php
     *  [
     *      ['a' => 'Buy domain'],
     *      ["//button[@type='submit']" => 'Search'],
     *      ['a' => 'Clear'],
     *      ["//button[@type='button']" => 'Basic actions'],
     *      ["//button[@type='button']" => 'Set notes'],
     *      ["//button[@type='button']" => 'Set NS'],
     *      ["//button[@type='button']" => 'Change contacts'],
     *  ]
     *```
     */
    public function containsButtons(array $buttons): void
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
     * @param array $columns Example:
     * ```php
     *  [
     *      'Domain name',
     *      'Status',
     *      'WHOIS',
     *      'Protection',
     *      'Registered',
     *      'Paid till',
     *      'Autorenew',
     *  ]
     *```
     */
    public function containsColumns(string $formId, array $columns): void
    {
        $I = $this->tester;

        foreach ($columns as $column) {
            $I->see($column, "//form[@id='$formId']//table/thead/tr/th");
        }
    }
}
