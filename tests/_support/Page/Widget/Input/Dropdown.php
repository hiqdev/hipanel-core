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
 * Class Dropdown.
 *
 * Represents dropdown input element
 */
class Dropdown extends TestableInput
{
    /**
     * @property string[]|null
     */
    private $items;

    /**
     * @return string
     */
    protected function getSearchSelector(): string
    {
        return self::AS_BASE . "div[data-title='{$this->title}']>select";
    }

    /**
     * @return string
     */
    protected function getFilterSelector(): string
    {
        return self::TF_BASE . "select[name*={$this->auxName}]";
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->tester->selectOption($this->selector, $value);
    }

    /**
     * @param string[] $items array of items names
     * @return self
     */
    public function withItems(array $items): Dropdown
    {
        $this->items = $items;

        return $this;
    }

    public function isVisible(): void
    {
        parent::isVisible();
        if ($this->items) {
            foreach ($this->items as $item) {
                $this->tester->see($item, $this->selector . ' option');
            }
        }
    }
}
