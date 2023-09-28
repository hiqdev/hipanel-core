<?php
declare(strict_types=1);

namespace hipanel\behaviors;

use hipanel\client\debt\models\ClientDebtSearch;
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

    public function fetchTags(?string $tagLike = null): mixed
    {
        if (!$this->owner instanceof ClientDebtSearch) {
            return $this->owner->perform('get-available-tags', array_filter(['tags' => $tagLike]));
        }

        return [];
    }

    public function isTagsHidden(): bool
    {
        $user = Yii::$app->user;

        return match (str_replace('search', '', mb_strtolower($this->owner->formName()))) {
            'client' => !$user->can('owner-staff'),
            'contact' => !$user->can('owner-staff'),
            'target' => !$user->can('plan.update'),
            'server' => !$user->can('server.update'),
            'hub' => !$user->can('hub.update'),
            default => true,
        };
    }

    public function isTagsSimpleHidden(): bool
    {
        $user = Yii::$app->user;
        return match (str_replace('search', '', mb_strtolower($this->owner->formName()))) {
            'clientdebt' => !$user->can('owner-staff'),
            default => true,
        };
    }
}
