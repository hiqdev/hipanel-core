<?php

namespace hipanel\widgets;

interface HookInterface
{
    public function registerJsHook(string $paramName): void;
}
