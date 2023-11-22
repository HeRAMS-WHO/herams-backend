<?php
declare(strict_types=1);

namespace prime\models\search;

use prime\models\ar\Favorite;
use prime\models\ar\Project;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\db\conditions\InCondition;
use yii\db\Expression;
use yii\validators\BooleanValidator;
use yii\validators\NumberValidator;
use yii\validators\SafeValidator;
use yii\validators\StringValidator;

class Workspace extends Model
{
    public $id;
    public $created;
    public $title;
    private Project $project;
    private \prime\models\ar\User $user;
    public $favorite;

    public function __construct(
        Project $project,
        \prime\models\ar\User $user,
        array $config = []
    ) {
        parent::__construct($config);
        $this->project = $project;
        $this->user = $user;
    }

    public function rules()
    {
        return [
            [['created'], SafeValidator::class],
            [['title'], StringValidator::class],
            [['id'], NumberValidator::class],
            [['favorite'], BooleanValidator::class]
        ];
    }

    public function search($params)
    {
        $subqueryResponse = (new \yii\db\Query())
            ->select([
                'workspace_id',
                'latestUpdate' => 'MAX(date)',
                'facilityCount' => 'COUNT(DISTINCT hf_id)',
                'responseCount' => 'COUNT(*)'
            ])
            ->from(\prime\models\ar\Response::tableName())
            ->groupBy('workspace_id');

        $subqueryContributorCount = (new \yii\db\Query())
            ->select(['target_id', 'contributorCount' => 'COUNT(DISTINCT source_id)'])
            ->from(\prime\models\ar\Permission::tableName())
            ->where([
                'target' => \prime\models\ar\Workspace::class,
                'source' => \prime\models\ar\User::class,
            ])
            ->groupBy('target_id');

        $subqueryFavorite = (new \yii\db\Query())
            ->select('target_id')
            ->from(\prime\models\ar\Favorite::tableName())
            ->where([
                'target_class' => \prime\models\ar\Workspace::class,
                'user_id' => $this->user->id
            ]);

        $query = \prime\models\ar\Workspace::find()
            ->leftJoin(['r' => $subqueryResponse], 'r.workspace_id = prime2_workspace.id')
            ->leftJoin(['p' => $subqueryContributorCount], 'p.target_id = prime2_workspace.id')
            ->select(['prime2_workspace.*', 'r.latestUpdate', 'r.facilityCount', 'r.responseCount', 'p.contributorCount'])
            ->andFilterWhere(['tool_id' => $this->project->id]);

        $query->orderBy([
            new Expression("CASE WHEN prime2_workspace.id NOT IN ({$subqueryFavorite->createCommand()->rawSql}) THEN 1 ELSE 0 END DESC"),
            'r.latestUpdate' => SORT_DESC,
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'id' => 'workspace-data-provider',
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        // We are forced to do it this way because Yii doesn't properly bind query params from the order by clause.
        $favorites = Favorite::find()->workspaces()->user($this->user)->select('target_id')->createCommand()->rawSql;

        $sort = new Sort([
            'attributes' => [
                'id',
                'title',
                'created',
                'permissionCount',
                'facilityCount',
                'responseCount',
                'contributorCount',
                'latestUpdate' => [
                    'asc' => [
                        new Expression('[[latestUpdate]] IS NOT NULL ASC'),
                        'latestUpdate' => SORT_ASC
                    ],
                    'desc' => [
                        'latestUpdate' => SORT_DESC
                    ],
                    'default' => SORT_DESC,
                ],
                'favorite' => [
                    'asc' => new Expression("[[id]] IN ($favorites)"),
                    'desc' => new Expression("[[id]] NOT IN ($favorites)"),
                    'default' => SORT_DESC,
                ]
            ],
            'defaultOrder' => [
                'favorite' => SORT_DESC,
                'latestUpdate' => SORT_DESC
            ]
        ]);

        $dataProvider->setSort($sort);
        if (!$this->load($params) || !$this->validate()) {
            return $dataProvider;
        }

        if (isset($this->created)) {
            $interval = explode(' - ', $this->created);
            if (count($interval) == 2) {
                $query->andFilterWhere([
                    'and',
                    ['>=', 'created', $interval[0]],
                    ['<=', 'created', $interval[1] . ' 23:59:59']
                ]);
            }
        }

        if ($this->favorite !== "") {
            $condition = ['id' => $this->user->getFavorites()->workspaces()->select('target_id')];
            if ($this->favorite) {
                $query->andWhere($condition);
            } else {
                $query->andWhere(['not', $condition]);
            }
        }
        $query->andFilterWhere(['like', 'title', trim($this->title)]);
        $query->andFilterWhere(['id' => $this->id]);
        return $dataProvider;
    }
}
