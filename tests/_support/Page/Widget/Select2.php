<?php
namespace hipanel\tests\_support\Page\Widget;

use Codeception\Util\Locator;
use hipanel\tests\_support\AcceptanceTester;

class Select2
{
    /**
     * @var \AcceptanceTester
     */
    protected $tester;

    function __construct(AcceptanceTester $I)
    {
        $this->tester = $I;
    }

    /**
     * @param string $name
     * @throws \Exception
     */
    public function fillSearchField(string $name): void
    {
        $this->tester->fillField('.select2-search__field', $name);
        $this->tester->waitForElementNotVisible('.loading-results', 120);
    }

    /**
     * @param string $selector
     * @return Select2
     */
    public function open(string $selector): Select2
    {
        $this->tester->click($this->getSelect2Selector($selector));
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
     * @return Select2
     */
    public function seeIsClosed(): Select2
    {
        $this->tester->cantSeeElement('.select2-container--open');

        return $this;
    }

    /**
     * @param $optionName
     * @return $this
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

    /**
     * @param string $selector
     * @return string
     */
    protected function getSelect2Selector(string $selector): string
    {
        return $selector . ' + span [role=combobox]';
    }

    /**
     * @return string
     */
    protected function getSelect2OptionSelector(): string
    {
        return '.select2-container--open .select2-results__options li';
    }
}
