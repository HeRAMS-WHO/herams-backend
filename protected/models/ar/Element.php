<?php


namespace prime\models\ar;


use prime\models\ActiveRecord;
use prime\models\ar\elements\Chart;
use prime\models\ar\elements\Map;
use prime\models\ar\elements\Table;
use SamIT\LimeSurvey\Interfaces\QuestionInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\base\NotSupportedException;
use yii\base\Widget;

class Element extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%element}}';
    }

    public static function instantiate($row)
    {
        if (!isset($row['type'])) {
            throw new \InvalidArgumentException('Type must be set');
        }

        switch ($row['type']) {
            case 'map': return new Map();
            case 'chart': return new Chart();
            case 'table': return new Table();
            default:
                throw new \InvalidArgumentException('Unknown type: ' . $row['type']);
        }
    }

    public function getWidget(SurveyInterface $survey, array $data): Widget
    {
        throw new NotSupportedException('This must be implemented in a subclass');
    }

    protected function findQuestionByCode(SurveyInterface $survey, string $text): ?QuestionInterface
    {
        foreach($survey->getGroups() as $group) {
            foreach($group->getQuestions() as $question) {
                if ($question->getTitle() === $text) {
                    return $question;
                }

            }
        }
    }
}