<?php
declare(strict_types=1);

namespace hipanel\behaviors;

use hipanel\helpers\ArrayHelper;
use hipanel\models\Tag;
use yii\base\Behavior;
use yii\web\User;

class TaggableBehavior extends Behavior
{
    private array $tags = [];

    public function __construct(private readonly User $user, $config)
    {
        parent::__construct($config);
    }

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
        return $this->owner->perform('get-available-tags', array_filter(['tags' => $tagLike]), ['batch' => true]);
    }

    public function isNotAllowed(): bool
    {
        return match (str_replace('search', '', mb_strtolower($this->owner->formName()))) {
            'client' => !$this->user->can('client.update'),
            'contact' => !$this->user->can('contact.update'),
            'target' => !$this->user->can('target.update'),
            'server' => !$this->user->can('server.update'),
            'hub' => !$this->user->can('hub.update'),
        };
    }
}
