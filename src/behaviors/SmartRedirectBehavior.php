<?php


declare(strict_types=1);


namespace hipanel\behaviors;

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
class SmartRedirectBehavior extends Behavior
{
    public function events(): array
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'rememberUrl',
        ];
    }

    public function rememberUrl(ActionEvent $actionEvent): void
    {
        $controller = $actionEvent->sender;
        if ($actionEvent->action instanceof SmartUpdateAction && $this->suitableReferrer($controller->request->referrer)) {
            $url = $controller->request->referrer;
            $key = $this->getUrlKey($this->owner, $this->owner->action);
            Url::remember($url, $key);
        }
    }

    public function getPreviousUrl(Action $action): ?string
    {
        $key = $this->getUrlKey($this->owner, $action);

        return Url::previous($key);
    }

    private function getUrlKey(Controller $controller, Action $action): string
    {
        return implode('.', [$controller->id, $action->id]);
    }

    private function suitableReferrer(string $referrer): bool
    {
        return str_contains($referrer, '/index'); // todo: make it configurable?
    }
}
