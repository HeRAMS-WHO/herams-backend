<?php

declare(strict_types=1);

namespace prime\controllers\facility;

use prime\actions\FrontendAction;
use prime\components\Controller;
use prime\objects\Breadcrumb;
use prime\objects\BreadcrumbCollection;
use prime\objects\enums\ProjectType;
use prime\repositories\FacilityRepository;
use prime\repositories\SurveyResponseRepository;
use prime\values\FacilityId;
use yii\web\ForbiddenHttpException;

class AdminResponses extends FrontendAction
{
    public function __construct(
        string $id,
        Controller $controller,
        private FacilityRepository $facilityRepository,
    ) {
        parent::__construct($id, $controller, []);
    }

    public function run(
        FacilityRepository $facilityRepository,
        SurveyResponseRepository $surveyResponseRepository,
        string $id
    ) {
        $facilityId = new FacilityId($id);
        $facility = $facilityRepository->retrieveForTabMenu($facilityId);

        $this->getBreadcrumbCollection()->add($facilityRepository->retrieveForBreadcrumb($facilityId));

        if ($facilityRepository->isOfProjectType($facilityId, ProjectType::limesurvey())) {
            throw new ForbiddenHttpException('Limesurvey projects do not have admin responses.');
        } else {
            $dataProvider = $surveyResponseRepository->searchAdminInFacility($facilityId);
        }

        return $this->render(
            'admin-responses',
            [
                'responseProvider' => $dataProvider,
                'facility' => $facility,
            ]
        );
    }

    protected function configureBreadcrumbs(BreadcrumbCollection $breadcrumbCollection): void
    {

        $breadcrumbCollection->add(new Breadcrumb(\Yii::t('app', 'Administrative responses')));
    }
}
