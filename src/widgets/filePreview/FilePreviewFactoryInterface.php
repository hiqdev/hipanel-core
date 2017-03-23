<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets\filePreview;

use hipanel\widgets\filePreview\types\AbstractPreviewGenerator;
use League\FactoryMuffin\Generators\GeneratorInterface;

interface FilePreviewFactoryInterface
{
    /**
     * @param $path
     * @throws UnsupportedMimeTypeException
     * @return AbstractPreviewGenerator
     */
    public function createGenerator($path);

    /**
     * @param string $path
     * @return string
     */
    public function getMimeType($path);

    /**
     * @param $mimeType
     * @return GeneratorInterface
     */
    public function resolveGeneratorClass($mimeType);
}
