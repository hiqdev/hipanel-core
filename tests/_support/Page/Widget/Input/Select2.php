<?php

namespace hipanel\tests\_support\Page\Widget\Input;

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
        return self::AS_BASE . "div[data-title='{$this->title}']>select";
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
     * @param string $value
     * @throws \Exception
     */
    public function setValueLike(string $value): void
    {
        $this->open();
        $this->fillSearchField($value);
        $this->chooseOptionLike($value);
    }

    /**
     * @return Select2
     */
    public function open(): Select2
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
    public function fillSearchField(string $name): Select2
    {
        $inputSelector = '.select2-container--open input.select2-search__field';
        $this->tester->fillField($inputSelector, $name);
        $this->tester->waitForElementNotVisible('.loading-results', 120);

        return $this;
    }

    /**
     * @param $optionName
     * @return Select2
     */
    public function chooseOption($optionName): Select2
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
    public function chooseOptionLike($optionName): Select2
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
