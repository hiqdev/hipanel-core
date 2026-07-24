<?php

declare(strict_types=1);

namespace hipanel\models;

/**
 * @property array $tags
 * @method void setTags(string|array|null $tags = null)
 * @method array getTags()
 * @method void saveTags(string $tags)
 * @method mixed fetchTags(?array $searchQuery = null)
 * @method bool isTagsHidden()
 * @method bool isTagsReadOnly()
 */
interface TaggableInterface
{
}
