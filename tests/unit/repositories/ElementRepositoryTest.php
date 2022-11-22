<?php

declare(strict_types=1);

namespace prime\tests\unit\repositories;

use Codeception\Test\Unit;
use herams\common\domain\element\Element;
use herams\common\domain\variableSet\HeramsVariableSetRepository;
use herams\common\helpers\ModelHydrator;
use herams\common\values\ElementId;
use herams\common\values\PageId;
use prime\repositories\ElementRepository;

/**
 * @covers \prime\repositories\ElementRepository
 */
class ElementRepositoryTest extends Unit
{
    public function testFindForBreadcrumb(): void
    {
        $element = Element::findOne([
            'id' => 37,
        ]);

        $pageRepository = new ElementRepository(new ModelHydrator(), \Yii::$container->get(HeramsVariableSetRepository::class));
        $breadcrumb = $pageRepository->retrieveForBreadcrumb(new ElementId($element->id));

        $this->assertEquals($element->getTitle(), $breadcrumb->getLabel());
        $this->assertEquals([
            '/element/preview',
            'id' => $element->id,
        ], $breadcrumb->getUrl());
        $this->assertEquals(new PageId($element->page_id), $breadcrumb->getPageId());
    }
}
