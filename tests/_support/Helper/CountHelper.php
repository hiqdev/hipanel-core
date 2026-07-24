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

    /**
     * Returns position of the element in the set of elements.
     *
     * The position counts from 0.
     * If the element is not found in the set -1 will be returned.
     *
     * ```html
     *  <ul>
     *      <li></li>
     *      <li></li>
     *      <li class="target"></li>
     *      <li></li>
     *  </ul>
     *  <img />
     * '''
     *
     * ```php
     *  indexOf('li.target', 'li')  => 2
     *  indexOf('img', 'li')        => -1
     * ```
     * @param string $elementSelector
     * @param string $setSelector
     * @return int
     * @throws \Codeception\Exception\ModuleException
     */
    public function indexOf(string $elementSelector, string $setSelector): int
    {
        $I = $this->getModule('WebDriver');

        return  $I->executeJS(<<<JS
return $('{$setSelector}').index($("{$elementSelector}"));
JS
        );
    }
}
