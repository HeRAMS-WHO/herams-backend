<?php
declare(strict_types=1);

namespace prime\controllers\user;

use prime\components\NotificationService;
use prime\models\ar\Favorite;
use prime\models\ar\User;
use prime\models\ar\Workspace;
use prime\models\forms\user\ChangePasswordForm;
use prime\models\forms\user\UpdateEmailForm;
use SamIT\Yii2\UrlSigner\UrlSigner;
use yii\base\Action;
use yii\data\ActiveDataProvider;
use yii\mail\MailerInterface;
use yii\web\Request;

class Favorites extends Action
{
    public function run(
        Request $request,
        \yii\web\User $user,
        NotificationService $notificationService,
        UrlSigner $urlSigner,
        MailerInterface $mailer
    ) {
        $this->controller->layout = 'form';
        /** @var User $model */
        $model = $user->identity;

        $query = Workspace::find()->andWhere(['id' =>
            $model->getFavorites()->filterTargetClass(Workspace::class)->select('target_id')]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        return $this->controller->render('favorites', [
            'dataProvider' => $dataProvider
        ]);
    }
}
