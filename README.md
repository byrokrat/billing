# ledgr/billing [![Latest Stable Version](https://poser.pugx.org/ledgr/billing/v/stable.png)](https://packagist.org/packages/ledgr/billing) [![Build Status](https://travis-ci.org/ledgr/billing.png?branch=master)](https://travis-ci.org/ledgr/billing) [![Code Coverage](https://scrutinizer-ci.com/g/ledgr/billing/badges/coverage.png?s=0a13eb6f754b0e90a8ffa9e633e768ccf006ece8)](https://scrutinizer-ci.com/g/ledgr/billing/) [![Dependency Status](https://gemnasium.com/ledgr/billing.png)](https://gemnasium.com/ledgr/billing)


Data types for creating invoices.


Installation using [composer](http://getcomposer.org/)
------------------------------------------------------
Simply add `ledgr/billing` to your list of required libraries.


Usage
-----
`InvoicePost` represents a purchased item.

    // 1 unit of a 100 EUR item with 25% VAT
    $item = new InvoicePost(
        'Item description',
        new Amount('1'),
        new Amount('100'),
        new Amount('.25')
    );

The simplest way to create invoices is by using the `InvoiceBuilder`.

    $builder = new InvoiceBuilder();

    $invoice = $builder->reset()
        ->setSerial('1')
        ->generateOCR()
        ->setSeller(new LegalPerson('Company X', ...))
        ->setBuyer(new LegalPerson('Mrs Y', ...))
        ->setMessage('Pay in time or else!')
        ->setCurrency('EUR')
        ->addPost($item)
        ->getInvoice();

`Invoice` represents the actual invoice. Se the class definition for a complete
list of access methods.

    echo $invoice->getInvoiceTotal();
    // prints 125 (100 EUR plus 25% VAT)


Run tests  using [phpunit](http://phpunit.de/)
----------------------------------------------
To run the tests you must first install dependencies using composer.

    $ curl -sS https://getcomposer.org/installer | php
    $ php composer.phar install
    $ phpunit
