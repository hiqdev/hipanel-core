<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 05.03.15
 * Time: 14:38
 */

namespace common\models;

use Yii;
use yii\base\Model;

class Skin extends Model
{
    /**
     * Can have values: fixed|layout-boxed
     * @var string
     */
    public $layout;

    /**
     * Can have values:
     *      skin-blue
     *      skin-yellow
     *      skin-purple
     *      skin-green
     *      skin-red
     *      skin-black
     *      skin-ebony
     * @var string
     */
    public $skin;

    /**
     * Can have value: table-condensed, or blank
     * @var bool
     */
    public $table_condensed;

    /**
     * @var
     */
    public $collapsed_sidebar;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['layout', 'skin', 'table_condensed', 'collapsed_sidebar'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function formLayoutData() {
        return [
            'none' => Yii::t('app', 'Default'),
            'fixed' => Yii::t('app', 'Fixed layout'),
            'layout-boxed' => Yii::t('app', 'Boxed Layout'),
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels() {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * @return array
     */
    public function skinSampleArray() {
        return [
            [
                'attribute' => 'skin-blue',
                'label' => Yii::t('app', 'Skin Blue'),
                'color' => '#367fa9',
                'bg' => 'bg-light-blue'
            ],
            [
                'attribute' => 'skin-black',
                'label' => Yii::t('app', 'Skin Black'),
                'color' => '#fff',
                'bg' => 'bg-black'
            ],
            [
                'attribute' => 'skin-purple',
                'label' => Yii::t('app', 'Skin Purple'),
                'color' => '#367fa9',
                'bg' => 'bg-purple'
            ],
            [
                'attribute' => 'skin-green',
                'label' => Yii::t('app', 'Skin Green'),
                'color' => '#367fa9',
                'bg' => 'bg-green'
            ],
            [
                'attribute' => 'skin-red',
                'label' => Yii::t('app', 'Skin Red'),
                'color' => '#367fa9',
                'bg' => 'bg-red'
            ],
            [
                'attribute' => 'skin-yellow',
                'label' => Yii::t('app', 'Skin Yellow'),
                'color' => '#367fa9',
                'bg' => 'bg-yellow'
            ],
            [
                'attribute' => 'skin-ebony',
                'label' => Yii::t('app', 'Skin Ebony'),
                'color' => '#fff',
                'bg' => 'bg-black'
            ],
        ];
    }

    /**
     * todo: cache implement, AR save method
     * @return bool
     */
    public function saveLayoutSettings() {
        $session = \Yii::$app->session;
        $session->set('user.layout', $this->layout);
        $session->set('user.skin', $this->skin);
        $session->set('user.table_condensed', $this->table_condensed);
        $session->set('user.collapsed_sidebar', $this->collapsed_sidebar);
        return true;
//        return $this->save();
    }

    public function loadLayoutSettings() {
        $session = \Yii::$app->session;
        $this->layout = $session->get('user.layout');
        $this->skin = $session->get('user.skin');
        $this->table_condensed = $session->get('user.table_condensed');
        $this->collapsed_sidebar = $session->get('user.collapsed_sidebar');
    }

    /**
     * @param $entity
     * @param string $default
     * @return mixed|string
     */
    private static function cssClassProvider($entity, $default = '') {
        switch ($entity) {
            case 'skin':
                $lc = \Yii::$app->session->get(sprintf('user.%s', $entity), null);
                return $lc != null ? $lc : $default;
                break;
            case 'layout':
                $lc = \Yii::$app->session->get(sprintf('user.%s', $entity), null);
                return $lc != null ? $lc : $default;
                break;
            case 'table_condensed':
                $lc = \Yii::$app->session->get(sprintf('user.%s', $entity), 0);
                return $lc != 0 ? 'table-condensed' : $default;
                break;
            case 'collapsed_sidebar':
                $lc = \Yii::$app->session->get(sprintf('user.%s', $entity), 0);
                return $lc != 0 ? 'sidebar-collapse' : $default;
                break;
            default:
                throw new \LogicException('Skin class has error input data');
        }
    }

    public static function skinClass() {
        return self::cssClassProvider('skin', \Yii::$app->params['skin']['default-skin']);
    }

    public static function layoutClass() {
        return self::cssClassProvider('layout');
    }

    public static function tableClass() {
        return self::cssClassProvider('table_condensed');
    }

    public static function sidebarClass() {
        return self::cssClassProvider('collapsed_sidebar');
    }
}