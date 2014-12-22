<?php
namespace app\modules\server\widgets;

use app\modules\server\models\Osimage;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\helpers\Html;

/**
 * Class OSFormatter
 *
 * Renders a formatted information about OS
 *
 * @package app\modules\server\widgets
 * @uses yii\bootstrap\Modal
 * @uses app\modules\server\models\Osimage
 * @author SilverFire
 */
class OSFormatter extends Widget {
    public $osimages;

    /**
     * @var \app\modules\server\models\Osimage
     */
    public $osimage;

    /**
     * @var string osimage code-name
     */
    public $imageName;

    /**
     * @var bool whether to display a button with modal pop-up, containing OS soft information
     */
    public $infoCircle = true;

    public function init () {
        parent::init();
        foreach ($this->osimages as $osimage) {
            if ($osimage->osimage == $this->imageName) {
                $this->osimage = $osimage;
                break;
            }
        }
    }

    /**
     * @return string html code of definition list
     */
    public function generateOSInfo () {
        $html = Html::beginTag('dl', ['class' => 'dl-horizontal']);

        foreach ($this->osimage->softpack['soft'] as $item) {
            $html .= Html::tag('dt', $item['name'] . ' ' . $item['version']);
            $html .= Html::tag('dd', $item['description']);
        }

        $html .= Html::endTag('dl');
        return $html;
    }

    /**
     * Renders info-circle with modal popup
     */
    public function generateInfoCircle () {
        Modal::begin([
            'toggleButton'  => [
                'class' => 'fa fa-info-circle text-info os-info-popover',
                'label' => ''
            ],
            'header'        => Html::tag('h4', $this->osimage->getFullOsName()),
            'size'          => Modal::SIZE_LARGE
        ]);
        echo Html::tag('div', $this->generateOSInfo(), [
            'class' => 'row'
        ]);
        Modal::end();
    }

    /**
     * Renders the widget
     */
    public function run () {
        if (!($this->osimage instanceof Osimage)) return $this->imageName;

        echo $this->osimage->getFullOsName();
        echo "&nbsp;";
        if ($this->osimage->hasSoftPack() && $this->infoCircle)
            echo $this->generateInfoCircle();
    }
}
