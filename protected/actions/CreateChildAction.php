<?php
declare(strict_types=1);

namespace prime\actions;

use prime\components\NotificationService;
use prime\helpers\ModelHydrator;
use prime\interfaces\CreateModelRepositoryInterface;
use prime\interfaces\RetrieveReadModelRepositoryInterface;
use prime\values\IntegerId;
use yii\base\Action;
use yii\web\BadRequestHttpException;
use yii\web\Request;

class CreateChildAction extends Action
{
    public string $view = 'create';
    public \Closure $context;
    public string $paramName = 'parent_id';

    public function __construct(
        $id,
        $controller,
        private CreateModelRepositoryInterface $repository,
        private RetrieveReadModelRepositoryInterface $parentRepository,
        private ModelHydrator $modelHydrator,
        $config = []
    ) {
        parent::__construct($id, $controller, $config);
    }

    public function run(Request $request)
    {
        if (null === $id = $request->getQueryParam($this->paramName)) {
            throw new BadRequestHttpException("Missing required parameter {$this->paramName}");
        }
        $id = new IntegerId((int) $id);

        $parentModel = $this->parentRepository->retrieveForRead($id);
        // TODO: Add permission check.
        $model = $this->repository->createFormModel($id);

        if ($request->isPost) {
            $this->modelHydrator->hydrateFromRequestBody($model, $request);
            if ($model->validate()) {
                $createdId = $this->repository->create($model);
                return $this->controller->redirect(['update', 'id' => $createdId]);
            }
        }
        return $this->controller->render($this->view, ['model' => $model, 'parent' => $parentModel]);
    }
}
