<?php

declare(strict_types=1);

namespace prime\models\forms\workspace;

use prime\components\LimesurveyDataProvider;
use prime\models\ar\Project;
use prime\models\ar\WorkspaceForLimesurvey;
use prime\values\ProjectId;
use SamIT\LimeSurvey\Interfaces\TokenInterface;
use yii\base\Model;
use yii\db\Query;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;

class CreateForLimesurvey extends Model
{
    public null|string $title = null;
    public null|ProjectId $project_id = null;
    public null|string $token = null;

    public function attributeLabels(): array
    {
        return WorkspaceForLimesurvey::labels();
    }

    private function getLimesurveyDataProvider(): LimesurveyDataProvider
    {
        return \Yii::$app->get('limesurveyDataProvider');
    }

    public function rules(): array
    {
        return [
            [['title'], RequiredValidator::class],
            [['title'], StringValidator::class, 'min' => 1],
            [['token'], UniqueValidator::class, 'targetClass' => WorkspaceForLimesurvey::class, 'filter' => function (Query $query) {
                $query->andWhere(['project_id' => $this->project_id]);
            }],
        ];
    }

    public function tokenOptions(): array
    {
        /**
         * TODO: Model this better
         * - Project dependency
         * - Other workspaces dependency
         *
         * Maybe not needed since we are migrating away from lime survey. Joey 29-09-2021
         */
        $limesurveyDataProvider = $this->getLimesurveyDataProvider();
        $usedTokens = WorkspaceForLimesurvey::find()->andWhere(['project_id' => $this->project_id])->select(['token'])->indexBy('token')->column();

        $tokens = $limesurveyDataProvider->getTokens((int) Project::find()->andWhere(['id' => $this->project_id])->select('base_survey_eid')->scalar());

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
        asort($result);

        return array_merge(['' => \Yii::t('app', 'Create new token')], $result);
    }
}
