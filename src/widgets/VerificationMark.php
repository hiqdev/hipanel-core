<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

use hipanel\modules\client\models\Verification;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;

class VerificationMark extends Widget
{
    const COLOR_VERIFIED = '#00a65a';
    const COLOR_CONFIRMED = '#54b5ff';
    const COLOR_UNCONFIRMED = '#b3b3b3';

    /**
     * @var Verification
     */
    public $model;

    public function init()
    {
        if (!$this->model instanceof Verification) {
            throw new InvalidConfigException(Yii::t('hipanel', 'Attribute "model" must be instance of Verification model.'));
        }
    }

    public function run()
    {
        return Html::tag('i', null, [
            'class' => 'fa fa-fw fa-lg fa-check-circle pull-right verification-tooltip',
            'style' => 'color: ' . $this->getColor(),
            'title' => $this->model->getLabels()[$this->model->level],
            'data' => [
                'toggle' => 'tooltip',
                'trigger' => 'hover',
            ],
        ]);
    }

    protected function getColor()
    {
        if ($this->model->isVerified()) {
            return static::COLOR_VERIFIED;
        } elseif ($this->model->isConfirmed()) {
            return static::COLOR_CONFIRMED;
        }

        return static::COLOR_UNCONFIRMED;
    }
}
