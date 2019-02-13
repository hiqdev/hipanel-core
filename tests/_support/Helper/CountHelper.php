<?php

namespace hipanel\tests\_support\Helper;


class CountHelper extends \Codeception\Module
{
    /**
     * @param string $cssOrXpath
     * @return int
     * @throws \Codeception\Exception\ModuleException
     */
    public function countElements(string $cssOrXpath): int
    {
        $I = $this->getModule('WebDriver');

        return count($I->grabMultiple($cssOrXpath));
    }
}
