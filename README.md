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
[`InvoicePost`](/src/InvoicePost.php) represents a purchased item.

```php
use byrokrat\billing\InvoicePost;

// 1 unit of a 100 EUR item with 25% VAT
$item = new InvoicePost(
    'Item description',
    new Amount('1'),
    new Amount('100'),
    new Amount('.25')
);
```

The simplest way to create invoices is by using the [`InvoiceBuilder`](/src/InvoiceBuilder.php).

```php
use byrokrat\billing\InvoiceBuilder;

$invoice = (new InvoiceBuilder)
    ->setSerial('1')
    ->setSeller(new LegalPerson('Company X', ...))
    ->setBuyer(new LegalPerson('Mrs Y', ...))
    ->setMessage('Pay in time or else!')
    ->generateOcr()
    ->addPost($item)
    ->setCurrency('EUR')
    ->buildInvoice();
```

[`Invoice`](/src/Invoice.php) represents the actual invoice. Se the class
definition for a complete list of access methods.

```php
echo $invoice->getInvoiceTotal();
// prints 125 (100 EUR plus 25% VAT)
```

Credits
-------
Billing is covered under the [WTFPL](http://www.wtfpl.net/)

@author Hannes Forsgård (hannes.forsgard@fripost.org)
