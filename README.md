# ledgr/billing

Invoice and support classes.

**License**: [GPL](/LICENSE)


## Installation using [composer](http://getcomposer.org/)

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


Running unit tests
------------------
From project root simply type

    > phpunit
