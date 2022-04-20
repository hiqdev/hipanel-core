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

/**
 * Class PrepareBulkAction.
 */
class PrepareBulkAction extends PrepareAjaxViewAction
{
    /** {@inheritdoc} */
    public function run($id = null)
    {
        $request = Yii::$app->request;
        $selection = $request->get('selection', []);
        if (empty($selection)) {
            $selection = $request->post('selection', []);
        }
        $this->setId($selection);

        return parent::run($id);
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
