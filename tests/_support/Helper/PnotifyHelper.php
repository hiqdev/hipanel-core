<?php

namespace hipanel\tests\_support\Helper;

use Codeception\Module\WebDriver;

class PnotifyHelper extends \Codeception\Module
{
    public function closeNotification(string $text): void
    {
        /** @var WebDriver $I */
        $I = $this->getModule('WebDriver');
        $I->waitForElement('.ui-pnotify', 180);
        $I->see($text, '.ui-pnotify');
        $I->moveMouseOver(['css' => '.ui-pnotify']);
        $I->wait(1);
        $I->click("div.ui-pnotify-closer>span[title='Close']");
        $I->waitForElementNotVisible('.ui-pnotify');
    }
}
