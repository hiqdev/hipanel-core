<?php

namespace hipanel\tests\_support\Page\Widget\Input;

use hipanel\tests\_support\AcceptanceTester;

abstract class TestableInput
{
    /**
     * @var string $name
     */
    protected $name;

    /**
     * TestableInput constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Checks whether input is visible
     *
     * @param AcceptanceTester $I
     * @param string $formId
     */
    abstract public function isVisible(AcceptanceTester $I, string $formId): void;
}
