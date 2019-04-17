<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\tests\_support\Page;

use hipanel\helpers\Url;
use hipanel\tests\_support\AcceptanceTester;

class SidebarMenu extends Authenticated
{
    public function __construct(AcceptanceTester $I)
    {
        parent::__construct($I);
        $I->needPage(Url::to(['/']));
    }

    public function ensureContains(string $rootMenuName, array $items): void
    {
        $I = $this->tester;

        $I->click($rootMenuName, '.sidebar-menu');
        $I->waitForElement('.menu-open');
        foreach ($items as $name => $url) {
            $I->see($name, '.menu-open');
            $I->seeElement(['css' => '.menu-open a[href~="' . Url::to($url) . '"]']);
        }
        $I->click($rootMenuName, '.sidebar-menu');
    }

    public function ensureDoesNotContain(string $rootMenuName, array $items = null): void
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
