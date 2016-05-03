<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;

class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    protected function getBillableMock($desc = '', Amount $cost = null, $units = 1, float $vat = .25)
    {
        $billable = $this->prophesize(Billable::CLASS);
        $billable->getBillingDescription()->willReturn($desc);
        $billable->getCostPerUnit()->willReturn($cost ?: new Amount('0'));
        $billable->getNrOfUnits()->willReturn($units);
        $billable->getVatRate()->willReturn($vat);

        return $billable->reveal();
    }
}
