<?php
namespace hipanel\widgets\filePreview;

use hipanel\widgets\filePreview\types\AbstractPreviewGenerator;
use League\FactoryMuffin\Generators\GeneratorInterface;

interface FilePreviewFactoryInterface
{
    /**
     * @param $path
     * @return AbstractPreviewGenerator
     * @throws UnsupportedMimeTypeException
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
