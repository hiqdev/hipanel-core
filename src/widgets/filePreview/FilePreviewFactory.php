<?php

namespace hipanel\widgets\filePreview;

use hipanel\helpers\FileHelper;
use hipanel\widgets\filePreview\types\AbstractPreviewGenerator;
use hipanel\widgets\filePreview\types\ImagePreviewGenerator;
use hipanel\widgets\filePreview\types\PdfPreviewGenerator;
use Yii;

class FilePreviewFactory implements FilePreviewFactoryInterface
{
    public $generators = [
        '^image/.*$' => ImagePreviewGenerator::class,
        '^application/pdf$' => PdfPreviewGenerator::class
    ];

    /**
     * @param $path
     * @return AbstractPreviewGenerator
     * @throws UnsupportedMimeTypeException
     */
    public function createGenerator($path)
    {
        $mime = $this->getMimeType($path);

        $className = $this->resolveGeneratorClass($mime);
        if ($className === false) {
            throw new UnsupportedMimeTypeException();
        }

        return Yii::createObject($className, [$path]);
    }

    public function getMimeType($path)
    {
        return FileHelper::getMimeType($path);
    }

    public function resolveGeneratorClass($mimeType)
    {
        foreach ($this->generators as $pattern => $generatorClass) {
            if (preg_match('#' . $pattern . '#', $mimeType)) {
                return $generatorClass;
            }
        }

        return false;
    }
}