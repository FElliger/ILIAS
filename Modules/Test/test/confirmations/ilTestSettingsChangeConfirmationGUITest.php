<?php declare(strict_types=1);

/* Copyright (c) 1998-2020 ILIAS open source, Extended GPL, see docs/LICENSE */

use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class ilTestSettingsChangeConfirmationGUITest
 * @author Marvin Beym <mbeym@databay.de>
 */
class ilTestSettingsChangeConfirmationGUITest extends ilTestBaseTestCase
{
    private ilTestSettingsChangeConfirmationGUI $testSettingsChangeConfirmationGUI;
    /**
     * @var ilObjTest|MockObject
     */
    private $testObj_mock;
    /**
     * @var ilLanguage|MockObject
     */
    private $lng_mock;

    protected function setUp() : void
    {
        parent::setUp();
        $this->lng_mock = $this->getMockBuilder(ilLanguage::class)->disableOriginalConstructor()->getMock();
        $this->testObj_mock = $this->getMockBuilder(ilObjTest::class)->disableOriginalConstructor()->getMock();

        $this->setGlobalVariable('lng', $this->lng_mock);

        $this->testSettingsChangeConfirmationGUI = new ilTestSettingsChangeConfirmationGUI(
            $this->lng_mock,
            $this->testObj_mock
        );
    }

    public function testSetAndGetOldQuestionSetType() : void
    {
        $expect = "testType";

        $this->testSettingsChangeConfirmationGUI->setOldQuestionSetType($expect);

        $this->assertEquals($expect, $this->testSettingsChangeConfirmationGUI->getOldQuestionSetType());
    }

    public function testSetAndGetNewQuestionSetType() : void
    {
        $expect = "testType";

        $this->testSettingsChangeConfirmationGUI->setNewQuestionSetType($expect);

        $this->assertEquals($expect, $this->testSettingsChangeConfirmationGUI->getNewQuestionSetType());
    }

    public function testSetAndIsQuestionLossInfoEnabled() : void
    {
        $expect = true;

        $this->testSettingsChangeConfirmationGUI->setQuestionLossInfoEnabled($expect);

        $this->assertEquals($expect, $this->testSettingsChangeConfirmationGUI->isQuestionLossInfoEnabled());
    }
}