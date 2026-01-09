<?php

declare(strict_types=1);

namespace hipanel\actions\RedirectPolicy;

class PreferSearchRedirectPolicy implements PostActionRedirectPolicy
{
    public function preferredTarget(): RedirectTarget
    {
        return RedirectTarget::Search;
    }
}
