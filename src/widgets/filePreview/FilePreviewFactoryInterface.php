<?php
namespace hipanel\widgets\filePreview;

use hipanel\widgets\filePreview\types\AbstractPreviewGenerator;

interface FilePreviewFactoryInterface
{
    /**
     * @param $path
     * @return AbstractPreviewGenerator
     * @throws UnsupportedMimeTypeException
     */
    public function createGenerator($path);

    public function getMimeType($path);

    public function resolveGeneratorClass($mimeType);
}