<?php

namespace hipanel\tests\_support\Page\Widget\Input;

/**
 * Class Textarea
 *
 * Represent textarea input element.
 * @package hipanel\tests\_support\Page\Widget\Input
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
