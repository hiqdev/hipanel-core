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

class ControllerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Controller
     */
    protected $object;

    protected function setUp(): void
    {
        $this->object = new Controller('test', null);
    }

    protected function tearDown(): void
    {
    }

    public function testCreated()
    {
        $this->assertInstanceOf(Controller::class, $this->object);
    }
}
