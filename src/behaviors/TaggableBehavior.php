<?php declare(strict_types=1);

namespace hipanel\behaviors;

use hipanel\client\debt\models\Debt;
use hipanel\helpers\ArrayHelper;
use Yii;
use yii\base\Behavior;
use yii\caching\TagDependency;

class TaggableBehavior extends Behavior
{
    private array $tags = [];
    private const string CACHE_TAG = 'hipanel-tags';
    private const int CACHE_DURATION_DAY = 86400;

    public function setTags(string|array|null $tags = null): void
    {
        $this->tags = is_array($tags) ? $tags : ArrayHelper::csplit((string)$tags);
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function saveTags(string $tags): void
    {
        $this->owner->perform(
            'set-tags',
            ['id' => $this->owner->id, 'tags' => $tags]
        );
        TagDependency::invalidate(Yii::$app->cache, self::CACHE_TAG);
    }

    public function fetchTags(?array $searchQuery = null): mixed
    {
        if ($this->owner instanceof Debt) {
            return [];
        }

        return Yii::$app->cache->getOrSet([
            'fetchTags',
            Yii::$app->user->id,
            get_class($this->owner),
            $searchQuery,
        ], function () use ($searchQuery) {
            $tagLike = $searchQuery['tagLike'] ?? null;
            $id = $searchQuery['id'] ?? null;

            return $this->owner->perform('get-available-tags', array_filter(['tags' => $tagLike, 'id' => $id]));
        }, self::CACHE_DURATION_DAY, new TagDependency(['tags' => self::CACHE_TAG]));
    }

    public function isTagsHidden(): bool
    {
        $user = Yii::$app->user;

        return match (str_replace('search', '', mb_strtolower($this->owner->formName()))) {
            'client', 'contact', 'debt' => !$user->can('owner-staff'),
            'target' => !$user->can('plan.update'),
            'server' => !$user->can('server.update'),
            'hub' => !$user->can('hub.update'),
            default => true,
        };
    }

    public function isTagsReadOnly(): bool
    {
        return match (str_replace('search', '', mb_strtolower($this->owner->formName()))) {
            'debt', 'requisite' => true,
            default => false,
        };
    }
}
