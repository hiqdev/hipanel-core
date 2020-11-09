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

use Yii;

class VariantsAction extends RenderAction
{
    public array $variants;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $headerName = $this->controller->request->headers->get(self::EXPECTED_AJAX_RESPONSE_HEADER_NAME);
        if (isset($this->variants[$headerName])) {
            return $this->variants[$headerName]($this);
        }
            if ($expected === $headerName) {
                return $variant($this);
            }
        }
        Yii::$app->end();
    }
}
