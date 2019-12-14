<?php

namespace byrokrat\paperinvoice;

use byrokrat\amount\Amount;
use byrokrat\billing\InvoiceBuilder;
use byrokrat\billing\Item;
use byrokrat\billing\Agent;

/** @var Agent The seller object */
$seller = new Agent(filter_input(INPUT_POST, 'selName', FILTER_SANITIZE_STRING));

/** @var Agent The buyer object */
$buyer = new Agent(filter_input(INPUT_POST, 'custRef', FILTER_SANITIZE_STRING));

/** @var InvoiceBuilder Builder that creates byrokrat invoice */
$builder = new InvoiceBuilder;
$builder->setSerial(filter_input(INPUT_POST, 'invoiceNr', FILTER_SANITIZE_STRING))
    ->setOcr(filter_input(INPUT_POST, 'refNr', FILTER_SANITIZE_STRING))
    ->setSeller($seller)
    ->setBuyer($buyer)
    ->setBillDate(new \DateTime(filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING)))
    ->setAttribute('message', filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING))
    ->setAttribute('payment-term', (int)filter_input(INPUT_POST, 'payTerms', FILTER_SANITIZE_STRING))
    ->setAttribute('tax-registered', !!($_POST['selFtax'] == 'on'))
    ->setDeduction(new Amount(filter_input(INPUT_POST, 'deduction', FILTER_SANITIZE_STRING)));

/* Add posts to invoice */
for ($i=0; $i<10; $i++) {
    if ($_POST["post{$i}Txt"] == "") continue;
    $builder->addItem(
        new Item(
            filter_input(INPUT_POST, "post{$i}Txt", FILTER_SANITIZE_STRING),
            new Amount(filter_input(INPUT_POST, "post{$i}Units", FILTER_SANITIZE_STRING)),
            (int)filter_input(INPUT_POST, "post{$i}UnitCost", FILTER_SANITIZE_STRING),
            (float)filter_input(INPUT_POST, "post{$i}Vat", FILTER_SANITIZE_STRING)
        )
    );
}

/** @var PaperInvoice PDF invoice object */
$paper = new PaperInvoice($builder->buildInvoice());

$paper->buyerAddress = filter_input(INPUT_POST, 'custAddress', FILTER_SANITIZE_STRING);
$paper->sellerAddress = filter_input(INPUT_POST, 'selAddress', FILTER_SANITIZE_STRING);
$paper->sellerPhone = filter_input(INPUT_POST, 'selPhone', FILTER_SANITIZE_STRING);
$paper->sellerMail = filter_input(INPUT_POST, 'selMail', FILTER_SANITIZE_STRING);

$bankAccountFactory = new \byrokrat\banking\DelegatingFactory(
    new \byrokrat\banking\AccountFactory,
    new \byrokrat\banking\BankgiroFactory,
    new \byrokrat\banking\PlusgiroFactory
);

$paper->sellerAccount = $bankAccountFactory->createAccount(filter_input(INPUT_POST, 'selAccount', FILTER_SANITIZE_STRING));

$idFactory = new \byrokrat\id\PersonalIdFactory(new \byrokrat\id\OrganizationIdFactory);

$paper->sellerId = $idFactory->createId(filter_input(INPUT_POST, 'selOrgNr', FILTER_SANITIZE_STRING));

/* Render and output pdf */
$pdf = $paper->getPdf();

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="invoice.pdf"');

echo $pdf;
