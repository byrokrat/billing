<?php

namespace byrokrat\billing;

class LegalPersonTest extends \PHPUnit_Framework_TestCase
{
    private $person;

    protected function setup()
    {
        $this->person = new LegalPerson('Name', null, null, '1234', true, true);
    }

    public function testGetName()
    {
        $this->assertEquals(
            'Name',
            $this->person->getName()
        );
    }

    public function testGetId()
    {
        $this->assertInstanceOf(
            'byrokrat\id\Id',
            $this->person->getId()
        );
    }

    public function testGetAccount()
    {
        $this->assertInstanceOf(
            'byrokrat\banking\AccountNumber',
            $this->person->getAccount()
        );
    }

    public function testGetCustomerNumber()
    {
        $this->assertEquals(
            '1234',
            $this->person->getCustomerNumber()
        );
    }

    public function testIsOrganization()
    {
        $this->assertTrue($this->person->isOrganization());
    }

    public function testGetVatNr()
    {
        $this->assertEquals(
            'SE01',
            $this->person->getVatNr()
        );
    }
}
