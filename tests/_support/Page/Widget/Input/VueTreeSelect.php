<?php

declare(strict_types=1);

namespace hipanel\tests\_support\Page\Widget\Input;

class VueTreeSelect extends TestableInput
{
    protected function getSearchSelector(): string
    {
        return self::AS_BASE . "div[data-title='$this->title'] div.vue-treeselect";
    }

    protected function getFilterSelector(): string
    {
        // TODO: Implement getFilterSelector() method.
    }

    public function setValue(string $value): void
    {
        // TODO: Implement setValue() method.
    }
}
