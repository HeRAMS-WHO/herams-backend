<?php

namespace prime\tests\functional\controllers\user;

use Carbon\Carbon;
use prime\models\ar\Permission;
use prime\models\ar\Project;
use prime\tests\FunctionalTester;
use SamIT\abac\interfaces\Resolver;
use SamIT\Yii2\UrlSigner\UrlSigner;

class AcceptInvitationCest
{
    private function getSignedUrl(
        string $email,
        Project $project,
        string $permission = Permission::PERMISSION_READ,
    ) {
        /** @var UrlSigner $urlSigner */
        $urlSigner = \Yii::$app->urlSigner;
        $resolver = \Yii::createObject(Resolver::class);
        $subject = $resolver->fromSubject($project);

        $url = [
            '/user/accept-invitation',
            'email' => $email,
            'subject' => $subject->getAuthName(),
            'subjectId' => $subject->getId(),
            'permissions' => $permission,
        ];
        $urlSigner->signParams($url, false, Carbon::now()->addDays(7));
        return $url;
    }

    public function testInvitationLinkChangedEmail(FunctionalTester $I)
    {
        $project = $I->haveProject();
        $email = 'email@test.com';
        $url = $this->getSignedUrl($email, $project);

        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);

        $I->fillField('Email', 'changed' . $email);
        $I->click('Create account');
        $I->seeResponseCodeIs(200);

        $I->seeEmailIsSent();
    }

    public function testInvitationLinkSameEmail(FunctionalTester $I)
    {
        $project = $I->haveProject();
        $email = 'email@test.com';
        $url = $this->getSignedUrl($email, $project);

        $I->amOnPage($url);
        $I->seeResponseCodeIs(200);

        $I->stopFollowingRedirects();
        $I->click('Create account');
        $I->canSeeResponseCodeIsRedirection();
        $I->dontSeeEmailIsSent();
    }
}
