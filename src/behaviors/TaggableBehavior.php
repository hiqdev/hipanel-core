<?php
declare(strict_types=1);

namespace hipanel\behaviors;

use hipanel\helpers\ArrayHelper;
use yii\base\Behavior;
use yii\web\User;
use Yii;

class TaggableBehavior extends Behavior
{
    private array $tags = [];

    public function setTags(string|array|null $tags = null): void
    {
        $this->tags = is_array($tags) ? $tags : ArrayHelper::csplit($tags);
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
        return $this->owner->perform('get-available-tags', array_filter(['tags' => $tagLike]));
    }

    public function isNotAllowed(): bool
    {
        $user = Yii::$app->user;

        return match (str_replace('search', '', mb_strtolower($this->owner->formName()))) {
            'client' => !$user->can('client.update'),
            'contact' => !$user->can('contact.update'),
            'target' => !$user->can('target.update'),
            'server' => !$user->can('server.update'),
            'hub' => !$user->can('hub.update'),
        };
    }
}
