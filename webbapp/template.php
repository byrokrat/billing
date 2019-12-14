<!DOCTYPE html>
<html lang="sv">
<head>
<meta charset="utf-8">
<title>Fakturera</title>
<style>
#wrapp {
    width: 600px;
    font: normal 10pt Helvetica, sans-serif;
    margin-bottom:50px;
}
#foot {
    margin:20px;
    text-align:center;
    color:#777;
}
h1 {
    font:bold 34pt Helvetica, sans-serif;
    text-align:center;
}
form div {
    margin: 10px 0 10px 0;
}
fieldset {
    margin:10px 0 10px 0;
    border: solid 1px #777;
}
textarea {
    width:99%;
    height:100px;
    background-color:#eed;
    border-color:#777;
}
input[type="text"] {
    width:99%;
    border:solid 1px #777;
    padding:3px;
    background-color:#eed;
}
.post {
    margin:0;
}
.post input {
    border: solid 1px #777;
    padding:3px;
    background-color:#eed;
}
.post input:nth-child(1) {
    width:250px;
}
.post input:nth-child(2) {
    width:30px;
}
.post input:nth-child(3) {
    width:50px;
}
.post input:nth-child(4) {
    width:20px;
}
.required{color:red;}
</style>
<script type="text/javascript">
function setCustomer(ref, address){
    form = document.forms['invoice'];
    form.elements["custRef"].value = ref;
    form.elements["custAddress"].value = address;
    return false;
}
</script>
</head>

<body>

<div id="wrapp">

<h1>FAKTURERA</h1>

<form id="invoice" action="." method="POST">
    <input type="hidden" name="newInvoice" value="true">
    <input type="submit">
    <input type="reset">

    <div>
        <span class="required">Fakturanr:</span>
        <input type="text" name="invoiceNr" value="">
    </div>

    <div>
        <span class="required">Referensnr:</span>
        <input type="text" name="refNr" value="">
    </div>

    <fieldset>
        <legend class="required">Poster</legend>
        <?php for ($i=0; $i<10; $i++) { ?>
        <div class="post">
            Text: <input type="text" name="post<?php echo $i;?>Txt">
            Antal: <input type="text" name="post<?php echo $i;?>Units" value="1">
            Á pris: <input type="text" name="post<?php echo $i;?>UnitCost">
            Moms: <input type="text" name="post<?php echo $i;?>Vat" value=".25">
        </div>
        <?php } ?>
    </fieldset>

    <fieldset>
        <legend>Köpare</legend>
        <?php foreach ($data['buyers'] as $buyer) {
            echo "<a href=\"javascript:setCustomer('{$buyer['ref']}', '{$buyer['address']}');\">{$buyer['name']}</a> ";
        } ?>
        <div>
            Referens:
            <input type="text" name="custRef">
        </div>
        <div>
            <span class="required">Adress:</span>
            <textarea name="custAddress"></textarea>
        </div>
    </fieldset>

    <div>
        Datum (lämna tomt för dagens datum, annars ÅÅÅÅ-MM-DD):
        <input type="text" name="date">
    </div>

    <div>
        Betalningsvillkor (dagar):
        <input type="text" name="payTerms" value="<?php echo $data['paymentTerm'];?>">
    </div>

    <div>
        Avdrag (om del av fakturan redan betalats, ange summa):
        <input type="text" name="deduction">
    </div>

    <div>
        Meddelande:
        <textarea name="message"><?php echo $data['message'];?></textarea>
    </div>

    <fieldset>
        <legend class="required">Säljare</legend>
        <div>
            Namn:
            <input type="text" name="selName" value="<?php echo $data['seller']['name'];?>">
        </div>
        <div>
            Adress:
            <textarea name="selAddress"><?php echo $data['seller']['address'];?></textarea>
        </div>
        <div>
            Telefon:
            <input type="text" name="selPhone" value="<?php echo $data['seller']['phone'];?>">
        </div>
        <div>
            E-mail:
            <input type="text" name="selMail" value="<?php echo $data['seller']['mail'];?>">
        </div>
        <div>
            Orgnisationsnr:
            <input type="text" name="selOrgNr" value="<?php echo $data['seller']['id'];?>">
        </div>
        <div>
            Konto:
            <input type="text" name="selAccount" value="<?php echo $data['seller']['account'];?>">
        </div>
        <div>
            Har F-skattsedel:
            <input type="checkbox" name="selFtax" <?php if ($data['seller']['tax']) echo 'checked';?>>
        </div>
    </fieldset>

    <input type="submit"/>
    <input type="reset"/>
</form>

</div>
</body>
</html>
