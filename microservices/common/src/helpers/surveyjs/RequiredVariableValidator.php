<?php

declare(strict_types=1);

namespace herams\common\helpers\surveyjs;

use yii\validators\Validator;

/**
 * A validator that validates survey structure and checks if one or more variables exist.
 */
class RequiredVariableValidator extends Validator
{
    /**
     * @var list<string>
     */
    public array $requiredVariables = [];

    public SurveyParser $surveyParser;

    protected function validateValue($value): array|null
    {
        if (! is_array($value)) {
            return ['Value must be an array'];
        }

        try {
            $variableSet = $this->surveyParser->parseSurveyStructure($value);
        } catch (\Throwable $t) {
            return [$t->getMessage()];
        }
        $missing = [];
        foreach ($this->requiredVariables as $requiredVariable) {
            try {
                $variableSet->getVariable($requiredVariable);
            } catch (\InvalidArgumentException) {
                $missing[] = $requiredVariable;
            }
        }
        if ($missing !== []) {
            return [
                "The following required variables are missing: {variables}", [
                    'variables' => implode(', ', $missing),
                ]];
        }

        return null;
    }
}
