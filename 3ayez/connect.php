<?php

$dev = false;

try {
    if ($dev === true) {
        $GLOBALS["db"] = new PDO("mysql:host=localhost;dbname=apis", "root", "");
    } else {
        $GLOBALS["db"] = new PDO("mysql:host=premium-host.dnsco.in;dbname=if0_35242478_app", "doplario_ahmed", "fyotol_2008");
    }
} catch (PDOException $th) {
    echo "Field Connect: " . $th;
}
