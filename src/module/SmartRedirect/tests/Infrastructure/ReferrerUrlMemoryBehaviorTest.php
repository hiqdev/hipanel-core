<?php

declare(strict_types=1);

namespace hipanel\module\SmartRedirect\tests\Infrastructure;

use hipanel\module\SmartRedirect\Infrastructure\ReferrerUrlMemoryBehavior;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use yii\base\Action;

class ReferrerUrlMemoryBehaviorTest extends TestCase
{
    public function testDefaultPatternsMatchIndexAndView(): void
    {
        $behavior = new ReferrerUrlMemoryBehavior();

        $this->assertTrue($this->isSuitableReferrer($behavior, '/entity/index'));
        $this->assertTrue($this->isSuitableReferrer($behavior, '/entity/view?id=1'));
        $this->assertFalse($this->isSuitableReferrer($behavior, '/entity/create'));
    }

    public function testCustomPatternsOverrideDefaults(): void
    {
        $behavior = new ReferrerUrlMemoryBehavior();
        $behavior->suitableReferrerPatterns = ['/custom'];

        $this->assertTrue($this->isSuitableReferrer($behavior, '/entity/custom'));
        $this->assertFalse($this->isSuitableReferrer($behavior, '/entity/index'));
    }

    public function testFormUrlAndItsRefererShareKey(): void
    {
        $behavior = $this->behaviorOnController('hub');
        $action = new Action('assign-hubs', null);

        $this->assertSame(
            $this->urlKey($behavior, $action, '/server/hub/assign-hubs?id=1'),
            $this->urlKey($behavior, $action, 'https://panel.example/server/hub/assign-hubs?id=1'),
        );
    }

    public function testFormsOfDifferentObjectsHaveDifferentKeys(): void
    {
        $behavior = $this->behaviorOnController('hub');
        $action = new Action('assign-hubs', null);

        $this->assertNotSame(
            $this->urlKey($behavior, $action, '/server/hub/assign-hubs?id=1'),
            $this->urlKey($behavior, $action, '/server/hub/assign-hubs?id=2'),
        );
    }

    private function behaviorOnController(string $controllerId): ReferrerUrlMemoryBehavior
    {
        $behavior = new ReferrerUrlMemoryBehavior();
        $behavior->owner = (object)['id' => $controllerId];

        return $behavior;
    }

    private function urlKey(ReferrerUrlMemoryBehavior $behavior, Action $action, string $formUrl): string
    {
        $method = new ReflectionMethod($behavior, 'getUrlKey');

        return $method->invoke($behavior, $action, $formUrl);
    }

    private function isSuitableReferrer(ReferrerUrlMemoryBehavior $behavior, string $referrer): bool
    {
        $method = new ReflectionMethod($behavior, 'suitableReferrer');

        return $method->invoke($behavior, $referrer);
    }
}
