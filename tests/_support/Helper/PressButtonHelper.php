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

class PressButtonHelper extends \Codeception\Module
{
    /**
     * @param $textOnButton
     * @throws \Codeception\Exception\ModuleException
     */
    public function pressButton(string $textOnButton, string $xpathPrefix = ''): void
    {
        $I = $this->getModule('WebDriver');

        $selector = $xpathPrefix . "//button[contains(text(), '{$textOnButton}')]";
        $I->click($selector);
    }

    /**
     * @param $linkText
     * @throws \Codeception\Exception\ModuleException
     */
    public function clickLink(string $linkText, string $xpathPrefix = ''): void
    {
        $I = $this->getModule('WebDriver');
        $selector = $xpathPrefix . "//a[contains(text(), '$linkText')]";
        $I->click($selector);
    }
}
