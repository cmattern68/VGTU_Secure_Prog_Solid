<?php

include("class.php");

main($argv);

function main($av) {
    try {
        $TuringMachine = new TuringMachine($av);
    } catch (Exception $e) {
        echo $e;
    }
}