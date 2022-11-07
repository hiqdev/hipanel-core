<?php

declare(strict_types=1);

namespace hipanel\behaviors;

use ArrayIterator;
use yii\base\Arrayable;
use yii\base\InvalidArgumentException;
use yii\helpers\Json;

class JsonField implements \ArrayAccess, Arrayable, \IteratorAggregate
{
    /**
     * @var array
     */
    protected $value;


    /**
     * @param string|array $value
     */
    public function __construct($value = [])
    {
        $this->set($value);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value ? Json::encode($this->value) : '';
    }

    /**
     * @param string|array $value
     */
    public function set($value): void
    {
        if ($value === null || $value === '') {
            $value = [];
        } elseif (is_string($value)) {
            $value = Json::decode($value, true);
            if (!is_array($value)) {
                throw new InvalidArgumentException('Value is scalar');
            }
        }
        if (!is_array($value)) {
            throw new InvalidArgumentException('Value is not array');
        }
        $this->value = $value;
    }

    /**
     * @inheritdoc
     */
    public function fields(): array
    {
        $fields = array_keys($this->value);

        return array_combine($fields, $fields);
    }

    /**
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function toArray(array $fields = [], array $expand = [], $recursive = true): mixed
    {
        return empty($fields) ? $this->value : array_intersect_key($this->value, array_flip($fields));
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return !$this->value;
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset): bool
    {
        return isset($this->value[$offset]);
    }

    /**
     * @inheritdoc
     */
    public function &offsetGet($offset): mixed
    {
        $null = null;
        if (isset($this->value[$offset])) {
            return $this->value[$offset];
        }

        return $null;
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $this->value[] = $value;
        } else {
            $this->value[$offset] = $value;
        }
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset): void
    {
        unset($this->value[$offset]);
    }

    /**
     * @inheritDoc
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->value);
    }
}
