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

use hipanel\tests\_support\Page\Widget\Input\Exception\ElementNotDisappearedException;
use \Facebook\WebDriver\WebDriverKeys;

class MultipleSelect2 extends Select2
{
    private $inputSelector;
    public function setValue(string $value): void
    {
        if ($value === '') {
            $this->removeChosenOption();
            return;
        }
        $this->open();
        $this->fillSearchField($value);
        $this->close();
    }

    public function fillSearchField(string $name): Select2
    {
        $this->inputSelector = '.select2-container--open input.select2-search__field';
        $this->tester->fillField($this->inputSelector, $name);

        return $this;
    }

    public function close(): Select2
    {
        $this->tester->pressKey($this->inputSelector, WebDriverKeys::ENTER);
        $this->tester->pressKey($this->inputSelector, WebDriverKeys::ESCAPE);
        $this->seeIsClosed();

        return $this;
    }

    public function seeIsClosed(): void
    {
        try {
            parent::seeIsOpened();
        } catch (\Throwable $e) {
            return;
        }

        throw new ElementNotDisappearedException($this->selector);
    }
}
