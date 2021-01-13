<?php


namespace prime\controllers\project;

use prime\components\Controller;
use prime\models\search\Project as ProjectSearch;
use SamIT\abac\interfaces\Resolver;
use SamIT\abac\repositories\PreloadingSourceRepository;
use yii\base\Action;
use yii\web\Request;
use yii\web\User;

class Index extends Action
{
    public function run(
        Resolver $abacResolver,
        PreloadingSourceRepository $preloadingSourceRepository,
        Request $request,
        User $user
    ) {
        $this->controller->layout = Controller::LAYOUT_ADMIN_TABS;
        $projectSearch = new ProjectSearch();

        $preloadingSourceRepository->preloadSource($abacResolver->fromSubject($user->identity));

        $projectProvider = $projectSearch->search($request->queryParams, $user);
        return $this->controller->render('index', [
            'projectSearch' => $projectSearch,
            'projectProvider' => $projectProvider
        ]);
    }
}
