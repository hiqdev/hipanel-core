<?php

namespace hipanel\tests\_support\Page;

use hipanel\helpers\Url;

class GridView extends Authenticated
{
    public function containsColumns(array $columns, string $url): void
    {
        $I = $this->tester;

        foreach ($columns as $param => $text) {
            $I->seeLink($text, Url::to([$url, 'sort' => $param]));
        }
    }
}
