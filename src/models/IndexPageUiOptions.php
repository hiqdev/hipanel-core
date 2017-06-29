<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\models;

use Yii;
use yii\base\Model;

class IndexPageUiOptions extends Model
{
    const ORIENTATION_VERTICAL = 'vertical';

    const ORIENTATION_HORIZONTAL = 'horizontal';

    public $sort;

    public $per_page;

    public $orientation;

    public $representation;

    public $availableRepresentations = [];

    public function fields()
    {
        return ['sort', 'per_page', 'orientation', 'representation'];
    }

    public function rules()
    {
        return [
            ['per_page', 'default', 'value' => 25],
            ['per_page', 'number', 'skipOnEmpty' => true],

            ['sort', 'default', 'value' => null],
            ['sort', 'string', 'skipOnEmpty' => true],

            ['orientation', 'default', 'value' => $this->getDefaultOrientation()],
            ['orientation', 'in', 'range' => array_keys($this->getOrientationOptions())],

            ['representation', 'default', 'value' => null],
            ['representation', function ($attribute, $params, $validator) {
                if (!empty($this->availableRepresentations) && !in_array($this->{$attribute}, $this->availableRepresentations, true)) {
                    $this->addError($attribute, 'The token must contain letters or digits.');
                }
            }],
        ];
    }

    /**
     * @return array
     */
    public function getOrientationOptions()
    {
        return [
            self::ORIENTATION_HORIZONTAL => Yii::t('hipanel', 'Horizontal'),
            self::ORIENTATION_VERTICAL => Yii::t('hipanel', 'Vertical'),
        ];
    }

    /**
     * @return string
     */
    public function getDefaultOrientation()
    {
        $settings = isset(Yii::$app->themeManager) ? Yii::$app->themeManager->getSettings() : null;
        $orientationOptions = array_keys($this->getOrientationOptions());

        if (isset($settings->filterOrientation) && in_array($settings->filterOrientation, $orientationOptions, true)) {
            return $settings->filterOrientation;
        } else {
            return reset($orientationOptions);
        }
    }

    public function getSortDirection()
    {
        return (strncmp($this->sort, '-', 1) === 0) ? SORT_DESC : SORT_ASC;
    }

    public function getSortAttribute()
    {
        $attribute = $this->sort;
        if (strncmp($attribute, '-', 1) === 0) {
            $attribute = substr($attribute, 1);
        }

        return $attribute;
    }
}
