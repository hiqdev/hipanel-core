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
        $I->wait(0.5);
        $I->waitForJS(<<<JS
            var selector = "div.ui-pnotify-closer>span[title='Close']";
            var closeButton = document.querySelector(selector);
            if (closeButton !== undefined) {
                closeButton.click();
                return true;
            }
            return false;
JS
, 60);
        $I->waitForElementNotVisible('.ui-pnotify');
    }
}
