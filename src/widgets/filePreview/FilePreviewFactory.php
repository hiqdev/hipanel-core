<?php

namespace hipanel\widgets\filePreview;

use hipanel\helpers\FileHelper;
use hipanel\widgets\filePreview\types\ImagePreviewGenerator;
use hipanel\widgets\filePreview\types\PdfPreviewGenerator;
use hipanel\widgets\filePreview\types\PreviewGeneratorInterface;
use Yii;

/**
 * Class FilePreviewFactory creates File
 */
class FilePreviewFactory implements FilePreviewFactoryInterface
{
    public $generators = [
        '^image/.*$' => ImagePreviewGenerator::class,
        '^application/pdf$' => PdfPreviewGenerator::class
    ];

    /**
     * @param string $path
     * @return PreviewGeneratorInterface
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

    /**
     * @inheritdoc
     */
    public function getMimeType($path)
    {
        return FileHelper::getMimeType($path);
    }

    /**
     * @inheritdoc
     */
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
