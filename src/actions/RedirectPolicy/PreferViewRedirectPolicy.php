<?php

declare(strict_types=1);

namespace hipanel\actions\RedirectPolicy;

class PreferViewRedirectPolicy implements PostActionRedirectPolicy
{
    public function preferredTarget(): RedirectTarget
    {
        return RedirectTarget::View;
    }
}
