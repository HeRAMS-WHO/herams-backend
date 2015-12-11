<?php

use \Step\Acceptance\AdminTester;

class ToolCest
{

    public function _before(AdminTester $I)
    {
        $I->login();
        $I->click('Tools');
    }

    public function _after(AdminTester $I)
    {
    }

    // tests

    public function testCreateTool(AdminTester $I)
    {
        $toolTitle = 'Test tool';
        $I->click('Create');
        $I->fillField('Title', $toolTitle);
        $I->fillField('Acronym', 'T');
        $I->fillField('Description', 'Test description');
        $I->selectOption('Progress report', 'CCPM Percentage');
        $I->attachFile('Image', 'tools/image.jpg');
        $I->selectOption('Intake survey', 'Cluster Coordination Performance Monitoring - Activation Request');
        $I->selectOption('Base data survey', 'Cluster Coordination Performance Monitoring - Partners\' Assessment');
        $I->checkOption('input[type="checkbox"][name="Tool[generators][]"]');
        $I->click('Save', 'button');
        $I->seeInSource('is created.');
        $I->see($toolTitle);
    }
}
