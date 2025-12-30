<?php

declare(strict_types=1);

namespace hipanel\actions;

use hidev\base\Component;
use hipanel\actions\RedirectPolicy\PostActionRedirectPolicy;
use hipanel\actions\RedirectPolicy\PreferViewRedirectPolicy;
use hipanel\actions\RedirectPolicy\RedirectTarget;
use yii\di\Instance;

class ActionRedirectResolver extends Component
{
    public PostActionRedirectPolicy|string|array $policy = PreferViewRedirectPolicy::class;

    private PostActionRedirectPolicy $resolvedPolicy;

    public function init(): void
    {
        parent::init();

        $this->resolvedPolicy = Instance::ensure(
            $this->policy,
            PostActionRedirectPolicy::class
        );
    }

    public function resolve(Action $action): string|array
    {
        return match ($this->resolvedPolicy->preferredTarget()) {
            RedirectTarget::View => $this->resolveView($action),
            RedirectTarget::Previous => $this->resolvePrevious($action),
            RedirectTarget::Search => $this->resolveSearch($action),
        };
    }

    private function resolveView(Action $action): string|array
    {
        if ($action->collection->count() === 1) {
            return $action->controller->getActionUrl(
                'view',
                ['id' => $action->collection->first->id],
            );
        }

        return $this->resolveSearch($action);
    }

    private function resolveSearch(Action $action): string|array
    {
        return $action->controller->getSearchUrl();
    }

    private function resolvePrevious(Action $action): string|array
    {
        return $action->controller->getPreviousUrl()
            ?? $this->resolveSearch($action);
    }
}
