<?php

declare(strict_types=1);

namespace herams\common\queries;

use herams\common\models\SurveyResponse;
use herams\common\models\Workspace;
use herams\common\values\FacilityId;
use herams\common\values\ProjectId;

/**
 * @method SurveyResponse|null one()
 */
final class SurveyResponseQuery extends ActiveQuery
{
    public function forProject(ProjectId $id): self
    {
        return $this->andWhere([
            'facility_id' => Workspace::find()->forProject($id)->select('id'),
        ]);
    }

    public function forFacility(FacilityId $id): self
    {
        return $this->andWhere([
            'facility_id' => $id,
        ]);
    }
}
