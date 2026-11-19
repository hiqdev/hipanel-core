<?php

declare(strict_types=1);

namespace hipanel\module\SmartRedirect\Infrastructure;

use hipanel\actions\SmartUpdateAction;
use hipanel\base\Controller;
use yii\base\Action;
use yii\base\ActionEvent;
use yii\base\Behavior;
use yii\helpers\Url;

/**
 *
 * @property-read null|string $previousUrl
 */
class ReferrerUrlMemoryBehavior extends Behavior
{
    /**
     * Substrings that mark a referrer URL as suitable to be remembered as "previous URL".
     *
     * @var string[]
     */
    public array $suitableReferrerPatterns = ['/index', '/view'];

    public function events(): array
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'rememberUrl',
        ];
    }

    public function rememberUrl(ActionEvent $actionEvent): void
    {
        $request = $actionEvent->sender->request;
        if ($actionEvent->action instanceof SmartUpdateAction && $this->suitableReferrer($request->referrer ?? '')) {
            Url::remember($request->referrer, $this->getUrlKey($actionEvent->action, $request->url));
        }
    }

    private function suitableReferrer(string $referrer): bool
    {
        return array_any($this->suitableReferrerPatterns, fn($pattern) => str_contains($referrer, $pattern));

    }

    private function getUrlKey(Action $action, string $formUrl): string
    {
        $parts = parse_url($formUrl);
        $pathAndQuery = ($parts['path'] ?? '') . (isset($parts['query']) ? '?' . $parts['query'] : '');

        return implode('.', [$this->owner->id, $action->parent->id ?? $action->id, md5($pathAndQuery)]);
    }

    public function getPreviousUrl(Action $action): ?string
    {
        $formUrl = $this->owner->request->referrer;

        return $formUrl === null ? null : Url::previous($this->getUrlKey($action, $formUrl));
    }
}
