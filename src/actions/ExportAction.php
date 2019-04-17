<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\actions;

use hipanel\grid\RepresentationCollectionFinder;
use hiqdev\yii2\export\exporters\ExporterFactoryInterface;
use hiqdev\yii2\export\exporters\Type;
use hiqdev\yii2\export\models\CsvSettings;
use hiqdev\yii2\export\models\TsvSettings;
use hiqdev\yii2\export\models\XlsxSettings;
use Yii;

class ExportAction extends IndexAction
{
    /**
     * @var ExporterFactoryInterface
     */
    private $exporterFactory;

    public function __construct($id, $controller, ExporterFactoryInterface $exporterFactory, RepresentationCollectionFinder $representationCollectionFinder, array $config = [])
    {
        parent::__construct($id, $controller, $representationCollectionFinder, $config);
        $this->exporterFactory = $exporterFactory;
    }

    public function run()
    {
        $type = $this->getType();
        $exporter = $this->exporterFactory->build($type);
        $settings = $this->loadSettings($type);
        if ($settings !== null) {
            $settings->applyTo($exporter);
        }
        $representation = $this->ensureRepresentationCollection()->getByName($this->getUiModel()->representation);
        $gridClassName = $this->guessGridClassName();
        $indexAction = null;
        if (isset($this->controller->actions()['index'])) {
            $indexActionConfig = $this->controller->actions()['index'];
            $indexAction = Yii::createObject($indexActionConfig, ['index', $this->controller]);
            $indexAction->beforePerform();
        }
        $dataProvider = $indexAction ? $indexAction->getDataProvider() : $this->getDataProvider();
        $grid = Yii::createObject([
            'class' => $gridClassName,
            'dataProvider' => $dataProvider,
            'columns' => $representation->getColumns(),
        ]);
        $grid->dataColumnClass = \hiqdev\higrid\DataColumn::class;
        $result = $exporter->export($grid);
        $filename = $exporter->filename . '.' . $type;

        return Yii::$app->response->sendContentAsFile($result, $filename);
    }

    public function loadSettings($type)
    {
        $map = [
            Type::CSV => CsvSettings::class,
            Type::TSV => TsvSettings::class,
            Type::XLSX => XlsxSettings::class,
        ];

        $settings = Yii::createObject($map[$type]);
        if ($settings->load(Yii::$app->request->get(), '') && $settings->validate()) {
            return $settings;
        }

        return null;
    }

    protected function getType()
    {
        return Yii::$app->request->get('format');
    }

    /**
     * @throws \Exception
     * @return string
     */
    protected function guessGridClassName()
    {
        $controllerName = ucfirst($this->controller->id);
        $ns = implode(array_diff(explode('\\', get_class($this->controller)), [
            $controllerName . 'Controller', 'controllers',
        ]), '\\');
        $girdClassName = sprintf('\%s\grid\%sGridView', $ns, $controllerName);
        if (class_exists($girdClassName)) {
            return $girdClassName;
        }

        throw new \Exception("ExportAction cannot find a {$girdClassName}");
    }
}
