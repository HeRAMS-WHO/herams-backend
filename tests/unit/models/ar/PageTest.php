<?php


namespace prime\tests\unit\models\ar;


use prime\models\ar\Page;
use prime\models\ar\Project;

class PageCest  extends ActiveRecordTest
{
    /**
     * @inheritDoc
     */
    public function validSamples(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function invalidSamples(): array
    {
        return [];
    }


//    public function testImportExport()
//    {
//        $project = new Project();
//        $model = new Page();
//        $data = $model->export();
//
//        $imported = Page::import($project, $data);
//        $this->tester->assertSame($model->attributes, $imported->attributes);
//
//
//    }
}