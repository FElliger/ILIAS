<?php declare(strict_types=1);

/* Copyright (c) 1998-2020 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 * Class ilTestPassManualScoringOverviewTableGUITest
 * @author Marvin Beym <mbeym@databay.de>
 */
class ilTestPassManualScoringOverviewTableGUITest extends ilTestBaseTestCase
{
    private ilTestPassManualScoringOverviewTableGUI $tableGui;
    private ilObjTestGUI $parentObj_mock;

    protected function setUp() : void
    {
        parent::setUp();

        $lng_mock = $this->createMock(ilLanguage::class);
        $ctrl_mock = $this->createMock(ilCtrl::class);
        $ctrl_mock->expects($this->any())
                  ->method("getFormAction")
                  ->willReturnCallback(function () {
                      return "testFormAction";
                  });

        $this->setGlobalVariable("lng", $lng_mock);
        $this->setGlobalVariable("ilCtrl", $ctrl_mock);
        $this->setGlobalVariable("tpl", $this->createMock(ilGlobalPageTemplate::class));
        $this->setGlobalVariable("ilPluginAdmin", new ilPluginAdmin());
        $this->setGlobalVariable("ilDB", $this->createMock(ilDBInterface::class));

        $this->parentObj_mock = $this->createMock(ilObjTestGUI::class);
        $this->parentObj_mock->object = $this->createMock(ilObjTest::class);
        $this->tableGui = new ilTestPassManualScoringOverviewTableGUI($this->parentObj_mock, "");
    }

    public function test_instantiateObject_shouldReturnInstance() : void
    {
        $this->assertInstanceOf(ilTestPassManualScoringOverviewTableGUI::class, $this->tableGui);
    }
}