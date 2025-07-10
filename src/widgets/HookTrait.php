<?php

declare(strict_types=1);

namespace hipanel\widgets;

use hipanel\actions\Action;
use Yii;
use yii\helpers\Json;

trait HookTrait
{
    public ?string $url = null;

    public function registerJsHook(string $reqHeaderParamName): void
    {
        $id = $this->getId();
        $headerName = Action::EXPECTED_AJAX_RESPONSE_HEADER_NAME;
        $url = $this->url ? Json::encode($this->url) : 'document.URL';
        $post = Json::htmlEncode(Yii::$app->request->post(null, []));
        $this->view->registerJs(
            <<<"JS"
;(() => {
  $.ajax({
    type: 'POST',
    url: $url,
    data: $post,
    beforeSend: xhr => {
      xhr.setRequestHeader('$headerName', '$reqHeaderParamName');
    },
    success: html => {
      $('#$id').html(html);
    }
  });
})();
JS
        );
    }
}
