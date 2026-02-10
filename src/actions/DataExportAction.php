<?php declare(strict_types=1);

namespace hipanel\actions;

use Generator;
use hiqdev\yii2\export\exporters\ExporterFactoryInterface;
use hiqdev\yii2\export\exporters\ExportType;
use hiqdev\yii2\export\models\ExportJob;
use Yii;
use yii\i18n\Formatter;
use yii\web\Response;

/**
 *
 * @property-read array $columns
 */
abstract class DataExportAction extends Action
{
    protected ?Formatter $formatter = null;
    protected ExportType $exportType = ExportType::CSV;
    private ?ExportJob $exportJob = null;

    public function __construct($id, $controller, readonly private ExporterFactoryInterface $exporterFactory, array $config = [])
    {
        parent::__construct($id, $controller, $config);
    }

    public function run(): Response
    {
        $request = $this->controller->request;
        $params = $request->get(null, []) + $request->post(null, []);

        $exporter = $this->exporterFactory->build($this->exportType);
        $this->formatter = $exporter::applyExportFormatting();

        $exportJobKey = md5(implode('', [$this->controller->request->getAbsoluteUrl(), Yii::$app->user->id, time()]));
        $this->exportJob = ExportJob::findOrCreate($exportJobKey);
        $exporter->setExportJob($this->exportJob);

        $saver = $this->exportJob->getSaver();
        $exporter->exportToFile($saver->getFilePath(), [
            'data' => fn() => $this->generateRows($params),
        ]);

        return $this->controller->response->sendFile($saver->getFilePath(), $saver->getFilename());
    }

    public function __destruct()
    {
        $this->exportJob?->delete();
    }

    /**
     * Returns the columns for the export file.
     *
     * @return array
     */
    abstract protected function getColumns(): array;

    /**
     * Generates rows for the export file.
     *
     * @param array $params Parameters for data generation
     * @return Generator
     */
    abstract protected function generateRows(array $params): Generator;
}
