<?php
declare(strict_types=1);

namespace prime\models\project;

use prime\models\ar\Project;
use prime\objects\LanguageSet;
use prime\values\ProjectId;

/**
 * @codeCoverageIgnore Since all functions are simple getters
 */
class ForBreadcrumb implements \prime\interfaces\project\ForBreadcrumb
{
    private ProjectId $id;
    private LanguageSet $languages;
    private string $title;

    public function __construct(
        Project $model
    ) {
        $this->id = new ProjectId($model->id);
        $this->languages = LanguageSet::from($model->languages);
        $this->title = $model->title;
    }

    public function getId(): ProjectId
    {
        return $this->id;
    }

    public function getLanguages(): LanguageSet
    {
        return $this->languages;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
