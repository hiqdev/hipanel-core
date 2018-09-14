<?php

namespace hipanel\tests\_support\Page\Widget\Input;

/**
 * Class Dropdown
 *
 * Represents dropdown input element
 * @package hipanel\tests\_support\Page\Widget\Input
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
        return self::TF_BASE . "select[id*={$this->auxName}]";
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
