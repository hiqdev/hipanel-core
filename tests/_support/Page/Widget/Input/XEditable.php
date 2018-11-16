<?php

namespace hipanel\tests\_support\Page\Widget\Input;

use hipanel\tests\_support\AcceptanceTester;

/**
 * Class XEditable
 *
 * Represent XEditable input element.
 * @package hipanel\tests\_support\Page\Widget\Input
 */
class XEditable
{
    private $tester;

    public function __construct(AcceptanceTester $tester)
    {
        $this->tester = $tester;
    }

    /**
     * @return int
     */
    public function getRowsInTableForFill(): int
    {
        return count($this->tester->grabMultiple("//a[contains(@class, 'editable')]"));
    }

    /**
     * @param string $note
     * @param int $row
     */
    public function fillNoteWithoutAjax(string $note, int $row): void
    {
        $this->tester->click("div.price-item:nth-child($row) a.editable");
        (new Input($this->tester, "div.price-item:nth-child($row) div.editable-input input"))
            ->setValue($note);
        $this->tester->click("div.price-item:nth-child($row) button[type=submit]");
    }
}

