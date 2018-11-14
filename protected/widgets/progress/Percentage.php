<?php

namespace prime\widgets\progress;

use prime\models\ar\Project;
use yii\base\Widget;

class Percentage extends Widget
{
    /**
     * @var Project
     */
    public $project;

    public function getPartners()
    {
        /* @todo implement stub */
        return 17;
    }

    public function getPartnersResponding()
    {
        /* @todo implement stub */
        return 10;
    }

    public function getResponseRate()
    {
        return $this->getPartners() > 0 ?
            round(($this->getPartnersResponding() * 100) / $this->getPartners()) :
            0;
    }

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        parent::run();
        return $this->render('percentage', [
            'widget' => $this
        ]);
    }
}