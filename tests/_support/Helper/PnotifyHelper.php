<?php

namespace hipanel\tests\_support\Helper;

use Codeception\Module\WebDriver;

class PnotifyHelper extends \Codeception\Module
{
    public function closeNotification(string $text)
    {
        /** @var WebDriver $I */
        $I = $this->getModule('WebDriver');
        $I->waitForElement('.ui-pnotify', 180);
        $I->see($text, '.ui-pnotify');
        $I->moveMouseOver(['css' => '.ui-pnotify']);
        $I->wait(1);
        $I->click('//span[@title="Close"]');
    }
}
