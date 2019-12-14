<?php

ini_set("error_reporting", E_ALL & ~E_DEPRECATED);

include "../vendor/autoload.php";

/* Render invoice */
if (isset($_POST['newInvoice'])) {
    include "render.php";
    die();
}

/* Echo invoice creation form (save your personal presets in presets.json) */
$data = (array)json_decode(@file_get_contents("presets.json"), true);

include "template.php";
