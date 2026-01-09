<?php

declare(strict_types=1);

namespace hipanel\actions\RedirectPolicy;

enum RedirectTarget
{
    case View;
    case Previous;
    case Search;
}
