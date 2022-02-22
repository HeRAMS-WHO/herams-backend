<?php

namespace prime\models\forms;

use prime\models\ar\Page;
use prime\models\ar\Project;
use prime\validators\JsonValidator;
use yii\base\Model;

class ImportDashboard extends Model
{
    public $pages;

    private $project;
    public function __construct(Project $project)
    {
        parent::__construct();
        $this->project = $project;
    }

    public function rules()
    {
        return [
            [['pages'], JsonValidator::class],
            [['pages'], function () {
                $transaction = Project::getDb()->beginTransaction();
                foreach (json_decode($this->pages, true) as $page) {
                    Page::import($this->project, $page);
                }
                $transaction->rollBack();
            }]
        ];
    }

    public function run()
    {
        $transaction = Project::getDb()->beginTransaction();
        foreach (json_decode($this->pages, true) as $page) {
            Page::import($this->project, $page);
        }
        $transaction->commit();
    }
}
