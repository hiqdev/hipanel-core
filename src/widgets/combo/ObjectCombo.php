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
    public $class_attribute = 'class';

    public $class_attribute_name = 'class';

    public $objectMap = [
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
            'objectOptions' => $this->getObjectOptions(),
            'objects' => $this->getObjects(),
            'class_attribute' => $this->model->{$this->class_attribute},
            'class_attribute_name' => $this->class_attribute_name,
        ]);
    }

    private function getObjects(): array
    {
        $objects = [];
        foreach ($this->objectMap as $type => $options) {
            if (!Yii::getAlias($options['alias'], false)) {
                continue;
            }
            if ($options['combo']) {
                $options['comboOptions'] = get_class_vars($options['combo']);
            }
            $objects[$type] = $options;
        }

        return $objects;
    }

    private function getObjectOptions(): array
    {
        $dropDownOptions = [];
        foreach ($this->getObjects() as $class => $options) {
            $dropDownOptions[$class] = Yii::t('hipanel', $this->getLabel($class));
        }

        return $dropDownOptions;
    }

    private function getLabel($class): string
    {
        return $class === 'device' ? 'Server' : ucfirst($class);
    }
}

