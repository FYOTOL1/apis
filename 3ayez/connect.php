<?php

$dev = false;

try {
    if ($dev === true) {
        $GLOBALS["db"] = new PDO("mysql:host=localhost;dbname=apis", "root", "");
    } else {
        $GLOBALS["db"] = new PDO("mysql:host=premium-host.dnsco.in;dbname=if0_35242478_app", "if0_35242478", "fyotol2008");
    }
} catch (PDOException $th) {
    echo "Field Connect: " . $th;
}
