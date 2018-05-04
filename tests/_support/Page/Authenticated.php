<?php

namespace hipanel\tests\_support\Page;

use hipanel\tests\_support\AcceptanceTester;

class Authenticated
{
    /**
     * @var AcceptanceTester
     */
    protected $tester;

    public function __construct(AcceptanceTester $I)
    {
        $this->tester = $I;
        $this->login();
    }

    protected function login()
    {
        $this->tester->login();
    }
}
