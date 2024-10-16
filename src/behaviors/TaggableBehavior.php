<?php

declare(strict_types=1);

namespace hipanel\behaviors;

use hipanel\client\debt\models\Debt;
use hipanel\helpers\ArrayHelper;
use yii\base\Behavior;
use Yii;

class TaggableBehavior extends Behavior
{
    private array $tags = [];

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
    }

    public function fetchTags(?array $searchQuery = null): mixed
    {
        if (!$this->owner instanceof Debt) {
            $tagLike = $searchQuery['tagLike'] ?? null;
            $id = $searchQuery['id'] ?? null;

            return $this->owner->perform('get-available-tags', array_filter(['tags' => $tagLike, 'id' => $id]));
        }

        return [];
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
