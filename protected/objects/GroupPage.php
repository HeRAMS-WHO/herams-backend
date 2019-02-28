<?php


namespace prime\objects;


use prime\interfaces\PageInterface;
use prime\models\ar\Element;
use prime\models\ar\elements\Chart;
use prime\models\ar\elements\Map;
use prime\models\ar\elements\Table;
use SamIT\LimeSurvey\Interfaces\GroupInterface;
use SamIT\LimeSurvey\Interfaces\SurveyInterface;

class GroupPage implements PageInterface
{
    /** @var GroupInterface */
    private $group;
    private $parent;
    public function __construct(
        GroupInterface $group,
        PageInterface $parent
    ) {
        $this->group = $group;
        $this->parent = $parent;
    }

    /**
     * @return PageInterface[]
     */
    public function getChildPages(SurveyInterface $survey): iterable
    {
        return [];
    }

    /**
     * @return Element[]
     */
    public function getChildElements(): iterable
    {

        return [
            new Map([
                'transpose' => false,
                'config' => [
                    'code' => 'HF2'
                ],
            ]),
            new Chart([
                'transpose' => true,
                'config' => [
                    'code' => 'availability',
                    'colors' => ['green', 'orange', 'red'],
                    'map' => [
                        'A1' => 'Fully available',
                        'A2' => 'Partially available',
                        'A3' => 'Not available',
                        'A4' => 'Not normally provided'
                    ]
                ]
            ]),
            new Chart([
                'transpose' => true,
                'config' => [
                    'code' => 'causes',
                    'colors' => [
                        "rgb(255, 99, 132)",
                        "rgb(255, 159, 64)",
                        "rgb(255, 205, 86)",
                        "rgb(75, 192, 192)",
                        "rgb(54, 162, 235)",
                        "rgb(153, 102, 255)",
                        "rgb(201, 203, 207)"
                    ],
                    'map' => [
                        'A1' => 'Lack of health staff',
                        'A2' => 'Lack of training of health staff',
                        'A3' => 'Lack of medical supplies',
                        'A4' => 'Lack of medical equipment',
                        'A5' => 'Lack of finances'
                    ]
                ]
            ]),
            new Table([
                'transpose' => true,
                'config' => [
                    'code' => 'availability',
                    'reasonCode' => 'causes',
                    'groupCode' => 'location',
                    'reasonMap' => [
                        'A1' => 'Lack of health staff',
                        'A2' => 'Lack of training of health staff',
                        'A3' => 'Lack of medical supplies',
                        'A4' => 'Lack of medical equipment',
                        'A5' => 'Lack of finances'
                    ]
                ]
            ])
        ];
    }

    public function getTitle(): string
    {
        return trim(strtr($this->group->getTitle(), [
            'HeRAMS' => ''
        ]));
    }

    public function getId(): int
    {
        return $this->group->getId() + 10000;
    }

    public function getParentId(): ?int
    {
        return $this->parent->getId();
    }


    public function getParentPage(): PageInterface
    {
        return $this->parent;
    }

    public function filterResponses(iterable $responses): iterable
    {
        $codes = [];
        foreach($this->group->getQuestions() as $question) {
            $codes[$question->getTitle()] = true;
        }

        foreach($responses as $response) {
            if (!$response instanceof HeramsSubject || isset($codes[$response->getCode()])) {
                yield $response;
            }
        }
    }
}