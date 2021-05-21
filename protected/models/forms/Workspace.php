<?php
declare(strict_types=1);

namespace prime\models\forms;


use prime\components\LimesurveyDataProvider;
use prime\models\ar\Project;
use prime\models\ar\Workspace as WorkspaceModel;
use prime\values\Point;
use prime\values\ProjectId;
use prime\values\WorkspaceId;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SamIT\LimeSurvey\Interfaces\TokenInterface;
use yii\base\Model;
use yii\db\Query;
use yii\validators\DefaultValueValidator;
use yii\validators\ExistValidator;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;

class Workspace extends Model
{
    public null|string $title = null;

    public null|string $token = null;

    public function __construct(public ProjectId $projectId)
    {
        parent::__construct();
    }

    public function rules(): array
    {
        return [
            [['title'], RequiredValidator::class],
            [['title'], StringValidator::class, 'min' => 1],
            [['!projectId'], ExistValidator::class, 'targetClass' => Project::class, 'targetAttribute' => 'id'],
            [['token'], UniqueValidator::class, 'targetClass' => WorkspaceModel::class, 'filter' => function (Query $query) {
                $query->andWhere(['tool_id' => $this->projectId]);
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
         */
        $limesurveyDataProvider = $this->getLimesurveyDataProvider();
        $usedTokens = WorkspaceModel::find()->andWhere(['tool_id' => $this->projectId])->select(['token'])->indexBy('token')->column();

        $tokens = $limesurveyDataProvider->getTokens((int) Project::find()->andWhere(['id' => $this->projectId])->select('base_survey_eid')->scalar());

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

    private function getLimesurveyDataProvider(): LimesurveyDataProvider
    {
        return \Yii::$app->get('limesurveyDataProvider');
    }

}
