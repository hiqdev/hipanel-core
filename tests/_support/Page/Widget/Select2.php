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
        $this->seeIsOpened();
        $this->tester->clickWithLeftButton(Locator::contains($this->getSelect2OptionSelector(), $optionName), 5, 5);
        $this->seeIsClosed();

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
