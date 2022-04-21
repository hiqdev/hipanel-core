<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets\combo;

use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\client\widgets\combo\PartnerCombo;
use hipanel\modules\domain\widgets\combo\DomainCombo;
use hipanel\modules\domain\widgets\combo\ZoneCombo;
use hipanel\modules\finance\widgets\combo\PlanCombo;
use hipanel\modules\finance\widgets\combo\target\AnycastCDNCombo;
use hipanel\modules\finance\widgets\combo\target\BackupCombo;
use hipanel\modules\finance\widgets\combo\target\PrivateCloudBackupCombo;
use hipanel\modules\finance\widgets\combo\target\PrivateCloudCombo;
use hipanel\modules\finance\widgets\combo\target\SnapshotCombo;
use hipanel\modules\finance\widgets\combo\target\StorageCombo;
use hipanel\modules\finance\widgets\combo\target\VideoCDNCombo;
use hipanel\modules\finance\widgets\combo\target\VolumeCombo;
use hipanel\modules\finance\widgets\combo\target\VPSCombo;
use hipanel\modules\hosting\widgets\combo\AccountCombo;
use hipanel\modules\server\widgets\combo\NetCombo;
use hipanel\modules\server\widgets\combo\RackCombo;
use hipanel\modules\server\widgets\combo\ServerCombo;
use hipanel\modules\server\widgets\combo\LocationCombo;
use hipanel\modules\stock\widgets\combo\ModelCombo;
use hipanel\modules\stock\widgets\combo\ModelGroupCombo;
use hipanel\modules\stock\widgets\combo\PartCombo;
use Yii;
use yii\bootstrap\InputWidget;
use Yiisoft\Strings\Inflector;

/**
 * Class BaseObjectSelector.
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
     * @var string Normally, when $model already has $attribute property filled,
     * and the $attribute is some ID, it will be resolved to name with an AJAX query.
     * In case page that contains this combo has a lot of inputs (e.g. bulk edit form)
     * each will produce an AJAX query, that will last forever.
     *
     * In case $model already has a name, corresponding to that ID (stored in $attribute),
     * you can help Combo by hinting that attribute name and thus preventing AJAX queries.
     */
    public $selectedAttributeName;

    /**
     * @var array
     */
    public array $knownClasses = [
        'client' => ['alias' => '@client', 'combo' => ClientCombo::class],
        'partner' => ['alias' => '@client', 'combo' => PartnerCombo::class],
        'device' => ['alias' => '@server', 'combo' => ServerCombo::class],
        'domain' => ['alias' => '@domain', 'combo' => DomainCombo::class],
        'zone' => ['alias' => '@domain', 'combo' => ZoneCombo::class],
        'part' => ['alias' => '@part', 'combo' => PartCombo::class],
        'account' => ['alias' => '@account', 'combo' => AccountCombo::class],
        'plan' => ['alias' => '@plan', 'combo' => PlanCombo::class],
        'model' => ['alias' => '@model', 'combo' => ModelCombo::class],
        'model_group' => ['alias' => '@model-group', 'combo' => ModelGroupCombo::class],
        'switch' => ['alias' => '@hub', 'combo' => NetCombo::class],
        'rack' => ['alias' => '@hub', 'combo' => RackCombo::class],
        'data_center' => ['alias' => '@hub', 'combo' => LocationCombo::class],
        'anycastcdn' => ['alias' => '@finance', 'combo' => AnycastCDNCombo::class],
        'backup' => ['alias' => '@finance', 'combo' => BackupCombo::class],
        'private_cloud' => ['alias' => '@finance', 'combo' => PrivateCloudCombo::class],
        'private_cloud_backup' => ['alias' => '@finance', 'combo' => PrivateCloudBackupCombo::class],
        'snapshot' => ['alias' => '@finance', 'combo' => SnapshotCombo::class],
        'storage' => ['alias' => '@finance', 'combo' => StorageCombo::class],
        'videocdn' => ['alias' => '@finance', 'combo' => VideoCDNCombo::class],
        'volume' => ['alias' => '@finance', 'combo' => VolumeCombo::class],
        'vps' => ['alias' => '@finance', 'combo' => VPSCombo::class],
    ];

    /**
     * {@inheritdoc}
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
            'selectedAttributeName' => $this->selectedAttributeName,
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
     * @throws \yii\base\InvalidConfigException
     */
    private function getLabel(string $class): string
    {
        return $class === 'device' ? 'Server' : ucwords(Yii::createObject(Inflector::class)->toWords($class));
    }
}
