<?php

namespace hipanel\tests\_support\Page;

use hipanel\tests\_support\AcceptanceTester;
use yii\helpers\Url;

class SidebarMenu extends Authenticated
{
    public function __construct(AcceptanceTester $I)
    {
        parent::__construct($I);
        $I->amOnPage(Url::to(['/']));
    }

    public function ensureContains($rootMenuName, $items)
    {
        $I = $this->tester;

        $I->click($rootMenuName, '.sidebar-menu');
        $I->waitForElement('.menu-open');
        foreach ($items as $name => $url) {
            $I->see($name, '.menu-open');
            $I->seeLink('', Url::to($url));
        }
        $I->click($rootMenuName, '.sidebar-menu');
    }

    public function ensureDoesNotContain($rootMenuName, $items = null)
    {
        $I = $this->tester;

        if ($items === null) {
            $I->dontSee($rootMenuName);
        } else {
            $I->click($rootMenuName, '.sidebar-menu');
            $I->waitForElement('.menu-open');
            foreach ($items as $name) {
                $I->dontSee($name, '.menu-open');
            }
            $I->click($rootMenuName, '.sidebar-menu');
        }
    }
}
