<?php

namespace hipanel\tests\_support\Page\Widget;

use hipanel\tests\_support\AcceptanceTester;
use hipanel\tests\_support\Page\Authenticated;

class Grid extends Authenticated
{
    /**
     * @var string $Xpath Chropath of current grid
     */
    private $Xpath;

    public function __construct(AcceptanceTester $I, string $Xpath = "//form[contains(@id, 'bulk')]")
    {
        parent::__construct($I);
        $this->Xpath = $Xpath;
    }

    /**
     * @param string[] $columnNames array of column names
     * @param string|null $representation the representation name
     * @throws \Codeception\Exception\ModuleException
     */
    public function containsColumns(array $columnNames, $representation = null): void
    {
        $I = $this->tester;
        $formId = $I->grabAttributeFrom($this->Xpath, 'id');

        if ($representation !== null) {
            $I->click("//button[contains(text(), 'View:')]");
            $I->click("//ul/li/a[contains(text(), '$representation')]");
            $I->waitForPageUpdate(120);
        }

        foreach ($columnNames as $column) {
            $I->see($column, "//form[@id='$formId']//table/thead/tr/th");
        }
    }
}