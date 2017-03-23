<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

use hipanel\components\FileStorage;
use hipanel\helpers\ArrayHelper;
use hipanel\models\File;
use hipanel\widgets\filePreview\Dimensions;
use hipanel\widgets\filePreview\FilePreviewFactoryInterface;
use hipanel\widgets\filePreview\InsetDimensions;
use hipanel\widgets\filePreview\types\PdfPreviewGenerator;
use hipanel\widgets\filePreview\UnsupportedMimeTypeException;
use hiqdev\assets\lightbox2\LightboxAsset;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @property mixed iconOptions
 */
class FileRender extends Widget
{
    /**
     * @var File
     */
    public $file;

    /**
     * @var int
     */
    public $thumbWidth = 64;

    /**
     * @var int
     */
    public $thumbHeight = 64;

    /**
     * @var string Name of the file storage component
     */
    public $fileStorageComponent = 'fileStorage';

    /**
     * @var FileStorage
     */
    protected $fileStorage;

    /**
     * @var array Options that will be passed to [[Html::a()]] for the image lightbox
     */
    public $lightboxLinkOptions = [];

    public $imageOptions = [];

    public $iconOptions = [];

    /**
     * @var array
     */
    private $extMatch = [
        'pdf' => 'fa-file-pdf-o',
        'doc' => 'fa-file-word-o',
        'docx' => 'fa-file-word-o',
        'xls' => 'fa-file-excel-o',
        'xlsx' => 'fa-file-excel-o',
    ];

    public function init()
    {
        parent::init();

        if (!($this->file instanceof File)) {
            throw new InvalidConfigException('The "file" property must instance of File class.');
        }
        $this->fileStorage = Yii::$app->get($this->fileStorageComponent);
    }

    public function run()
    {
        $this->registerClientScript();
        return $this->renderHtml();
    }

    private function registerClientScript()
    {
        $view = $this->getView();
        LightboxAsset::register($view);
        // Fix: Incorrect resizing of image #122
        $view->registerCss('.lightbox  .lb-image { max-width: inherit!important; }');
    }

    private function renderHtml()
    {
        $file = $this->file;
        $path = $this->fileStorage->get($file);

        /** @var FilePreviewFactoryInterface $factory */
        $factory = Yii::createObject(FilePreviewFactoryInterface::class);
        try {
            $generator = $factory->createGenerator($path);
            $dimensions = new InsetDimensions($generator->getDimensions(), new Dimensions($this->thumbWidth, $this->thumbWidth));
            $src = 'data: ' . $generator->getContentType() . ';base64,' . base64_encode($generator->asBytes($dimensions));
            if ($generator instanceof PdfPreviewGenerator) {
                return Html::a(Html::img($src, $this->imageOptions), $this->getLink(), ['target' => '_blank']);
            } else {
                $linkOptions = ArrayHelper::merge(['data-lightbox' => 'file-' . $file->id], $this->lightboxLinkOptions);
                return Html::a(Html::img($src, $this->imageOptions), $this->getLink(), $linkOptions);
            }
        } catch (UnsupportedMimeTypeException $e) {
            return Html::a($this->getExtIcon($file->type), $this->getLink(true));
        }
    }

    private function getLink($download = false)
    {
        return Url::to($this->getRoute($download));
    }

    /**
     * @param string $download whether to return route to download page
     *
     * @return array
     */
    public function getRoute($download)
    {
        if ($download) {
            return ['/file/get', 'id' => $this->file->id];
        }

        return ['/file/view', 'id' => $this->file->id];
    }

    private function getExtIcon($ext)
    {
        $defaultIcon = 'fa-file-text-o';
        $icon =  array_key_exists($ext, $this->extMatch) ? $this->extMatch[$ext] : $defaultIcon;
        $iconClasses = 'fa ' . $icon;
        Html::addCssClass($this->iconOptions, $iconClasses);

        return  Html::tag('i', null, $this->iconOptions);
    }
}
