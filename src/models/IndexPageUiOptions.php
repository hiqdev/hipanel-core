<?php

namespace hipanel\models;

use Yii;
use yii\base\Model;

class IndexPageUiOptions extends Model
{
    public $sort;

    public $per_page;

    public $orientation;

    public $representation;

    public $representationOptions = [];

    public function fields()
    {
        return ['sort', 'per_page', 'orientation', 'representation'];
    }

    public function rules()
    {
        return [
            ['per_page', 'default', 'value' => 25],
            ['per_page', 'number', 'skipOnEmpty' => true],

            ['sort', 'string', 'skipOnEmpty' => true],

            ['orientation', 'default', 'value' => $this->getDefaultOrientation()],
            ['orientation', 'in', 'range' => array_keys($this->getOrientationOptions())],

            ['representation', 'in', 'range' => $this->representationOptions, 'skipOnEmpty' => true],
        ];
    }

    /**
     * @return array
     */
    public function getOrientationOptions()
    {
        return [
            'horizontal' => Yii::t('hipanel', 'Horizontal'),
            'vertical' => Yii::t('hipanel', 'Vertical'),
        ];
    }

    /**
     * @return string
     */
    public function getDefaultOrientation()
    {
        $settings = Yii::$app->themeManager->getSettings();
        $orientationOptions = array_keys($this->getOrientationOptions());

        if (property_exists($settings, 'filterOrientation') && in_array($settings->filterOrientation, $orientationOptions, true)) {
            return $settings->filterOrientation;
        } else {
            return reset($orientationOptions);
        }
    }
}
