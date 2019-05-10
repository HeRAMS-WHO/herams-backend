<?php


namespace prime\tests\unit\models\ar;


use prime\models\ar\Page;
use prime\tests\FunctionalTester;

class PageCest
{


    public function testCanBeDeleted(FunctionalTester $I)
    {
        $page = new Page();
        $I->assertTrue($page->canBeDeleted());
    }
}