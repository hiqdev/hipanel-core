<?php
namespace app\modules\server\widgets;

use app\modules\server\models\Osimage;
use yii\base\Widget;
use yii\helpers\Html;

class OSFormatter extends Widget {
    public $osimages;

    /**
     * @var \app\modules\server\models\Osimage
     */
    public $osimage;
    public $imageName;

    public function init () {
        parent::init();
        foreach ($this->osimages as $osimage) {
            if ($osimage->osimage == $this->imageName) {
                $this->osimage = $osimage;
                break;
            }
        }
    }

    public function generateOSInfo () {
        $html = Html::beginTag('dl', ['class' => 'dl-horizontal']);

        foreach ($this->osimage->softpack['soft'] as $item) {
            $html .= Html::tag('dt', $item['name'] . ' ' . $item['version']);
            $html .= Html::tag('dd', $item['description']);
        }

        $html .= Html::endTag('dl');
        return $html;
    }

    public function generateInfoCircle () {
        $this->getView()->registerJs("$('.os-info-popover').popover({html: true});", \yii\web\View::POS_READY, 'os-info-popover');
        return Html::tag('button',
            ' ',
            [
                'class' => 'fa fa-info-circle text-info os-info-popover',
                'title' => Html::tag('h4', $this->osimage->getFullOsName()),
                'data'  => [
                    //'trigger' => 'focus',
                    'content' => Html::tag('div', $this->generateOSInfo())
                ]
            ]);
    }

    public function run () {
        if (!($this->osimage instanceof Osimage)) return $this->imageName;

        $html = $this->osimage->getFullOsName();

        if ($this->osimage->hasSoftPack()) {
            $html .= "&nbsp;&nbsp;" . $this->generateInfoCircle();
        }

        return $html;
    }
}
