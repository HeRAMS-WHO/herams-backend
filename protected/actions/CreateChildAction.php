<?php
declare(strict_types=1);

namespace prime\actions;

use prime\components\NotificationService;
use prime\helpers\ModelHydrator;
use prime\interfaces\RepositoryInterface;
use prime\values\IntegerId;
use yii\base\Action;
use yii\web\Request;

/**
 * Class CreateChildAction
 * @package prime\actions
 */
class CreateChildAction extends Action
{
    public string $view = 'create';

    public function __construct($id, $controller, private RepositoryInterface $repository, $config = [])
    {
        parent::__construct($id, $controller, $config);
    }


    public function run(Request $request, int $parent_id)
    {
        $id = new IntegerId($parent_id);
        $model = $this->repository->createFormModel($id);

        if ($request->isPost) {
            (new ModelHydrator())->hydrateFromRequestBody($model, $request);
            if ($model->validate()) {
                $createdId = $this->repository->create($model);
                return $this->controller->redirect(['update', 'id' => $createdId]);
            }
        }

        return $this->controller->render($this->view, ['model' => $model]);
    }
}
