<?php

abstract class aParser {
    public $file;
    public $fileContent = array();

    public function __construct($file) {
        $this->file = $file;
    }

    abstract public function parse();
}

class txtParser extends aParser {
    public function parse() {
        if (!file_exists($this->file)) {
            throw new Exception('File dosent exist.');
        }
        $formattedContent = array();
        $handle = fopen($this->file, "r");
        for ($i = 1; ($line = fgets($handle)) !== false; ++$i) {
            if ($i == 1) {
                $formattedContent["head-start-position"] = trim($line);
            } else if ($i == 2) {
                $formattedContent["tape"] = str_split(trim($line));
            } else {
                $exp = explode(" ", $line);
                $formattedContent["rules"][] = array(
                    "state" => trim($exp[0]),
                    "read" => trim($exp[1]),
                    "write" => trim($exp[2]),
                    "move" => trim($exp[3]),
                    "next-state" => trim($exp[4])
                );
            }
        }
        fclose($handle);
        $this->fileContent = $formattedContent;
        return $this;
    }
}

class jsonParser extends aParser {
    public function parse() {
        if (!file_exists($this->file)) {
            throw new Exception('File dosent exist.');
        }
        $formattedContent = json_decode(file_get_contents($this->file), true);
        $formattedContent["tape"] = str_split($formattedContent["tape"]);
        $this->fileContent = $formattedContent;
        return $this;
    }
}