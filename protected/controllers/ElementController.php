<?php

declare(strict_types=1);

namespace prime\controllers;

use herams\common\domain\element\Element;
use herams\common\domain\page\PageRepository;
use herams\common\domain\project\ProjectRepository;
use herams\common\values\ElementId;
use herams\common\values\PageId;
use prime\actions\DeleteAction;
use prime\components\Controller;
use prime\controllers\element\Create;
use prime\controllers\element\CreateForSurveyJs;
use prime\controllers\element\Preview;
use prime\controllers\element\PreviewForSurveyJs;
use prime\controllers\element\Update;
use prime\objects\Breadcrumb;
use prime\repositories\ElementRepository;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class ElementController extends Controller
{
    public $layout = Controller::LAYOUT_ADMIN_TABS;

    public function __construct(
        $id,
        $module,
        private ElementRepository $elementRepository,
        private PageRepository $pageRepository,
        private ProjectRepository $projectRepository,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actions(): array
    {
        return [
            'update' => Update::class,
            'create-for-survey-js' => CreateForSurveyJs::class,
            'preview-for-survey-js' => PreviewForSurveyJs::class,
            'delete' => [
                'class' => DeleteAction::class,
                'query' => Element::find(),
                'redirect' => function (Element $element) {
                    return [
                        'page/update',
                        'id' => $element->page_id,
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
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['delete'],
                    ],
                ],
                'access' => [
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    public function render($view, $params = [])
    {
        $breadcrumbCollection = $this->view->getBreadcrumbCollection()
            ->add(new Breadcrumb(\Yii::t('app', 'Projects'), Url::to('/project/index')));

        if (in_array($this->action->id, ['create']) && $pageId = (int) $this->request->getQueryParam('page_id')) {
            $page = $this->pageRepository->retrieveForBreadcrumb(new PageId((int) $pageId));
            $project = $this->projectRepository->retrieveForBreadcrumb($page->getProjectId());
            $breadcrumbCollection
                ->add($project)
                ->add($page);
        } elseif ($id = $this->request->getQueryParam('id')) {
            $model = $this->elementRepository->retrieveForBreadcrumb(new ElementId((int) $id));
            $page = $this->pageRepository->retrieveForBreadcrumb($model->getPageId());
            $project = $this->projectRepository->retrieveForBreadcrumb($page->getProjectId());
            $breadcrumbCollection
                ->add($project)
                ->add($page);
            ;
        }

        return parent::render($view, $params);
    }
}
