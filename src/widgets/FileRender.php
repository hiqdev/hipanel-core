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
        $this->addDownloadLinkButton();
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
                return Html::a(Html::tag('i', '', ['class' => 'fa fa-fw fas fa-download']), $this->getLink(), ['target' => '_blank']);
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

    private function addDownloadLinkButton()
    {
        $this->view->registerCss(<<<CSS
            .lb-data .lb-download-link, .lb-data .lb-download-link:hover {
                background: none;
                float: left;
                color: #d7d7d7;
                display: block;
                width: 30px;
                height: 30px;
                text-align: right;
                outline: none;
                filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=70);
                opacity: 0.7;
                -webkit-transition: opacity 0.2s;
                -moz-transition: opacity 0.2s;
                -o-transition: opacity 0.2s;
                transition: opacity 0.2s;
            }
CSS
);
        $this->view->registerJs(<<<JS
        if ($('.lb-closeContainer').length) {
            $('.lb-closeContainer').append('<a class="lb-download-link"><i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i></a>');
            $('.lb-download-link').on('click', function(e) {
                var win = window.open(e.currentTarget.attributes.href.value);
                win.focus();
            });
            var target = document.querySelector('.lb-image');
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    var src = mutation.target.attributes.src.value.replace('/file/', '/file/get/');
                    $('a.lb-download-link').attr({href: src});
                });
            });
            var config = { attributes: true, childList: false, characterData: true };
            observer.observe(target, config);
        }
JS
         );
    }
}
