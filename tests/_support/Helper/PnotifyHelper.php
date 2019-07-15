<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

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
        $I->executeJS(<<<JS
            const selector = "div.ui-pnotify-closer>span[title='Close']";
            const closeUntillItsDead = () => {
                const bttn = document.querySelector(selector);
                if (bttn) {
                    bttn.click();
                    setTimeout(closeUntillItsDead, 300);
                }
            };
            closeUntillItsDead();
           
JS
        );
        $I->waitForElementNotVisible('.ui-pnotify');
    }
}
