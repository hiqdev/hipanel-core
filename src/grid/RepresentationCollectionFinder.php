<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\grid;

use hiqdev\higrid\representations\RepresentationCollection;
use hiqdev\higrid\representations\RepresentationCollectionInterface;
use ReflectionClass;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Inflector;

/**
 * Class RepresentationCollectionFinder helps to find a representation collection class
 * depending on [[module]] and [[controller]] name.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class RepresentationCollectionFinder implements RepresentationCollectionFinderInterface
{
    private string $moduleId;
    private string $controllerId;
    private string $representationsLocation; // TODO: declare format. example: '%s\hipanel\modules\%s\grid\%sRepresentations'
    private ?string $vendor;

    public function __construct(
        string $moduleId,
        string $controllerId,
        string $representationsLocation,
        ?string $vendor = null,
    )
    {
        $this->representationsLocation = $representationsLocation;
        $this->controllerId = $controllerId;
        $this->moduleId = $moduleId;
        $this->vendor = $vendor;
    }

    protected function buildClassName(): string
    {
        return sprintf($this->representationsLocation, $this->vendor, $this->moduleId, $this->controllerId);
    }

    /**
     * @return RepresentationCollectionInterface|RepresentationCollection
     */
    public function find()
    {
        $representationsClass = $this->buildClassName();

        if (!class_exists($representationsClass)) {
            return null;
        }

        return Yii::createObject(['class' => $representationsClass]);
    }

    /**
     * @return RepresentationCollectionInterface|RepresentationCollection
     */
    public function findOrFallback()
    {
        $collection = $this->find();

        if ($collection === null) {
            $collection = new RepresentationCollection();
        }

        return $collection;
    }

    /**
     * @throws InvalidConfigException When collection does not exist for the route
     * @return RepresentationCollection|RepresentationCollectionInterface
     */
    public function findOrFail()
    {
        $collection = $this->find();
        if ($collection === null) {
            throw new InvalidConfigException('Representation class "' . $this->buildClassName() . '" does not exist');
        }

        return $collection;
    }

    public static function forCurrentRoute(string $representationsLocation): RepresentationCollectionFinderInterface
    {
        $controller = Yii::$app->controller;

        if ($controller->module instanceof RepresentationCollectionFinderProviderInterface) {
            return $controller->module->getRepresentationCollectionFinder();
        }

        $module = $controller->module->id;
        $controllerId = Inflector::id2camel($controller->id);
        $vendor = explode('\\', (new ReflectionClass($controller))->getNamespaceName())[0];

        return new static($module, $controllerId, $representationsLocation, $vendor === 'hipanel' ? null : $vendor);
    }

    public function getRepresentationsLocation(): string
    {
        return $this->representationsLocation;
    }

    public function setRepresentationsLocation(string $representationsLocation): void
    {
        $this->representationsLocation = $representationsLocation;
    }
}
