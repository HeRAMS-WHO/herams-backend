<?php

declare(strict_types=1);

namespace prime\tests;

use prime\models\ar\Element;
use prime\models\ar\Facility;
use prime\models\ar\Page;
use prime\models\ar\Project;
use prime\models\ar\Survey;
use prime\models\ar\SurveyResponse;
use prime\models\ar\Workspace;
use prime\models\ar\WorkspaceForLimesurvey;
use SamIT\abac\AuthManager;
use yii\db\ActiveRecord;
use yii\web\Request;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class FunctionalTester extends \Codeception\Actor
{
    use _generated\FunctionalTesterActions;

    private Element $element;

    private Facility $facility;

    private Page $page;

    private Project $project;

    private Project $projectForLimesurvey;

    private Survey $adminSurvey;

    private Survey $dataSurvey;

    private Workspace $workspace;

    private WorkspaceForLimesurvey $workspaceForLimesurvey;

    /**
     * Define custom actions here
     */
    public function grabHtmlContentFromEmail(\Swift_Message $email): ?string
    {
        foreach ($email->getChildren() as $child) {
            if ($child->getContentType() === 'text/html') {
                return $child->getBody();
            }
        }

        return null;
    }

    public function grabHtmlContentFromLastSentEmail(): ?string
    {
        $email = $this->grabLastSentEmail();
        if ($email === false) {
            $this->fail('No messages received');
            return null;
        }

        return $this->grabHtmlContentFromEmail($email->getSwiftMessage());
    }

    public function haveAdminSurvey(): Survey
    {
        if (! isset($this->adminSurvey)) {
            $this->adminSurvey = $survey = new Survey();
            $survey->config = [
                'pages' => [
                    [
                        'name' => 'page1',
                        'elements' => [
                            [
                                'type' => 'facilitytype',
                                'name' => 'type',
                                'title' => "Type of the facility",
                                'choices' => [
                                    [
                                        'value' => 'clinic',
                                        'text' => 'Clinic',
                                    ],
                                    [
                                        'value' => 'mobile',
                                        'text' => 'Mobile Cinic',
                                        'tier' => 'secondary',
                                    ],
                                    [
                                        'value' => 'private',
                                        'text' => 'Private Clinic',
                                        'tier' => 'tertiary'
                                    ]

                                ]
                            ],
                        ],
                    ],
                ],
            ];
            $this->save($survey);
        }

        return $this->adminSurvey;
    }

    public function haveDataSurvey(): Survey
    {
        if (! isset($this->dataSurvey)) {
            $this->dataSurvey = $survey = new Survey();
            $survey->config = [
                'pages' => [
                    [
                        'name' => 'page1',
                        'elements' => [
                            [
                                'type' => 'text',
                                'name' => 'question1',
                                'title' => 'title1',
                            ],
                        ],
                    ],
                ],
            ];
            $this->save($survey);
        }

        return $this->dataSurvey;
    }

    public function haveElement(): Element
    {
        if (! isset($this->element)) {
            $this->element = new Element();
            $this->element->page_id = $this->havePage()->id;
            $this->element->sort = 0;
            $this->element->type = Element::TYPE_MAP;
            $this->element->transpose = false;
            $this->element->code = 'test';
            $this->element->width = 1;
            $this->element->height = 1;
            $this->save($this->element);
        }

        return $this->element;
    }

    public function haveFacility(): Facility
    {
        if (! isset($this->facility)) {
            $adminData = [
                'name' => 'Test facility name',
            ];
            $this->facility = new Facility();
            $this->facility->admin_data = $adminData;
            $this->facility->name = 'Test facility name';
            $this->facility->workspace_id = $this->haveWorkspace()->id;
            $this->save($this->facility);

            $surveyResponse = new SurveyResponse();
            $surveyResponse->survey_id = $this->facility->workspace->project->admin_survey_id;
            $surveyResponse->facility_id = $this->facility->id;
            $surveyResponse->data = $adminData;
            $this->save($surveyResponse);
        }

        return $this->facility;
    }

    public function havePage(): Page
    {
        if (! isset($this->page)) {
            $this->page = new Page();
            $this->page->title = 'Test page';
            $this->page->sort = 0;
            $this->page->project_id = $this->haveProjectForLimesurvey()->id;
            $this->save($this->page);
        }

        return $this->page;
    }

    public function haveProject(): Project
    {
        if (! isset($this->project)) {
            $this->project = $project = new Project();
            $project->title = 'Test project';
            $project->admin_survey_id = $this->haveAdminSurvey()->id;
            $project->data_survey_id = $this->haveDataSurvey()->id;
            $this->save($project);
        }

        return $this->project;
    }

    public function haveProjectForLimesurvey(): Project
    {
        if (! isset($this->projectForLimesurvey)) {
            $this->projectForLimesurvey = $project = new Project();
            $project->title = 'Test project';
            $project->base_survey_eid = 12345;
            $this->save($project);
        }

        return $this->projectForLimesurvey;
    }

    public function haveWorkspace(): Workspace
    {
        if (! isset($this->workspace)) {
            $this->workspace = $workspace = new Workspace();
            $workspace->title = 'WS2';
            $workspace->project_id = $this->haveProject()->id;
            $this->save($workspace);
        }

        return $this->workspace;
    }

    public function haveWorkspaceForLimesurvey(): WorkspaceForLimesurvey
    {
        if (! isset($this->workspaceForLimesurvey)) {
            $this->workspaceForLimesurvey = $workspace = new WorkspaceForLimesurvey();
            $workspace->title = 'WS1';
            $workspace->project_id = $this->haveProjectForLimesurvey()->id;
            $workspace->token = 'TestToken1';
            $this->save($workspace);
        }

        return $this->workspaceForLimesurvey;
    }

    public function assertUserCan(object $subject, string $permission): void
    {
        $this->assertTrue(\Yii::$app->user->can($permission, $subject));
    }

    public function assertUserCanNot(object $subject, string $permission): void
    {
        $this->assertFalse(\Yii::$app->user->can($permission, $subject));
    }

    public function grantCurrentUser(object $subject, string $permission): void
    {
        /** @var AuthManager $manager */
        $manager = \Yii::$app->abacManager;
        $manager->grant(\Yii::$app->user->identity, $subject, $permission);
        $this->assertUserCan($subject, $permission);
    }

    public function save(ActiveRecord $activeRecord)
    {
        $this->assertTrue($activeRecord->save(), print_r($activeRecord->errors, true));
    }

    public function sendPostWithCsrf($url, $params)
    {
        $this->createAndSetCsrfCookie('abc');
        $this->haveHttpHeader(Request::CSRF_HEADER, \Yii::$app->security->maskToken('abc'));
        $this->sendPost($url, $params);
    }
}
