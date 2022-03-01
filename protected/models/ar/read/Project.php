<?php

declare(strict_types=1);

namespace prime\models\ar\read;

use prime\behaviors\LocalizableReadBehavior;
use prime\interfaces\project\ProjectForTabMenuInterface;
use prime\models\ActiveRecord;
use prime\traits\ReadOnlyTrait;
use prime\values\ProjectId;

class Project extends \prime\models\ar\Project implements ProjectForTabMenuInterface
{
    use ReadOnlyTrait;

    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'localizable' => [
                'class' => LocalizableReadBehavior::class,
                'locale' => \Yii::$app->language,
                'attributes' => ['title']
            ]
        ]);
    }

    public static function instantiate($row): static
    {
        return forward_static_call(ActiveRecord::instantiate(...), $row);
    }

    public function getId(): ProjectId
    {
        return new ProjectId($this->getAttribute('id'));
    }

    public function getWorkspaceCount(): int
    {
        return $this->getVirtualField('workspaceCount');
    }

    public function getPermissionSourceCount(): int
    {
        return $this->getVirtualField('permissionSourceCount');
    }

    public function getLabel(): string
    {
        return $this->title;
    }
}
