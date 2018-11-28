<?php

namespace hipanel\tests\_support\DashboardHelper;

use hipanel\tests\_support\AcceptanceTester;

class DashboardSearchBox
{
    /**
     * @var AcceptanceTester
     */
    protected $tester;

    /**
     * DashboardSearchBox constructor.
     * @param AcceptanceTester $tester
     */
    public function __construct(AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    /**
     * Ensure is search box contains on testing page
     * @param string $formAction
     * @param string $inputName
     * @param string $typeInput
     */
    public function ensureSearchBoxContains(
        string $formAction,
        string $inputName,
        string $typeInput
    ): void
    {
        $I = $this->tester;
        $formActionXpath = "//form[contains(@action,'" . $formAction . "')]";
        $input = $formActionXpath . "//". $typeInput . "[contains(@name, '$inputName')]";

        $I->seeInCurrentUrl('/dashboard/dashboard');
        $I->seeElement($formActionXpath);
        $I->seeElement($input);
    }
}
