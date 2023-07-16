<?php
declare(strict_types=1);

namespace hipanel\actions;

use yii\web\Response;

class TagsAction extends Action
{
    public function run()
    {
        $request = $this->controller->request;
        $model = $this->getCollection()->getModel();
        try {
            if ($request->isGet) {
                $searchQuery = $request->get('tagLike');
                $tags = $this->transformForTreeSelect($model->fetchTags($searchQuery));

                return $this->makeResponse(true, $tags);
            }
            if ($request->isPost) {
                $entityId = $request->post('id', null);
                $tags = $request->post('tags', []);
                $model->saveTags($entityId, implode(",", $tags));

                return $this->makeResponse(true);
            }
        } catch (\Exception $exception) {
            return $this->makeResponse(false, ['errorMessage' => $exception->getMessage()]);
        }
    }

    private function transformForTreeSelect($loadTags): array
    {
        $options = [];
        foreach ($loadTags as $tag) {
            $options[] = ['id' => $tag['tag'], 'label' => $tag['tag']];
        }

        return $options;
    }

    private function makeResponse(bool $isError, array $payload = []): Response
    {
        return $this->controller->asJson([
            'hasError' => !$isError,
            'data' => $payload,
        ]);
    }

    private function initAvailableTags(): array
    {

    }
}
