<?php


namespace prime\lists;


use prime\interfaces\FacilityInterface;
use prime\interfaces\FacilityListInterface;
use prime\models\ar\Facility;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;
use yii\data\DataProviderInterface;
use yii\data\Pagination;
use yii\data\Sort;

class SurveyFacilityList implements FacilityListInterface
{
    private $facilities = [];

    /**
     * WorkspaceList constructor.
     * @param array $workspaces
     */
    public function __construct(SurveyInterface $survey)
    {

        foreach($survey->getGroups() as $group) {
            foreach($group->getQuestions() as $question) {
                if (preg_match('/^QHeRAMS\d+$/', $question->getTitle())) {
                    $name = trim($question->getText(), "\n:");
                    $id = $question->getId();
                    $this->facilities[] = new Facility($name, $id);
                }
            }
        }
    }

    public function getLength(): int
    {
        return count($this->facilities);
    }

    public function get(int $i): FacilityInterface
    {
        return $this->facilities[$i];
    }

    public function toArray(): array
    {
        return $this->facilities;
    }
}