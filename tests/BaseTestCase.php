<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;

class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    protected function getItemMock($desc = '', $units = 1, Amount $cost = null, Amount $vat = null)
    {
        $item = $this->prophesize(Item::CLASS);
        $item->getDescription()->willReturn($desc);
        $item->getNrOfUnits()->willReturn($units);
        $item->getCostPerUnit()->willReturn($cost ?: new Amount('100'));
        $item->getVatRate()->willReturn($vat ?: new Amount('.25'));

        return $item->reveal();
    }
}
