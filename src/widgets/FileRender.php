<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

use hipanel\components\FileStorage;
use hipanel\helpers\ArrayHelper;
use hipanel\helpers\FileHelper;
use hipanel\models\File;
use hiqdev\assets\lightbox2\LightboxAsset;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\imagine\Image;

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

    /**
     * @var array
     */
    private $extMatch = [
        'pdf' => '<div><i class="fa fa-file-pdf-o fa-2x"></i></div>',
        'doc' => '<div><i class="fa fa-file-word-o fa-2x"></i></div>',
        'docx' => '<div><i class="fa fa-file-word-o fa-2x"></i></div>',
        'xls' => '<div><i class="fa fa-file-excel-o fa-2x"></i></div>',
        'xlsx' => '<div><i class="fa fa-file-excel-o fa-2x"></i></div>',
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
        $this->renderHtml();
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

        $filename = $this->fileStorage->get($file);
        $contentType = $this->getContentType($file->id);
        if (mb_substr($contentType, 0, 5) === 'image') {
            $thumb = Image::thumbnail($filename, $this->thumbHeight, $this->thumbHeight);
            $base64 = 'data: ' . $contentType . ';base64,' . base64_encode($thumb);
            echo Html::a(
                Html::img($base64, ['class' => 'margin']),
                $this->getLink(),
                ArrayHelper::merge(['data-lightbox' => 'file-' . $file->id], $this->lightboxLinkOptions)
            );
        } else {
            echo Html::a(
                Html::tag('div', $this->getExtIcon($file->type), ['class' => 'margin file']),
                $this->getLink(true)
            );
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

    private function getContentType($id)
    {
        $path = $this->fileStorage->get($id);

        return FileHelper::getMimeType($path);
    }

    private function getExtIcon($ext)
    {
        $default = '<div><i class="fa fa-file-text-o fa-2x"></i></div>';
        if (array_key_exists($ext, $this->extMatch)) {
            return $this->extMatch[$ext];
        } else {
            return $default;
        }
    }
}
