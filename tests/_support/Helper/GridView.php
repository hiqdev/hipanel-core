<?php

namespace hipanel\tests\_support\Helper;

use Codeception\Module\WebDriver;
use hipanel\helpers\Url;

class GridView extends \Codeception\Module
{
    public function seeSortColumns(array $columns, string $url): void
    {
        /** @var WebDriver $I */
        $I = $this->getModule('WebDriver');
        foreach ($columns as $param => $text) {
            $I->seeLink($text, Url::to([$url, 'sort' => $param]));
        }
    }
}
