<?php

namespace hipanel\widgets;

use hipanel\modules\mailing\renderers\AbstractRedirectFormRenderer;
use yii\helpers\Html;
use yii\web\JsExpression;

class RedirectFormRenderer extends AbstractRedirectFormRenderer
{
    /**
     * @var static URL for form submission
     */
    private $url;

    /**
     * RedirectFormRenderer constructor.
     * @param $list
     * @param $url
     */
    public function __construct($list, $url)
    {
        parent::__construct($list);
        $this->url = $url;
    }

    /**
     * @return string
     */
    protected function renderHtml()
    {
        $html[] = Html::beginForm($this->url, 'post', ['id' => 'redirect-form']);
        $html[] = Html::hiddenInput('list', $this->list);
        $html[] = Html::submitInput('submit', ['style' => 'display: none']);
        $html[] = Html::endForm();
        $html[] = "<script>document.getElementById('redirect-form').submit()</script>";

        return implode('', $html);
    }
}
