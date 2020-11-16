<?php

namespace hipanel\widgets;

use hipanel\actions\Action;
use yii\helpers\Json;
use yii\web\View;

trait HookTrait
{
    public ?string $url = null;

    public function registerJsHook(string $reqHeaderParamName): void
    {
        $id = $this->getId();
        $headerName = Action::EXPECTED_AJAX_RESPONSE_HEADER_NAME;
        $url = $this->url ? Json::encode($this->url) : 'document.URL';
        $this->view->registerJs("$.ajax({
           type: 'GET',
           url: {$url},
           beforeSend: xhr => {
             xhr.setRequestHeader('{$headerName}', '{$reqHeaderParamName}');
           },
           success: html => {
             $('#{$id}').html(html);
           }
        });", View::POS_LOAD);
    }
}
