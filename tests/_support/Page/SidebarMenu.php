<?php

namespace hipanel\tests\_support\Page;

use yii\helpers\Url;

class SidebarMenu extends Authenticated
{
    public function ensureContains($rootMenuName, $items)
    {
        $I = $this->tester;

        $I->amOnPage(Url::to(['/']));
        $I->click($rootMenuName, '.sidebar-menu');
        $I->waitForElement('.menu-open');
        foreach ($items as $name => $url) {
            $I->see($name, '.menu-open');
            $I->seeLink('', Url::to($url));
        }
    }

    public function ensureDoesNotContain($rootMenuName, $items = null)
    {
        $I = $this->tester;

        $I->amOnPage(Url::to(['/']));
        if ($items === null) {
            $I->dontSee($rootMenuName);
        } else {
            $I->click($rootMenuName, '.sidebar-menu');
            $I->waitForElement('.menu-open');
            foreach ($items as $name) {
                $I->dontSee($name, '.menu-open');
            }
        }
    }
}
