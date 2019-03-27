<?php

namespace prime\models\forms\workspace;

use prime\components\LimesurveyDataProvider;
use prime\models\ar\Workspace;
use SamIT\LimeSurvey\Interfaces\TokenInterface;

class CreateUpdate extends Workspace
{
    public function scenarios()
    {
        $scenarios =  [
            'create' => [
                'title',
                'owner_id',
                'token'
            ],
              'update' => [
                'title',
            ],
        ];
        $scenarios['admin-update'] = array_merge(['owner_id'], $scenarios['update']);
        return $scenarios;
    }

    public static function tableName()
    {
        return Workspace::tableName();
    }

    public function tokenOptions(LimesurveyDataProvider $limesurveyDataProvider = null): array
    {
        $limesurveyDataProvider = $this->getLimesurveyDataProvider();
        $usedTokens = $this->project->getWorkspaces()->select(['token'])->indexBy('token')->column();

        $tokens = $limesurveyDataProvider->getTokens($this->project->base_survey_eid);

        $result = [];
        /** @var TokenInterface $token */
        foreach ($tokens as $token) {
            if (isset($usedTokens[$token->getToken()])) {
                continue;
            }
            if (!empty($token->getToken())) {
                $result[$token->getToken()] = "{$token->getFirstName()} {$token->getLastName()} ({$token->getToken()}) " . implode(
                        ', ',
                        array_filter($token->getCustomAttributes())
                    );
            }
        }

        return $result;

    }


}