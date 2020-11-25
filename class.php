<?php

include("interface.php");

class AvParser {
    function __construct($av) {
        if (!isset($av[1])) {
            throw new Exception('No argument provied.');
        }
    }

    function getFile($av) {
        return $av[1];
    }
};

class Compute {
    static function run($formattedContent) {
        $state = 0;
        while (1) {
            foreach ($formattedContent["rules"] as $rule) {
                if ($rule["state"] == $state && $rule["read"] == $formattedContent["tape"][$formattedContent["head-start-position"]]) {
                    $formattedContent["tape"][$formattedContent["head-start-position"]] = $rule["write"];
                    if ($rule["move"] == "L")
                        --$formattedContent["head-start-position"];
                    else if ($rule["move"] == "R")
                        ++$formattedContent["head-start-position"];
                    $state = $rule["next-state"];
                    foreach ($formattedContent["tape"] as $c) {
                        echo $c;
                    }
                    echo "\n";
                }
            }
        }
    }
};

class TuringMachine {
    public $file;
    function __construct($av) {
        $avParse = new AvParser($av);
        $this->file = $avParse->getFile($av);
        $ext = explode(".", $this->file);
        $ext = end($ext);
        Compute::run($this->readerByExt($this->file, $ext));
    }

    private function readerByExt($file, $ext) {
        switch ($ext) {
            case "json":
                return jsonParser::parse($file);
                break;
            case "txt":
                return txtParser::parse($file);
                break;
            default:
                return null;
                break;
        }
    }
};
