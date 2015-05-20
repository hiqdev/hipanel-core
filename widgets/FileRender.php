<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\widgets;

use branchonline\lightbox\LightboxAsset;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;
use common\models\File;
use yii\helpers\Url;
use yii\imagine\Image;

class FileRender extends Widget
{
    /**
     * @var string
     */
    public $object_name;

    /**
     * @var int
     */
    public $object_id;

    /**
     * @var array
     */
    public $data = [];

    /**
     * @var int
     */
    public $thumbWidth = 64;

    /**
     * @var int
     */
    public $thumbHeight = 64;

    /**
     * @var int
     */
    public $answer_id;

    /**
     * @var array
     */
    private $extMatch = [
        'pdf'  => '<div><i class="fa fa-file-pdf-o fa-2x"></i></div>',
        'doc'  => '<div><i class="fa fa-file-word-o fa-2x"></i></div>',
        'docx' => '<div><i class="fa fa-file-word-o fa-2x"></i></div>',
        'xls'  => '<div><i class="fa fa-file-excel-o fa-2x"></i></div>',
        'xlsx' => '<div><i class="fa fa-file-excel-o fa-2x"></i></div>',
    ];

    public function init () {
        parent::init();
        if (empty($this->data)) {
            throw new InvalidConfigException('The "data" property must not be empty.');
        }
        if (empty($this->object_id)) {
            throw new InvalidConfigException('The "object_id" property must not be empty.');
        }
        if (empty($this->object_name)) {
            throw new InvalidConfigException('The "object_name" property must not be empty.');
        }
        $this->registerClientScript();
        $this->renderHtml();
    }

    private function registerClientScript () {
        $view = $this->getView();
        LightboxAsset::register($view);
        // Fix: Incorrect resizing of image #122
        $view->registerCss('.lightbox  .lb-image{ max-width: inherit!important; }');
    }

    private function renderHtml () {
        foreach ($this->data as $file) {
            $contentType = $this->getContentType($file['id']);
            if (mb_substr($contentType, 0, 5) == 'image') {
                $base64 = 'data: ' . $contentType . ';base64,' . base64_encode(Image::thumbnail($this->getPathToFile($file['id']), $this->thumbHeight, $this->thumbHeight));
                print Html::beginTag('a', [
                    'href' => $this->getLink($file['id']),
                    'data' => ['lightbox' => 'answer-gal-' . $this->answer_id]
                ]);
                print Html::img($base64, ['class' => 'margin']);
                print Html::endTag('a');
            } else {
                print Html::beginTag('a', ['href' => $this->getLink($file['id'], $file['type'], $contentType)]);
                print Html::tag('div', $this->getExtIcon($file['type']), ['class' => 'margin file']);
                print Html::endTag('a');
            }
        }
    }

    private function getLink ($id, $ext = null, $contentType = null) {
        if (!$ext && !$contentType) {
            return Url::to([
                '/file/view',
                'id'          => $id,
                'object_id'   => $this->object_id,
                'object_name' => $this->object_name,
            ]);
        } else {
            return Url::to([
                '/file/get',
                'id'          => $id,
                'object_id'   => $this->object_id,
                'object_name' => $this->object_name,
                'ext'         => $ext,
                'contentType' => $contentType
            ]);
        }
    }

    private function getContentType ($id) {
        return \yii\helpers\FileHelper::getMimeType($this->getPathToFile($id));
    }

    private function getPathToFile ($id, $render = false) {
        return File::renderFile($id, $this->object_id, $this->object_name, $render);
    }

    private function getExtIcon ($ext) {
        $default = '<div><i class="fa fa-file-text-o fa-2x"></i></div>';
        if (array_key_exists($ext, $this->extMatch)) return $this->extMatch[$ext]; else
            return $default;
    }
}
