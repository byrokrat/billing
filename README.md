# Billing

[![Packagist Version](https://img.shields.io/packagist/v/byrokrat/billing.svg?style=flat-square)](https://packagist.org/packages/byrokrat/billing)
[![Build Status](https://img.shields.io/travis/byrokrat/billing/master.svg?style=flat-square)](https://travis-ci.org/byrokrat/billing)
[![Quality Score](https://img.shields.io/scrutinizer/g/byrokrat/billing.svg?style=flat-square)](https://scrutinizer-ci.com/g/byrokrat/billing)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/byrokrat/billing.svg?style=flat-square)](https://scrutinizer-ci.com/g/byrokrat/billing/?branch=master)
[![Dependency Status](https://img.shields.io/gemnasium/byrokrat/billing.svg?style=flat-square)](https://gemnasium.com/byrokrat/billing)

Data types for creating and formatting invoices.

Installation
------------
```shell
composer require byrokrat/billing:^2
```

Usage
-----
[`Invoices`][invoice] are created using the [`InvoiceBuilder`][invoicebuilder]:

<!-- @expectOutput 125.00 -->
```php
namespace byrokrat\billing;

use byrokrat\amount\Currency\EUR;

// 1 unit of a 100 EUR item with 25% VAT
$item = new Item('Description', new EUR('100'), 1, .25);

$invoice = (new InvoiceBuilder)
    ->setSerial('1')
    ->setSeller(new Agent('Company X'))
    ->setBuyer(new Agent('Mrs Y'))
    ->generateOcr()
    ->addItem($item)
    ->setAttribute('message', 'custom invoice message')
    ->buildInvoice();

// prints 125 (100 EUR plus 25% VAT)
echo $invoice->getInvoiceTotal();
```

### Implementing your own billables and agents

Billing uses an interface centered design:

* [`Billable`][billable] represents a purchasable item.
* [`AgentInterface`][agentinterface] represents a selling or buying party.

[`Item`](/src/Item.php) and [`Agent`](/src/Agent.php) offers simple implementations
of these interfaces, but you may of course provide your own implementations and
extend the interfaces as needed.

API
---
### [`InvoiceBuilder`][invoicebuilder]

Method signature                                            | description
:---------------------------------------------------------- | :----------------------------------------
setSerial(string $serial): self                             | Set invoice serial number
setSeller([`AgentInterface`][agentinterface] $seller): self | Set seller
setBuyer([`AgentInterface`][agentinterface] $buyer): self   | Set buyer
setOcr(string $ocr): self                                   | Set invoice reference number
generateOcr(): self                                         | Generate invoice reference number from serial number
addItem([`Billable`][billable] $billable): self             | Add billable to invoice
setBillDate([`DateTimeInterface`][datetime] $date): self    | Set date of invoice creation
setExpiresAfter(int $nrOfDays): self                        | Set number of days before invoice expires
setDeduction([`Amount`][amount] $deduction): self           | Set deduction (amount prepaid)
setAttribute(string $key, $value): self                     | Set attribute defined by key
buildInvoice(): [`Invoice`][invoice]                        | Build invoice

### [`Invoice`][invoice]

Method signature                                     | description
:--------------------------------------------------- | :----------------------------------------
getSerial(): string                                  | Get invoice serial number
getSeller(): [`AgentInterface`][agentinterface]      | Get registered seller
getBuyer(): [`AgentInterface`][agentinterface]       | Get registered buyer
getOcr(): string                                     | Get invoice reference number
getItems(): [`ItemBasket`][itembasket]               | Get item basket
getInvoiceTotal(): [`Amount`][amount]                | Get charged amount (VAT included)
getBillDate(): [`DateTimeInterface`][datetime]       | Get date of invoice creation
getExpiresAfter(): int                               | Get number of days before invoice expires
getExpirationDate(): [`DateTimeInterface`][datetime] | Get date when invoice expires
getDeduction(): [`Amount`][amount]                   | Get deducted prepaid amound
getAttribute(string $key, $default = ''): mixed      | Get attribute or default if attribute is not set
getAttributes(): array                               | Get all loaded attributes

### [`ItemBasket`][itembasket]

Method signature                            | description
:------------------------------------------ | :-------------------------------------------------------------------
getIterator(): [`Traversable`][traversable] | Iterate over [`ItemEnvelope`](/src/ItemEnvelope.php) objects
getNrOfItems(): int                         | Get number of items in basket
getNrOfUnits(): int                         | Get number of units in basket (each item may contain multiple units)
getTotalUnitCost(): [`Amount`][amount]      | Get total cost of all items (VAT excluded)
getTotalVatCost(): [`Amount`][amount]       | Get total VAT cost for all items
getTotalCost(): [`Amount`][amount]          | Get total cost of all items (VAT included)
getVatRates(): array                        | Get unit and vat totals for non-zero vat rates

[invoicebuilder]: /src/InvoiceBuilder.php
[invoice]: /src/Invoice.php
[billable]: /src/Billable.php
[agentinterface]: /src/AgentInterface.php
[itembasket]: /src/ItemBasket.php
[amount]: https://github.com/byrokrat/amount
[datetime]: http://php.net/manual/en/class.datetimeinterface.php
[traversable]: http://php.net/manual/en/class.traversable.php
