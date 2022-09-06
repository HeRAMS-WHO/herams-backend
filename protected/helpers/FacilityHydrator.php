<?php

declare(strict_types=1);

namespace prime\helpers;

use Collecthor\SurveyjsParser\VariableSet;
use prime\helpers\surveyjs\LocalizableTextVariable;
use prime\models\ar\Facility;
use prime\modules\Api\models\NewFacility;
use prime\objects\Locale;

class FacilityHydrator
{
    public function hydrateFromAdminSurvey(
        Facility $facility,
        NewFacility $newFacility,
        VariableSet $variableSet
    ): void {
        $record = $newFacility->data;
        $facility->admin_data = $record->allData();

        $name = $variableSet->getVariable('name');

        $i18n = [];
        if ($name instanceof LocalizableTextVariable) {
            $facility->name = $name->getDisplayValue($record)->getRawValue();

            $i18n['name'] = $name->getValue($record)->getRawValue();
            unset($i18n['name'][Locale::default()->locale]);
        }

        $alternativeName = $variableSet->getVariable('alternative_name');
        if ($alternativeName instanceof LocalizableTextVariable) {
            $facility->alternative_name = $alternativeName->getDisplayValue($record)->getRawValue();

            $i18n['alternative_name'] = $alternativeName->getValue($record)->getRawValue();
            unset($i18n['alternative_name'][Locale::default()->locale]);
        }

        $facility->i18n = $i18n;
    }
}
