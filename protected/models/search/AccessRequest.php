<?php

declare(strict_types=1);

namespace prime\models\search;

use herams\common\domain\favorite\Favorite;
use herams\common\domain\user\User;
use herams\common\models\Project;
use herams\common\values\UserId;
use prime\components\FilteredActiveDataProvider;
use prime\queries\AccessRequestQuery;
use yii\base\Model;
use yii\data\DataProviderInterface;
use yii\validators\BooleanValidator;
use yii\validators\NumberValidator;

class AccessRequest extends Model
{
    public ?int $projectId = null;

    public ?int $workspaceId = null;

    public $favorite = null;

    public function __construct(
        private AccessRequestQuery $query,
        private User $user,
        private ?\Closure $filter,
        array $config = []
    ) {
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['projectId', 'workspaceId'], NumberValidator::class],
            [['favorite'], BooleanValidator::class],
        ];
    }

    public function search(array $params): DataProviderInterface
    {
        $query = clone $this->query;
        $dataProvider = \Yii::createObject(FilteredActiveDataProvider::class, [[
            'query' => $query,
            'id' => 'access-request-provider',
            'filter' => $this->filter,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]]);

        if (! $this->load($params) || ! $this->validate()) {
            return $dataProvider;
        }

        if ($this->projectId) {
            $query->andWhere([
                'target_id' => $this->projectId,
                'target_class' => Project::class,
            ]);
        }
        if ($this->workspaceId) {
            $query->andWhere([
                'target_id' => $this->workspaceId,
                'target_class' => \herams\common\models\Workspace::class,
            ]);
        }
        if (! is_null($this->favorite)) {
            $baseFavoriteCondition = [
                [
                    'and',
                    [
                        'target_class' => Project::class,
                    ],
                    [
                        'target_id' => Favorite::find()->projects()->user(UserId::fromUser($this->user))->select('target_id'),

                    ],
                ],
                [
                    'and',
                    [
                        'target_class' => \herams\common\models\Workspace::class,
                    ],
                    [
                        'target_id' => Favorite::find()->workspaces()->user(UserId::fromUser($this->user))->select('target_id'),

                    ],
                ],
            ];

            if ($this->favorite) {
                $favoriteCondition = array_merge(
                    ['or'],
                    $baseFavoriteCondition
                );
            } else {
                $favoriteCondition = ['not', array_merge(
                    ['and'],
                    $baseFavoriteCondition
                )];
            }

            $query->andWhere($favoriteCondition);
        }

        return $dataProvider;
    }
}
