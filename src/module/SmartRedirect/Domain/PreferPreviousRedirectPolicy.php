<?php

declare(strict_types=1);

namespace hipanel\module\SmartRedirect\Domain;

class PreferPreviousRedirectPolicy implements PostActionRedirectPolicy
{
    public function preferredTarget(): RedirectTarget
    {
        return RedirectTarget::Previous;
    }
}
