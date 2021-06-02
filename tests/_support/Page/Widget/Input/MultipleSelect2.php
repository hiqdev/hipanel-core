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

class MultipleSelect2 extends Select2
{
    public function setValue(string $value): void
    {
        parent::setValue($value);
        $this->close();
    }

    public function close(): Select2
    {
        $this->tester->click($this->getClickSelector());
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
