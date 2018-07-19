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

    public function fillSearchField(string $name): void
    {
        $this->tester->fillField('.select2-search__field', $name);
        $this->tester->waitForElementNotVisible('.loading-results', 120);
    }

    public function open($selector)
    {
        $this->tester->click($this->getSelect2Selector($selector));
        $this->seeIsOpened();

        return $this;
    }

    public function seeIsOpened()
    {
        $this->tester->seeElement('.select2-container--open');

        return $this;
    }

    public function seeIsClosed()
    {
        $this->tester->cantSeeElement('.select2-container--open');

        return $this;
    }

    /**
     * @param $optionName
     * @return $this
     */
    public function chooseOption($optionName)
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

    protected function getSelect2Selector($selector)
    {
        return $selector . ' + span [role=combobox]';
    }

    protected function getSelect2OptionSelector()
    {
        return '.select2-container--open .select2-results__options li';
    }
}
