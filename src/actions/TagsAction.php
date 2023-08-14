<?php
declare(strict_types=1);

namespace hipanel\actions;

use Yii;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Response;

class TagsAction extends Action
{
    private const ERROR_MESSAGE = 'errorMessage';

    public function run()
    {
        $request = $this->controller->request;
        $model = $this->getCollection()->getModel();
        try {
            if ($model->isTagsHidden()) {
                throw new MethodNotAllowedHttpException('No permission to manage Tags');
            }
            if ($request->isGet) {
                $searchQuery = $request->get('tagLike');
                $tags = $this->transformForTreeSelect($model->fetchTags($searchQuery));

                return $this->makeResponse($tags);
            }
            if ($request->isPost) {
                $entityId = $request->post('id', null);
                $model->id = $entityId;
                $tags = $request->post('tags', []);
                $model->saveTags(implode(",", $tags));

                return $this->makeResponse();
            }
        } catch (\Exception $exception) {
            return $this->makeResponse([self::ERROR_MESSAGE => $exception->getMessage()]);
        }
        Yii::$app->end();
    }

    private function transformForTreeSelect($loadTags): array
    {
        $options = [];
        foreach ($loadTags as $tag) {
            $options[] = ['id' => $tag['tag'], 'label' => $tag['tag']];
        }

        return $options;
    }

    private function makeResponse(array $payload = []): Response
    {
        return $this->controller->asJson([
            'hasError' => array_key_exists(self::ERROR_MESSAGE, $payload),
            'data' => $payload,
        ]);
    }
}
