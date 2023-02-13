<?php
declare(strict_types=1);

namespace herams\api\models;

use herams\api\components\Link;
use herams\common\models\Project;
use SamIT\Yii2\VirtualFields\VirtualFieldBehavior;
use yii\helpers\Url;
use yii\web\Linkable;

final class ProjectSummary extends Project implements Linkable
{
    public function fields(): array
    {
        $fields = parent::fields();
        $fields['name'] = fn(self $project) => $project->getTitle();
        /** @var VirtualFieldBehavior $virtualFields */
        $virtualFields = $this->getBehavior('virtualFields');
        foreach ($virtualFields->virtualFields as $key => $definition) {
            $fields[$key] = $key;
        }
        foreach (['overrides', 'contributorPermissionCount'] as $hidden) {
            unset($fields[$hidden]);
        }
        return $fields;
    }

    protected static function virtualFields(): array
    {
        return [
            ...parent::virtualFields()
        ];
    }

    final public function getLinks(): array
    {
        $result = [];
        $result[Link::REL_SELF] = Url::to([
            'project/view',
            'id' => $this->id,
        ]);

        $summaryLink = new Link();
        $summaryLink->href = [
            'project/summary',
            'id' => $this->id,
        ];
        $summaryLink->name = 'summary';
        $summaryLink->type = 'application/json';
        $summaryLink->title = \Yii::t('app', 'Project summary');


        $result['summary'] = $summaryLink;

        $workspacesLink = new Link();
        $workspacesLink->href = [
            '/frontend/project/workspaces',
            'id' => $this->id,
        ];
        $workspacesLink->name = 'workspaces';
        $workspacesLink->type = 'text/html';
        $workspacesLink->title = \Yii::t('app', 'Workspaces');


        $result['workspaces'] = $workspacesLink;
        return $result;
    }






}
