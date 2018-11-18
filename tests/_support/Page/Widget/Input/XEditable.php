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
    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->tester->click("$this->selector a.editable");
        (new Input($this->tester, "$this->selector div.editable-input input"))
            ->setValue($value);
        $this->tester->click("$this->selector button[type=submit]");
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

