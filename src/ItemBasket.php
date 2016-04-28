<?php

declare(strict_types=1);

namespace byrokrat\billing;

use byrokrat\amount\Amount;

/**
 * Container for billable items
 */
class ItemBasket implements \IteratorAggregate
{
    /**
     * @var ItemEnvelope[] Contained items
     */
    private $items = [];

    /**
     * Optionally load items at construct
     */
    public function __construct(ItemEnvelope ...$items) {
        foreach ($items as $item) {
            $this->addItem($item);
        }
    }

    /**
     * Add item to basket
     */
    public function addItem(ItemEnvelope $item): self
    {
        $this->items[] = $item;
        return $this;
    }

    /**
     * Get contained items
     *
     * @return ItemEnvelope[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Implements the IteratorAggregate interface
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->getItems() as $item) {
            yield $item;
        }
    }

    /**
     * Get number of items in basket
     */
    public function getNrOfItems(): int
    {
        return count($this->getItems());
    }

    /**
     * Get number of units in basket (each item may contain multiple units)
     */
    public function getNrOfUnits(): int
    {
        return array_reduce(
            $this->getItems(),
            function (int $carry, ItemEnvelope $item) {
                return $carry + $item->getNrOfUnits();
            },
            0
        );
    }

    /**
     * Get total cost of all items (VAT excluded)
     */
    public function getTotalUnitCost(): Amount
    {
        return array_reduce(
            $this->getItems(),
            function (Amount $carry, ItemEnvelope $item) {
                return $carry->add($item->getTotalUnitCost());
            },
            new Amount('0')
        );
    }

    /**
     * Get total VAT cost for all items
     */
    public function getTotalVatCost(): Amount
    {
        return array_reduce(
            $this->getItems(),
            function (Amount $carry, ItemEnvelope $item) {
                return $carry->add($item->getTotalVatCost());
            },
            new Amount('0')
        );
    }

    /**
     * Get total cost of all items (VAT included)
     */
    public function getTotalCost(): Amount
    {
        return $this->getTotalVatCost()->add($this->getTotalUnitCost());
    }

    /**
     * Get charged vat amounts for non-zero vat rates
     *
     * @return Billable[]
     */
    public function getVatRates(): array
    {
        $rates = [];

        foreach ($this as $item) {
            if ($item->getVatRate()->isPositive()) {
                $key = (string)$item->getVatRate();

                if (!array_key_exists($key, $rates)) {
                    $rates[$key] = new SimpleItem('', new Amount('0'), 1, $item->getVatRate());
                }

                $rates[$key] = new SimpleItem(
                    $rates[$key]->getBillingDescription(),
                    $rates[$key]->getCostPerUnit()->add($item->getTotalUnitCost()),
                    $rates[$key]->getNrOfUnits(),
                    $rates[$key]->getVatRate()
                );
            }
        }

        ksort($rates);

        return array_values($rates);
    }
}
