<?php

namespace hipanel\grid;

use hiqdev\higrid\representations\RepresentationCollection;
use hiqdev\higrid\representations\RepresentationCollectionInterface;
use yii\base\InvalidConfigException;

/**
 * Interface RepresentationCollectionFinderInterface provides an API to find
 * representation collections in conformance to the current HTTP request
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
interface RepresentationCollectionFinderInterface
{
    /**
     * @return RepresentationCollectionInterface|RepresentationCollection
     */
    public function find();

    /**
     * @throws InvalidConfigException When collection does not exist for the route
     * @return RepresentationCollection|RepresentationCollectionInterface
     */
    public function findOrFail();

    /**
     * @return RepresentationCollectionInterface|RepresentationCollection
     */
    public function findOrFallback();
}
