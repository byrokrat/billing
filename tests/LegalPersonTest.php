<?php

namespace ledgr\billing;

use ledgr\id\CorporateId;
use ledgr\banking\Bankgiro;

class LegalPersonTest extends \PHPUnit_Framework_TestCase
{
    private function make()
    {
        return new LegalPerson(
            'Name',
            new CorporateId('777777-7777'),
            new Bankgiro('111-1111'),
            '1234',
            true,
            true
        );
    }

    public function testGetName()
    {
        $this->assertEquals('Name', $this->make()->getName());
    }

    public function testGetId()
    {
        $this->assertEquals(new CorporateId('777777-7777'), $this->make()->getId());
    }

    public function testGetAccount()
    {
        $this->assertEquals(new Bankgiro('111-1111'), $this->make()->getAccount());
    }

    public function testGetCustomerNumber()
    {
        $this->assertEquals('1234', $this->make()->getCustomerNumber());
    }

    public function testIsCorporation()
    {
        $this->assertTrue($this->make()->isCorporation());
    }

    public function testGetVatNr()
    {
        $this->assertEquals('SE777777777701', $this->make()->getVatNr());
    }
}
