<?php
declare(strict_types=1);

namespace hipanel\behaviors;

use Exception;
use hipanel\helpers\ArrayHelper;
use hipanel\models\Tag;
use yii\base\Behavior;

/**
 *
 * @property-read Tag[] $tagsObjects
 */
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

    /**
     * @return Tag[]
     */
    public function getTagsObjects(): array
    {
        $tags = [];
        foreach ($this->tags as $tagValue) {
            $tag = new Tag();
            $tag->setAttributes([
                'tag' => $tagValue,
                'frequency' => $tagValue,
            ]);
            $tags[] = $tag;
        }

        return $tags;
    }

    public function saveTags(string $id, string $tags): void
    {
        $this->owner->perform(
            'set-tags',
            ['id' => $id, 'tags' => $tags]
        );
    }

    public function fetchTags(?string $tagLike = null): mixed
    {
        return $this->owner->perform('get-available-tags', array_filter(['tags' => $tagLike]), ['batch' => true]);
    }
}
