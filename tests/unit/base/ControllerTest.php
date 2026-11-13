<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\tests\unit\base;

use hipanel\base\Controller;
use Yii;
use yii\i18n\PhpMessageSource;
use yii\web\Application;

class ControllerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Controller
     */
    protected $object;

    protected function setUp(): void
    {
        new Application([
            'id' => 'test',
            'basePath' => dirname(__DIR__, 3),
            'components' => [
                'request' => ['cookieValidationKey' => 'test'],
                'i18n' => [
                    'translations' => [
                        '*' => ['class' => PhpMessageSource::class],
                    ],
                ],
            ],
        ]);
        $this->object = new Controller('test', Yii::$app);
    }

    protected function tearDown(): void
    {
        Yii::$app->errorHandler->unregister();
        Yii::$app = null;
    }

    public function testCreated()
    {
        $this->assertInstanceOf(Controller::class, $this->object);
    }
}
