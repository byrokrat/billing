Billing
=======

[![Packagist Version](https://img.shields.io/packagist/v/byrokrat/billing.svg?style=flat-square)](https://packagist.org/packages/byrokrat/billing)
[![Build Status](https://img.shields.io/travis/byrokrat/billing/master.svg?style=flat-square)](https://travis-ci.org/byrokrat/billing)
[![Quality Score](https://img.shields.io/scrutinizer/g/byrokrat/billing.svg?style=flat-square)](https://scrutinizer-ci.com/g/byrokrat/billing)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/byrokrat/billing.svg?style=flat-square)](https://scrutinizer-ci.com/g/byrokrat/billing/?branch=master)
[![Dependency Status](https://img.shields.io/gemnasium/byrokrat/billing.svg?style=flat-square)](https://gemnasium.com/byrokrat/billing)

Data types for creating invoices

Installation
------------
```shell
composer require byrokrat/billing
```

Usage
-----
Create items to bill:

```php
use byrokrat\billing\SimpleItem;
use byrokrat\amount\Currency\EUR;

// 1 unit of a 100 EUR item with 25% VAT
$item = new SimpleItem(
    'Item description',
    new EUR('100'),
    1,
    25
);
```

The simplest way to create invoices is by using the [`InvoiceBuilder`](/src/InvoiceBuilder.php):

```php
use byrokrat\billing\InvoiceBuilder;
use byrokrat\billing\StandardActor;

$invoice = (new InvoiceBuilder)
    ->setSerial('1')
    ->setSeller(new StandardActor('Company X'))
    ->setBuyer(new StandardActor('Mrs Y'))
    ->setMessage('Pay in time or else!')
    ->generateOcr()
    ->addItem($item)
    ->buildInvoice();
```

[`Invoice`](/src/Invoice.php) represents the actual invoice:

```php
echo $invoice->getInvoiceTotal();
// prints 125 (100 EUR plus 25% VAT)
```

### Using the billing interfaces

Billing uses an interface centered design:

* [`Billable`](/src/Billable.php) represents a purchased item
* [`Seller`](/src/Seller.php) represents the selling party
* [`Buyer`](/src/Buyer.php) represents the buying party

[`SimpleItem`](/src/SimpleItem.php) and [`StandardActor`](/src/StandardActor.php)
offers simple implementations of these interfaces, but you may of course provide your
own implementations and extend the interfaces as needed.

### The invoice api

[`Invoice`](/src/Invoice.php) defines the following api:

Method signature    | returns                             | description
:------------------ | :---------------------------------- | :------------------------------------------
getSerial()         | string                              | Get invoice serial number
getSeller()         | [`Seller`](/src/Seller.php)         | Get registered seller
getBuyer()          | [`Buyer`](/src/Buyer.php)           | Get registered buyer
getMessage()        | string                              | Get invoice message
getOcr()            | string                              | Get invoice reference number
getItems()          | [`ItemBasket`](/src/ItemBasket.php) | Get item basket
getInvoiceTotal()   | Amount                              | Get charged amount (VAT included)
getBillDate()       | DateTime                            | Get date of invoice creation
getExpiresAfter()   | integer                             | Get number of days before invoice expires
getExpirationDate() | DateTime                            | Get date when invoice expires
getDeduction()      | Amount                              | Get deducted prepaid amound

Credits
-------
Billing is covered under the [WTFPL](http://www.wtfpl.net/)

@author Hannes Forsgård (hannes.forsgard@fripost.org)
