<?php

namespace hipanel\tests\_support\Page;

use hipanel\tests\_support\AcceptanceTester;

class SidebarMenu
{
    private $tester;

    public function __construct(AcceptanceTester $I)
    {
        $this->tester = $I;
    }

    public function ensureContains($rootMenuName, $items)
    {
        $I = $this->tester;

        $I->click($rootMenuName, '.sidebar-menu');
        $I->wait(1);
        foreach ($items as $name => $url) {
            $I->see($name, '.menu-open');
            $I->seeLink('', $url);
        }
    }

    public function ensureDoesNotContain($rootMenuName, $items = null)
    {
        $I = $this->tester;

        if ($items === null) {
            $I->dontSee($rootMenuName);
        } else {
            $I->click($rootMenuName, '.sidebar-menu');
            $I->wait(1);
            foreach ($items as $name) {
                $I->dontSee($name, '.menu-open');
            }
        }
    }
}
