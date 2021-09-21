<?php
declare(strict_types=1);

namespace prime\controllers\user;

use prime\repositories\UserRepository;
use yii\base\Action;
use yii\web\Response;
use yii\web\User;

class Select2 extends Action
{
    public function run(
        Response $response,
        User $user,
        UserRepository $userRepository,
        string $q = null,
    ): array {
        $response->format = Response::FORMAT_JSON;

        $result = ['results' => []];

        if (empty($q)) {
            return $result;
        }

        // Could later be extended with pagination
        $users = $userRepository->retrieveForSelect2($q, $user->id, 0, 5);

        foreach ($users as $user) {
            $result['results'][] = ['id' => $user->getUserId()->getValue(), 'text' => $user->getText()];
        }

        return $result;
    }
}
