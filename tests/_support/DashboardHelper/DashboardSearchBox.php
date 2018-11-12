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
     * @var array $data
     */
    protected $data;

    /**
     * DashboardSearchBox constructor.
     * @param AcceptanceTester $tester
     * @param array $testData for protected $data
     *  [
     *     'formAction' => '/finance/tariff/index',
     *     'inputName'  => 'TariffSearch[tariff_like]',
     *     'typeInput'  => 'input',
     *  ]
     */
    public function __construct(AcceptanceTester $tester, array $testData)
    {
        $this->tester = $tester;
        $this->data = $testData;
    }

    /**
     * Ensure is search box contains on testing page
     *
     */
    public function ensureSearchBoxContains(): void
    {
        $I = $this->tester;
        $formAction = "//form[contains(@action,'" . $this->data['formAction'] . "')]";
        $input = $formAction . "//". $this->data['typeInput'] . "[contains(@name, '" . $this->data['inputName'] . "')]";

        $I->seeInCurrentUrl('/dashboard/dashboard');
        $I->seeElement($formAction);
        $I->seeElement($input);
    }
}
