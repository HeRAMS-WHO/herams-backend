<?php

declare(strict_types=1);

namespace prime\tests\functional\controllers\user;

use Carbon\Carbon;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\models\ar\User;
use prime\tests\FunctionalTester;
use SamIT\abac\interfaces\Resolver;
use SamIT\Yii2\UrlSigner\UrlSigner;

/**
 * @covers \prime\controllers\user\ConfirmInvitation
 */
class ConfirmInvitationCest
{
    private function getSignedUrl(
        string $email,
        Project $project,
        array $permissions = [Permission::PERMISSION_READ],
    ) {
        /** @var UrlSigner $urlSigner */
        $urlSigner = \Yii::$app->urlSigner;
        $resolver = \Yii::createObject(Resolver::class);
        $subject = $resolver->fromSubject($project);

        $url = [
            '/user/confirm-invitation',
            'email' => $email,
            'subject' => $subject->getAuthName(),
            'subjectId' => $subject->getId(),
            'permissions' => implode(',', $permissions),
        ];
        $urlSigner->signParams($url, false, Carbon::tomorrow());
        return $url;
    }

    public function testConfirmationLink(FunctionalTester $I)
    {
        $I->amLoggedInAs(TEST_USER_ID);
        $page = $I->havePage();
        \Yii::$app->user->logout();
        $email = 'email@test.com';
        $url = $this->getSignedUrl($email, $page->project, [Permission::PERMISSION_READ, PERMISSION::PERMISSION_WRITE]);
        $I->dontSeeRecord(User::class, [
            'email' => $email,
        ]);

        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);
        $password = 'Testpassword1';

        $I->fillField('Name', 'Test name');
        $I->fillField('Password', $password);
        $I->fillField('Confirm password', $password);
        $I->click('Create account');
        $I->seeResponseCodeIs(200);

        $user = User::findOne([
            'email' => $email,
        ]);
        $I->assertNotNull($user);

        $I->amLoggedInAs($user->id);
        $I->amOnPage([
            'project/view',
            'id' => $page->project->id,
        ]);
        $I->seeResponseCodeIs(200);

        $I->assertUserCan($page->project, Permission::PERMISSION_WRITE);
    }
}
