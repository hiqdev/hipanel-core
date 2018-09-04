<?php

namespace hipanel\tests\_support\Page\Widget\Input;

use hipanel\tests\_support\AcceptanceTester;
use phpDocumentor\Reflection\Types\Parent_;

/**
 * Class Select2
 *
 * Represent Select2 input element.
 * @package hipanel\tests\_support\Page\Widget\Input
 */
class Select2 extends TestableInput
{
    /**
     * @return string
     */
    protected function getSearchSelector(): string
    {
        return self::AS_BASE . "select[id*={$this->auxName}]";
    }

    /**
     * @return string
     */
    protected function getFilterSelector(): string
    {
        return self::TF_BASE . "select[id*={$this->auxName}]";
    }

    /**
     * @param string $value
     * @throws \Exception
     */
    public function setValue(string $value): void
    {
        $this->open();
        $this->fillSearchField($value);
        $this->chooseOption($value);
    }

    /**
     * @param AcceptanceTester $I
     * @param string $formId
     */
    public function isVisible(AcceptanceTester $I, string $formId): void
    {
        $I->see($this->name, "//form[@id='$formId']//span[contains(@class, 'select2-selection__placeholder')]");
//        TODO: Change implementation isVisible()
    }

    /**
     * @return Select2
     */
    protected function open(): Select2
    {
        $this->tester->click($this->getClickSelector());
        $this->seeIsOpened();

        return $this;
    }

    /**
     * @return Select2
     */
    public function seeIsOpened(): Select2
    {
        $this->tester->seeElement('.select2-container--open');

        return $this;
    }

    /**
     * @return string
     */
    protected function getClickSelector(): string
    {
        return $this->selector . ' + span [role=combobox]';
    }

    /**
     * @param string $name
     * @return Select2
     * @throws \Exception
     */
    protected function fillSearchField(string $name): Select2
    {
        $inputSelector = 'span[class*=dropdown] input.select2-search__field';
        $this->tester->fillField($inputSelector, $name);
        $this->tester->waitForElementNotVisible('.loading-results', 120);

        return $this;
    }

    /**
     * @param $optionName
     * @return Select2
     */
    protected function chooseOption($optionName): Select2
    {
        $this->tester->executeJS(<<<JS
        $("li:contains('{$optionName}')").each(function() {
            if (this.firstChild.data === '{$optionName}') {
                $(this).trigger('mouseup');
            }
        });
JS
        );
        return $this;
    }

    /**
     * @param $optionName
     * @return $this
     */
    protected function chooseOptionLike($optionName): Select2
    {
        $this->tester->executeJS(<<<JS
        $("li:contains('{$optionName}')").each(function() {
            if (this.firstChild.data.indexOf('{$optionName}') !== -1) {
                $(this).trigger('mouseup');
            }
        });
JS
        );
        return $this;
    }
}
