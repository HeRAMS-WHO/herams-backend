<?php

use \Step\Acceptance\Admin;

class ToolCest
{
    // tests

    public function testCreateTool(Admin $I)
    {
        $toolTitle = 'Test tool';
        $I->amOnPage('/tools');
        $I->click('Create');
        $I->fillField('Title', $toolTitle);
        $I->fillField('Acronym', 'T');
        $I->fillHtmlField(['css' => '[name*=description]'], 'Test description');
        $I->selectOption('Project dashboard report', 'CCPM Percentage');
        $I->attachFile('Image', 'tools/image.jpg');
        $I->selectOption('Intake survey', 'Cluster Coordination Performance Monitoring - Activation Request');
        $I->selectOption('Base data survey', 'Cluster Coordination Performance Monitoring - Partners\' Assessment');
        $I->checkOption('input[type="checkbox"][name="Tool[generatorsArray][]"]');
        $I->scrollTo('body', 0, -10000);
        $I->click('Save');

        $I->waitForText($toolTitle);


        $I->waitForText('is created.');

    }
}
