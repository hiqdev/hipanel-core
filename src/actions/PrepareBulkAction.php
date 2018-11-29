<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\actions;

use Yii;

/**
 * Class PrepareBulkAction.
 */
class PrepareBulkAction extends PrepareAjaxViewAction
{
    /** {@inheritdoc} */
    public function run($id = null)
    {
        $this->setId(Yii::$app->request->get('selection', []));

        return parent::run();
    }

    /** {@inheritdoc} */
    public function getDataProvider()
    {
        parent::getDataProvider();

        if (empty($this->dataProvider->query->limit)) {
            $this->dataProvider->query->limit(-1);
        }

        return $this->dataProvider;
    }
}
