<?php

namespace hipanel\widgets\combo;

use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\finance\widgets\combo\PlanCombo;
use hipanel\modules\hosting\widgets\combo\AccountCombo;
use hipanel\modules\server\widgets\combo\ServerCombo;
use hipanel\modules\stock\widgets\combo\PartCombo;
use Yii;
use yii\bootstrap\InputWidget;

/**
 * Class BaseObjectSelector
 */
class ObjectCombo extends InputWidget
{
    /**
     * @var string
     */
    public $class_attribute = 'class';

    /**
     * @var string
     */
    public $class_attribute_name = 'class';

    /**
     * @var array
     */
    public $knownClasses = [
        'client' => ['alias' => '@client', 'combo' => ClientCombo::class],
        'device' => ['alias' => '@server', 'combo' => ServerCombo::class],
        'domain' => ['alias' => '@domain'],
        'zone' => ['alias' => '@zone'],
        'part' => ['alias' => '@part', 'combo' => PartCombo::class],
        'account' => ['alias' => '@account', 'combo' => AccountCombo::class],
        'plan' => ['alias' => '@plan', 'combo' => PlanCombo::class],
    ];

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('ObjectCombo', [
            'model' => $this->model,
            'attribute' => $this->attribute,
            'classOptions' => $this->getClassOptions(),
            'classes' => $this->getClasses(),
            'class_attribute' => $this->model->{$this->class_attribute},
            'class_attribute_name' => $this->class_attribute_name,
        ]);
    }

    private function getClasses(): array
    {
        $classes = [];
        foreach ($this->knownClasses as $class => $options) {
            if (!Yii::getAlias($options['alias'], false)) {
                continue;
            }
            if ($options['combo']) {
                $options['comboOptions'] = get_class_vars($options['combo']);
            }
            $classes[$class] = $options;
        }

        return $classes;
    }

    private function getClassOptions(): array
    {
        $dropDownOptions = [];
        foreach ($this->getClasses() as $class => $options) {
            $dropDownOptions[$class] = Yii::t('hipanel.object-combo', $this->getLabel($class));
        }

        return $dropDownOptions;
    }

    /**
     * @param string $class
     * @return string
     */
    private function getLabel($class): string
    {
        return $class === 'device' ? 'Server' : ucfirst($class);
    }
}

