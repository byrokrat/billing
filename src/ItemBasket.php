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
     * @var string Classname of currency used in basket
     */
    private $currencyClassname = '';

    /**
     * Optionally load items at construct
     */
    public function __construct(ItemEnvelope ...$items)
    {
        foreach ($items as $envelope) {
            $this->addItem($envelope);
        }
    }

    /**
     * Add item to basket
     *
     * @throws Exception If two items with different currencies are added
     */
    public function addItem(ItemEnvelope $envelope): self
    {
        if (!$this->currencyClassname) {
            $this->currencyClassname = $envelope->getCurrencyClassname();
        }

        if ($envelope->getCurrencyClassname() != $this->getCurrencyClassname()) {
            throw new Exception('Unable to load items with different currencies');
        }

        $this->items[] = $envelope;
        return $this;
    }

    /**
     * Get classname of currency used in basket
     */
    public function getCurrencyClassname(): string
    {
        return $this->currencyClassname;
    }

    /**
     * Create amount object using the current basket currency
     *
     * @throws Exception If no item is loaded and currency is unknown
     */
    public function createCurrencyObject(string $value): Amount
    {
        if (!$currency = $this->getCurrencyClassname()) {
            throw new Exception('Unable to create currency object, currency unknown.');
        }

        return new $currency($value);
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
        foreach ($this->getItems() as $envelope) {
            yield $envelope;
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
            function (int $carry, ItemEnvelope $envelope) {
                return $carry + $envelope->getNrOfUnits();
            },
            0
        );
    }

    /**
     * Get total cost of all items (VAT excluded)
     */
    public function getTotalUnitCost(): Amount
    {
        return $this->reduce('getTotalUnitCost');
    }

    /**
     * Get total VAT cost for all items
     */
    public function getTotalVatCost(): Amount
    {
        return $this->reduce('getTotalVatCost');
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
     * @return Amount[]
     */
    public function getVatRates(): array
    {
        $rates = [];

        foreach ($this as $envelope) {
            if ($envelope->getVatRate() <= 0) {
                continue;
            }

            $key = (string)$envelope->getVatRate();

            if (!isset($rates[$key])) {
                $rates[$key] = [
                    'unit_total' => $this->createCurrencyObject('0'),
                    'vat_total' => $this->createCurrencyObject('0')
                ];
            }

            $rates[$key] = [
                'unit_total' => $rates[$key]['unit_total']->add($envelope->getTotalUnitCost()),
                'vat_total' => $rates[$key]['vat_total']->add($envelope->getTotalVatCost())
            ];
        }

        ksort($rates);
        return $rates;
    }

    /**
     * Reduce loaded items to single amount using envelope method
     */
    private function reduce(string $method): Amount
    {
        return array_reduce(
            $this->getItems(),
            function (Amount $carry, ItemEnvelope $envelope) use ($method) {
                return $carry->add($envelope->$method());
            },
            $this->createCurrencyObject('0')
        );
    }
}
