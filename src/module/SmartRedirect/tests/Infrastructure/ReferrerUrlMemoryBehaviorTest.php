<?php

declare(strict_types=1);

namespace hipanel\module\SmartRedirect\tests\Infrastructure;

use hipanel\actions\SmartUpdateAction;
use hipanel\module\SmartRedirect\Infrastructure\ReferrerUrlMemoryBehavior;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Yii;
use yii\base\ActionEvent;
use yii\i18n\PhpMessageSource;
use yii\web\Application;
use yii\web\Controller;
use yii\web\Request;

class ReferrerUrlMemoryBehaviorTest extends TestCase
{
    protected function tearDown(): void
    {
        if (Yii::$app !== null) {
            Yii::$app->errorHandler->unregister();
            Yii::$app = null;
        }
    }

    public function testDefaultPatternsMatchIndexAndView(): void
    {
        $behavior = new ReferrerUrlMemoryBehavior();

        $this->assertTrue($this->isSuitableReferrer($behavior, '/entity/index'));
        $this->assertTrue($this->isSuitableReferrer($behavior, '/entity/view?id=1'));
        $this->assertFalse($this->isSuitableReferrer($behavior, '/entity/create'));
        $this->assertFalse($this->isSuitableReferrer($behavior, 'https://view.example/entity/update?back=/entity/index'));
    }

    public function testCustomPatternsOverrideDefaults(): void
    {
        $behavior = new ReferrerUrlMemoryBehavior();
        $behavior->suitableReferrerPatterns = ['/custom'];

        $this->assertTrue($this->isSuitableReferrer($behavior, '/entity/custom'));
        $this->assertFalse($this->isSuitableReferrer($behavior, '/entity/index'));
    }

    public function testSameFormOpenedInTwoTabsReturnsEachToItsOwnReferrer(): void
    {
        $this->mockApplication();
        $firstForm = '/entity/update?id=1';
        $secondForm = '/entity/update?id=2';

        $this->openForm($firstForm, 'https://panel.example/entity/view?id=1');
        $this->openForm($secondForm, 'https://panel.example/entity/index');

        $this->assertSame('https://panel.example/entity/view?id=1', $this->previousUrlOnSave($firstForm));
        $this->assertSame('https://panel.example/entity/index', $this->previousUrlOnSave($secondForm));
    }

    public function testSavePostedFromViewOrIndexPageReturnsToThatPage(): void
    {
        $this->mockApplication();

        foreach (['https://panel.example/entity/view?id=1', 'https://panel.example/entity/index?page=2'] as $sourcePage) {
            $controller = $this->controllerFor('/entity/update', $sourcePage);
            $controller->action = $this->updateAction($controller);
            $controller->trigger(Controller::EVENT_BEFORE_ACTION, new ActionEvent($controller->action));

            $this->assertSame($sourcePage, $controller->getPreviousUrl($controller->action));
        }
    }

    private function openForm(string $formUrl, string $referrer): void
    {
        $controller = $this->controllerFor($formUrl, $referrer);
        $controller->action = $this->updateAction($controller);
        $controller->trigger(Controller::EVENT_BEFORE_ACTION, new ActionEvent($controller->action));
    }

    private function previousUrlOnSave(string $formUrl): ?string
    {
        $controller = $this->controllerFor($formUrl, 'https://panel.example' . $formUrl);

        return $controller->getPreviousUrl($this->updateAction($controller));
    }

    private function controllerFor(string $url, string $referrer): Controller
    {
        $request = new Request(['cookieValidationKey' => 'test']);
        $request->setUrl($url);
        $request->headers->set('Referer', $referrer);
        Yii::$app->set('request', $request);

        $controller = new Controller('entity', Yii::$app);
        $controller->attachBehavior('referrerUrlMemory', ReferrerUrlMemoryBehavior::class);

        return $controller;
    }

    private function updateAction(Controller $controller): SmartUpdateAction
    {
        return new SmartUpdateAction('update', $controller);
    }

    private function mockApplication(): void
    {
        new Application([
            'id' => 'test',
            'basePath' => dirname(__DIR__, 5),
            'components' => [
                'i18n' => [
                    'translations' => [
                        '*' => ['class' => PhpMessageSource::class],
                    ],
                ],
            ],
        ]);
    }

    private function isSuitableReferrer(ReferrerUrlMemoryBehavior $behavior, string $referrer): bool
    {
        $method = new ReflectionMethod($behavior, 'suitableReferrer');

        return $method->invoke($behavior, $referrer);
    }
}
