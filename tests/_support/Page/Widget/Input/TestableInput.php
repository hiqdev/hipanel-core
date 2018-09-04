<?php

namespace hipanel\tests\_support\Page\Widget\Input;

use hipanel\tests\_support\AcceptanceTester;

/**
 * Class TestableInput
 *
 * Basic class for input elements.
 * The elements can be created in three different ways:
 * 1. Free input element
 *  In this case, instance should be created using a common constructor.
 *  The selector of the element should be put to the constructor.
 *      new Class(tester, selector)
 *
 * 2. As Advanced search element
 *      Class::asAdvancedSearch(tester, name)
 * 3. As Table filter element of indexes page
 *      Class::asTableFilter(tester, name)
 *  In these two cases, the element should be created with related named
 *  constructors.
 *  The second argument 'name' is placeholder in Advanced search or
 *  column name in Table filter respectively.
 *
 * @package hipanel\tests\_support\Page\Widget\Input
 */
abstract class TestableInput
{
    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var string
     */
    protected $auxName;

    /**
     * @var
     */
    protected $selector;

    const AS_BASE = 'div.advanced-search ';
    const TF_BASE = 'tr.filters ';

    protected $tester;

    /**
     * TestableInput constructor.
     * @param AcceptanceTester $tester
     * @param $selector
     */
    public function __construct(AcceptanceTester $tester, $selector)
    {
        $this->tester = $tester;
        $this->selector = $selector;
    }

    /**
     * @param AcceptanceTester $tester
     * @param $name
     * @return TestableInput
     */
    public static function asAdvancedSearch(AcceptanceTester $tester, $name)
    {
        $instance = new static($tester, $name);
        $instance->auxName = $instance->computeAuxName($name);
        $instance->selector = $instance->getSearchSelector();
        return $instance;
    }

    /**
     * @param AcceptanceTester $tester
     * @param $name
     * @return TestableInput
     */
    public static function asTableFilter(AcceptanceTester $tester, $name)
    {
        $instance = new static($tester, $name);
        $instance->auxName = $instance->computeAuxName($name);
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
     * @param string $name
     * @return string
     */
    private function computeAuxName(string $name): string
    {
        return substr(strtolower($name), 0, 5);
    }

    /**
     * Checks whether input is visible
     *
     * @param AcceptanceTester $I
     * @param string $formId
     */
    abstract public function isVisible(AcceptanceTester $I, string $formId): void;
}
