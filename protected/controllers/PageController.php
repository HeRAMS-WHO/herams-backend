<?php

declare(strict_types=1);

namespace prime\controllers;

use herams\common\domain\page\PageRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\models\Page;
use herams\common\values\PageId;
use herams\common\values\ProjectId;
use prime\actions\DeleteAction;
use prime\components\Controller;
use prime\controllers\page\Create;
use prime\controllers\page\Update;
use prime\objects\Breadcrumb;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class PageController extends Controller
{
    public $layout = Controller::LAYOUT_ADMIN_TABS;

    public function __construct(
        $id,
        $module,
        private PageRepository $pageRepository,
        private ProjectRepository $projectRepository,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actions(): array
    {
        return [
            'update' => Update::class,
            'create' => Create::class,
            'delete' => [
                'class' => DeleteAction::class,
                'query' => Page::find(),
                'redirect' => function (Page $page) {
                    return [
                        'project/pages',
                        'id' => $page->project->id,
                    ];
                },
            ],
        ];
    }

    public function behaviors(): array
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['delete'],
                    ],
                ],
            ]
        );
    }

    public function render($view, $params = [])
    {
        $breadcrumbCollection = $this->view->getBreadcrumbCollection()
            ->add(new Breadcrumb(\Yii::t('app', 'Projects'), Url::to(['/project/index'])));

        if (in_array($this->action->id, ['create']) && $projectId = (int) $this->request->getQueryParam('project_id')) {
            $project = $this->projectRepository->retrieveForBreadcrumb(new ProjectId($projectId));
            $breadcrumbCollection->add($project);
        } elseif ($id = $this->request->getQueryParam('id')) {
            $model = $this->pageRepository->retrieveForBreadcrumb(new PageId((int) $id));
            $project = $this->projectRepository->retrieveForBreadcrumb($model->getProjectId());
            $breadcrumbCollection->add($project);
        }

        return parent::render($view, $params);
    }
}
