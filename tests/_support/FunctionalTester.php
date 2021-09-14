<?php
declare(strict_types=1);

namespace prime\tests;

use prime\models\ar\Element;
use prime\models\ar\Page;
use prime\models\ar\Project;
use prime\models\ar\Survey;
use prime\models\ar\Workspace;
use SamIT\abac\AuthManager;
use yii\db\ActiveRecord;

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
    private Page $page;
    private Project $project;
    private Survey $survey;
    private Workspace $workspace;

    /**
    * Define custom actions here
    */
    public function haveElement(): Element
    {
        if (!isset($this->element)) {
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

    public function havePage(): Page
    {
        if (!isset($this->page)) {
            $this->page = new Page();
            $this->page->title = 'Test page';
            $this->page->sort = 0;
            $this->page->project_id = $this->haveProject()->id;
            $this->save($this->page);
        }

        return $this->page;
    }

    public function haveProject(): Project
    {
        if (!isset($this->project)) {
            $this->project = $project = new Project();
            $project->title = 'Test project';
            $project->base_survey_eid = 12345;
            $this->save($project);
        }

        return $this->project;
    }

    public function haveSurvey(): Survey
    {
        if (!isset($this->survey)) {
            $this->survey = $survey = new Survey();
            $survey->config = [
                'pages' => [
                    0 => [
                        'name' => 'page1',
                        'elements' => [
                            0 =>
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

        return $this->survey;
    }

    public function haveWorkspace(): Workspace
    {
        if (!isset($this->workspace)) {
            $this->workspace = $workspace = new Workspace();
            $workspace->title = 'WS1';
            $workspace->tool_id = $this->haveProject()->id;
            $this->save($workspace);
        }

        return $this->workspace;
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
}
