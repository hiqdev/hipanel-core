<?php

declare(strict_types=1);

namespace hipanel\module\SmartRedirect\Application;

use hipanel\actions\Action;
use hipanel\module\SmartRedirect\Domain\PostActionRedirectPolicy;
use hipanel\module\SmartRedirect\Domain\RedirectTarget;
use yii\base\Component;
use yii\di\Instance;

class ActionRedirectResolver extends Component
{
    public PostActionRedirectPolicy|string|array|null $policy = null;

    private ?PostActionRedirectPolicy $resolvedPolicy = null;

    public function init(): void
    {
        parent::init();

        if ($this->policy !== null) {
            $this->resolvedPolicy = Instance::ensure(
                $this->policy,
                PostActionRedirectPolicy::class
            );
        }
    }

    public function resolve(Action $action): string|array
    {
        return $this->findPreferredUrl($action) ?? $this->resolveSearch($action);
    }

    private function findPreferredUrl(Action $action): string|array|null
    {
        if ($this->resolvedPolicy !== null) {
            return $this->findByPolicy($action);
        }

        return $this->resolveView($action) ?? $this->resolvePrevious($action);
    }

    private function findByPolicy(Action $action): string|array|null
    {
        return match ($this->resolvedPolicy->preferredTarget()) {
            RedirectTarget::View => $this->resolveView($action),
            RedirectTarget::Previous => $this->resolvePrevious($action),
            RedirectTarget::Search => $this->resolveSearch($action),
        };
    }

    private function resolveView(Action $action): string|array|null
    {
        if ($action->collection->count() === 1) {
            return $action->controller->getActionUrl(
                'view',
                ['id' => $action->collection->first->id],
            );
        }

        return null;
    }

    private function resolvePrevious(Action $action): string|array|null
    {
        return $action->controller->getPreviousUrl($action);
    }

    private function resolveSearch(Action $action): string|array
    {
        return $action->controller->getSearchUrl();
    }
}
