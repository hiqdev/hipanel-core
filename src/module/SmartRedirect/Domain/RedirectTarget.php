<?php

declare(strict_types=1);

namespace hipanel\module\SmartRedirect\Domain;

enum RedirectTarget
{
    case View;
    case Previous;
    case Search;
}
