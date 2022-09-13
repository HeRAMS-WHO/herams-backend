<?php

declare(strict_types=1);

namespace prime\queries;

use prime\components\ActiveQuery;
use prime\models\ar\Workspace;
use prime\values\FacilityId;
use prime\values\ProjectId;

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
            'facility_id' => $id
        ]);
    }
}
