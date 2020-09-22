<?php

namespace hipanel\helpers;

use RuntimeException;
use Yii;

class ResourceConfigurator
{
    private string $gridClassName;

    private string $searchModelClassName;

    private string $modelClassName;

    private string $searchView;

    private string $toObjectUrl;

    private string $fullTypePrefix;

    private array $columns = [];

    private static self $configurator;

    protected function __construct()
    {
    }

    /**
     * @return string
     */
    public function getGridClassName(): string
    {
        return $this->gridClassName;
    }

    /**
     * @param string $gridClassName
     * @return ResourceConfigurator
     */
    public function setGridClassName(string $gridClassName): self
    {
        $this->gridClassName = $gridClassName;

        return $this;
    }

    /**
     * @return string
     */
    public function getSearchModelClassName(): string
    {
        return $this->searchModelClassName;
    }

    /**
     * @param string $searchModelClassName
     * @return ResourceConfigurator
     */
    public function setSearchModelClassName(string $searchModelClassName): self
    {
        $this->searchModelClassName = $searchModelClassName;

        return $this;
    }

    /**
     * @return string
     */
    public function getModelClassName(): string
    {
        return $this->modelClassName;
    }

    /**
     * @param string $modelClassName
     * @return ResourceConfigurator
     */
    public function setModelClassName(string $modelClassName): self
    {
        $this->modelClassName = $modelClassName;

        return $this;
    }

    /**
     * @return string
     */
    public function getSearchView(): string
    {
        return $this->searchView;
    }

    /**
     * @param string $searchView
     * @return ResourceConfigurator
     */
    public function setSearchView(string $searchView): self
    {
        $this->searchView = $searchView;

        return $this;
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @param array $columns
     * @return ResourceConfigurator
     */
    public function setColumns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @return string
     */
    public function getToObjectUrl(): string
    {
        return $this->toObjectUrl;
    }

    /**
     * @param string $toObjectUrl
     * @return ResourceConfigurator
     */
    public function setToObjectUrl(string $toObjectUrl): self
    {
        $this->toObjectUrl = $toObjectUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullTypePrefix(): string
    {
        return $this->fullTypePrefix ?? 'overuse';
    }

    /**
     * @param string $fullTypePrefix
     * @return ResourceConfigurator
     */
    public function setFullTypePrefix(string $fullTypePrefix): self
    {
        $this->fullTypePrefix = $fullTypePrefix;

        return $this;
    }

    protected function __clone()
    {
    }

    public function __wakeup()
    {
        throw new RunTimeException('Cannot unserialize singleton');
    }

    public static function build(): self
    {
        if (!isset(self::$configurator)) {
            self::$configurator = new self();
        }

        return self::$configurator;
    }

    public function getFilterColumns(): array
    {
        $columns = [];
        foreach ($this->getColumns() as $type => $label) {
            $columns['overuse,' . $type] = $label;
        }

        return $columns;
    }

    public function getTypes(): array
    {
        return array_keys($this->getColumns());
    }

    public function getModel($params = [])
    {
        return $this->createObject($this->getModelClassName(), $params);
    }

    public function getSearchModel($params = [])
    {
        return $this->createObject($this->getSearchModelClassName(), $params);
    }

    private function createObject(string $className, $params = [])
    {
        return Yii::createObject(array_merge(['class' => $className], $params));
    }

    public function getModelName(): string
    {
        return call_user_func([$this->getModel(), 'modelName']);
    }
}
