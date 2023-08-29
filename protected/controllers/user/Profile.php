<?php

declare(strict_types=1);

namespace prime\controllers\user;

use herams\common\domain\user\User;
use herams\common\helpers\ConfigurationProvider;
use prime\components\NotificationService;
use yii\base\Action;
use yii\web\Request;
use yii\web\Response;
use yii\web\User as UserComponent;

class Profile extends Action
{
    public function run(
        Request $request,
        UserComponent $user,
        NotificationService $notificationService
    ) {
        /** @var User $model */
        $model = $user->identity;

        if ($model->load($request->getBodyParams()) && $model->save()) {
            $notificationService->success(\Yii::t('app', 'User updated'));

            $translations = \Yii::$app->translator->getTranslations('app', $model->language);
            $configurationProvider = \Yii::$container->get(ConfigurationProvider::class);
            $localizedLanguages = $configurationProvider->getLocalizedLanguageNames($model->language);
            \Yii::$app->response->format = Response::FORMAT_JSON; // Set response format to JSON for this case
            return [
                'status' => 'success',
                'translations' => $translations,
                'languages' => $localizedLanguages,
                'message' => \Yii::t('app', 'User updated')
            ];
        }

        return $this->controller->render('react-profile', [
            'model' => $model,
        ]);
    }
}
