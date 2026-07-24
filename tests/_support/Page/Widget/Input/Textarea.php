<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\tests\_support\Page\Widget\Input;

/**
 * Class Textarea.
 *
 * Represent textarea input element.
 */
class Textarea extends TestableInput
{
    /**
     * @return string
     */
    protected function getSearchSelector(): string
    {
        return self::AS_BASE . "div[data-title='{$this->title}']>textarea";
    }

    /**
     * @return string
     */
    protected function getFilterSelector(): string
    {
        // TODO: Implement getFilterSelector() method.
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->tester->fillField($this->selector, $value);
    }
}
