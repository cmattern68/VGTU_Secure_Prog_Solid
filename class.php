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
        $formattedContent = $formattedContent->parse();
        while (1) {
            foreach ($formattedContent->fileContent["rules"] as $rule) {
                if ($rule["state"] == $state && $rule["read"] == $formattedContent->fileContent["tape"][$formattedContent->fileContent["head-start-position"]]) {
                    $formattedContent->fileContent["tape"][$formattedContent->fileContent["head-start-position"]] = $rule["write"];
                    if ($rule["move"] == "L")
                        --$formattedContent->fileContent["head-start-position"];
                    else if ($rule["move"] == "R")
                        ++$formattedContent->fileContent["head-start-position"];
                    $state = $rule["next-state"];
                    foreach ($formattedContent->fileContent["tape"] as $c) {
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
                return new jsonParser($file);
                break;
            case "txt":
                return new txtParser($file);
                break;
            default:
                return null;
                break;
        }
    }
};
