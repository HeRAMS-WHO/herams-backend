<?php
namespace prime\tests;

use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\User;
use prime\models\ar\Workspace;
use SamIT\abac\AuthManager;
use SamIT\abac\repositories\PreloadingSourceRepository;
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

    private $workspace;
    private $project;
   /**
    * Define custom actions here
    */
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
