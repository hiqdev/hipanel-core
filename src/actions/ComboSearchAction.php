<?php

namespace hipanel\actions;

/**
 * Class ComboSearchAction is a default SearchAction with disabled pagination by default
 * This class is used generally together with Combo plugin to prevent count query generation
 */
class ComboSearchAction extends SearchAction
{
    public function init()
    {
        parent::init();

        if (!isset($this->dataProviderOptions['pagination'])) {
            $this->dataProviderOptions['pagination'] = false;
        }
    }
}
