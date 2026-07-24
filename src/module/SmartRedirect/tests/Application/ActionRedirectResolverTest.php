<?php

declare(strict_types=1);

namespace hipanel\module\SmartRedirect\tests\Application;

use hipanel\actions\Action;
use hipanel\module\SmartRedirect\Application\ActionRedirectResolver;
use hipanel\module\SmartRedirect\Domain\PreferPreviousRedirectPolicy;
use hipanel\module\SmartRedirect\Domain\PreferSearchRedirectPolicy;
use hipanel\module\SmartRedirect\Domain\PreferViewRedirectPolicy;
use PHPUnit\Framework\TestCase;

class ActionRedirectResolverTest extends TestCase
{
    private const VIEW_URL = '/entity/view?id=42';
    private const SEARCH_URL = '/entity/index';
    private const PREVIOUS_URL = '/entity/index?previous=1';

    // --- Waterfall (no policy): view → previous → search ---

    public function testWaterfallResolvesToViewForSingleItem(): void
    {
        $resolver = new ActionRedirectResolver();

        $this->assertSame(self::VIEW_URL, $resolver->resolve($this->makeAction(itemCount: 1, previousUrl: null)));
    }

    public function testWaterfallSkipsPreviousWhenViewIsResolved(): void
    {
        $resolver = new ActionRedirectResolver();

        // single item AND previous URL available — view must win
        $this->assertSame(self::VIEW_URL, $resolver->resolve($this->makeAction(itemCount: 1, previousUrl: self::PREVIOUS_URL)));
    }

    public function testWaterfallResolvesToPreviousWhenMultipleItems(): void
    {
        $resolver = new ActionRedirectResolver();

        $this->assertSame(self::PREVIOUS_URL, $resolver->resolve($this->makeAction(itemCount: 2, previousUrl: self::PREVIOUS_URL)));
    }

    public function testWaterfallResolvesToSearchWhenMultipleItemsAndNoPreviousUrl(): void
    {
        $resolver = new ActionRedirectResolver();

        $this->assertSame(self::SEARCH_URL, $resolver->resolve($this->makeAction(itemCount: 2, previousUrl: null)));
    }

    // --- PreferViewRedirectPolicy ---

    public function testPreferViewPolicyResolvesToViewForSingleItem(): void
    {
        $resolver = new ActionRedirectResolver(['policy' => new PreferViewRedirectPolicy()]);

        $this->assertSame(self::VIEW_URL, $resolver->resolve($this->makeAction(itemCount: 1, previousUrl: null)));
    }

    public function testPreferViewPolicyFallsBackToSearchForMultipleItems(): void
    {
        $resolver = new ActionRedirectResolver(['policy' => new PreferViewRedirectPolicy()]);

        // with explicit policy, there is no waterfall — view fails → search directly
        $this->assertSame(self::SEARCH_URL, $resolver->resolve($this->makeAction(itemCount: 2, previousUrl: self::PREVIOUS_URL)));
    }

    // --- PreferPreviousRedirectPolicy ---

    public function testPreferPreviousPolicyResolvesToPreviousUrl(): void
    {
        $resolver = new ActionRedirectResolver(['policy' => new PreferPreviousRedirectPolicy()]);

        $this->assertSame(self::PREVIOUS_URL, $resolver->resolve($this->makeAction(itemCount: 1, previousUrl: self::PREVIOUS_URL)));
    }

    public function testPreferPreviousPolicyFallsBackToSearchWhenNoPreviousUrl(): void
    {
        $resolver = new ActionRedirectResolver(['policy' => new PreferPreviousRedirectPolicy()]);

        $this->assertSame(self::SEARCH_URL, $resolver->resolve($this->makeAction(itemCount: 1, previousUrl: null)));
    }

    // --- PreferSearchRedirectPolicy ---

    public function testPreferSearchPolicyAlwaysResolvesToSearch(): void
    {
        $resolver = new ActionRedirectResolver(['policy' => new PreferSearchRedirectPolicy()]);

        $this->assertSame(self::SEARCH_URL, $resolver->resolve($this->makeAction(itemCount: 1, previousUrl: self::PREVIOUS_URL)));
    }

    private function makeAction(int $itemCount, ?string $previousUrl): Action
    {
        $collection = new class($itemCount) {
            public object $first;

            public function __construct(private int $itemCount)
            {
                $this->first = (object)['id' => 42];
            }

            public function count(): int
            {
                return $this->itemCount;
            }
        };

        $controller = new class(self::VIEW_URL, self::SEARCH_URL, $previousUrl) {
            public function __construct(
                private string $viewUrl,
                private string $searchUrl,
                private ?string $previousUrl,
            ) {}

            public function getActionUrl(string $action, array $params = []): string
            {
                return $this->viewUrl;
            }

            public function getSearchUrl(): string
            {
                return $this->searchUrl;
            }

            public function getPreviousUrl(Action $action): ?string
            {
                return $this->previousUrl;
            }
        };

        $action = new class($collection) extends Action {
            public function __construct(private object $collectionStub)
            {
            }

            public function getCollection(): object
            {
                return $this->collectionStub;
            }

            public function run(): mixed
            {
                return null;
            }
        };

        $action->controller = $controller;

        return $action;
    }
}
