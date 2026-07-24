<?php

declare(strict_types=1);

namespace hipanel\widgets;

use hipanel\base\Model;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 *
 * @property-read string $tableName
 */
class AuditButton extends Widget
{
    public Model $model;
    public bool $rightIcon = false;
    public array $linkOptions = [];

    public function run(): string
    {
        $user = Yii::$app->user;
        if (!$user->can('audit.read')) {
            return '';
        }
        $icon = Html::tag('i', '', ['class' => 'fa fa-history fa-fw ' . ($this->rightIcon ? 'pull-right' : '')]);

        return Html::a(
            implode(' ', [$icon, Yii::t('hipanel', 'History log')]),
            ['@audit/index', 'table' => $this->getTableName(), 'id' => $this->model->id],
            $this->linkOptions
        );
    }

    public function getTableName(): string
    {
        $name = mb_strtolower($this->model->formName());

        return match ($name) {
            'hub', 'server' => 'device',
            'plan' => 'tariff',
            default => $name,
        };
    }
}
