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
    private $module;
    private $controller;
    /**
     * @var string
     * // TODO: declare format. example: '\hipanel\modules\%s\grid\%sRepresentations'
     */
    private string $representationsLocation;

    public function __construct($module, $controller, string $representationsLocation)
    {
        $this->module = $module;
        $this->controller = $controller;
        $this->representationsLocation = $representationsLocation;
    }

    protected function buildClassName()
    {
        return sprintf($this->representationsLocation, $this->module, $this->controller);
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
        $controller = Inflector::id2camel($controller->id);

        return new static($module, $controller, $representationsLocation);
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
