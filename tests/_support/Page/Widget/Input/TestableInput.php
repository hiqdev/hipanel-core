<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\tests\_support\Page\Widget\Input;

use hipanel\tests\_support\AcceptanceTester;

/**
 * Class TestableInput.
 *
 * Basic class for input elements.
 * The elements can be created in three different ways:
 * 1. Free input element
 *  In this case, instance should be created using a common constructor.
 *  The selector of the element should be put to the constructor.
 *      new Class(tester, selector)
 *
 * 2. As Advanced search element
 *      Class::asAdvancedSearch($tester, $title)
 *
 * 3. As Table filter element of indexes page
 *      Class::asTableFilter($tester, $columnName)
 *
 *  In last two cases, the element should be created with related named
 *  constructors.
 *  The second argument is title of element in Advanced search or
 *  column name in Table filter respectively.
 */
abstract class TestableInput
{
    protected $tester;

    /**
     * @var string $title
     */
    protected $title;

    /**
     * @var string $auxName
     */
    protected $auxName;

    /**
     * @var string $selector
     */
    protected $selector;

    const AS_BASE = 'div.advanced-search ';

    const TF_BASE = 'tr.filters ';

    /**
     * TestableInput constructor.
     * @param AcceptanceTester $tester
     * @param string $selector
     */
    public function __construct(AcceptanceTester $tester, string $selector)
    {
        $this->tester = $tester;
        $this->selector = $selector;
    }

    /**
     * @param AcceptanceTester $tester
     * @param string $title
     * @return TestableInput
     */
    public static function asAdvancedSearch(AcceptanceTester $tester, string $title): TestableInput
    {
        $instance           = new static($tester, '');
        $instance->title    = $title;
        $instance->auxName  = $instance->computeAuxName($title);
        $instance->selector = $instance->getSearchSelector();

        return $instance;
    }

    /**
     * @param AcceptanceTester $tester
     * @param $columnName
     * @return TestableInput
     */
    public static function asTableFilter(AcceptanceTester $tester, string $columnName): TestableInput
    {
        $instance           = new static($tester, '');
        $instance->auxName  = $instance->computeAuxName($columnName);
        $instance->selector = $instance->getFilterSelector();

        return $instance;
    }

    /**
     * @return string
     */
    abstract protected function getSearchSelector(): string;

    /**
     * @return string
     */
    abstract protected function getFilterSelector(): string;

    /**
     * @param string $value
     */
    abstract public function setValue(string $value): void;

    /**
     * @return string
     */
    public function getSelector(): string
    {
        return $this->selector;
    }

    /**
     * @param string $name
     * @return string
     */
    private function computeAuxName(string $name): string
    {
        return substr(strtolower($name), 0, 5);
    }

    /**
     * Checks whether input is visible.
     */
    public function isVisible(): void
    {
        $this->tester->seeElement($this->selector);
    }
}
