<?php

namespace hipanel\tests\_support\Page\Widget\Input;

/**
 * Class XEditable
 *
 * Represent XEditable input element.
 * @package hipanel\tests\_support\Page\Widget\Input
 */
class XEditable extends TestableInput
{
    private $row;

    /**
     * @param mixed $row
     */
    public function setRow($row): void
    {
        $this->row = $row;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->tester->click("div.price-item:nth-child($this->row) a.editable");
        (new Input($this->tester, "div.price-item:nth-child($this->row) div.editable-input input"))
            ->setValue($value);
        $this->tester->click("div.price-item:nth-child($this->row) button[type=submit]");
    }

    /**
     * @return string
     */
    protected function getSearchSelector(): string
    {
        // TODO: Implement getSearchSelector() method.
    }

    /**
     * @return string
     */
    protected function getFilterSelector(): string
    {
        // TODO: Implement getFilterSelector() method.
    }
}

