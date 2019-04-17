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

class CountHelper extends \Codeception\Module
{
    /**
     * @param string $cssOrXpath
     * @throws \Codeception\Exception\ModuleException
     * @return int
     */
    public function countElements(string $cssOrXpath): int
    {
        $I = $this->getModule('WebDriver');

        return count($I->grabMultiple($cssOrXpath));
    }
}
