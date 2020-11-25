<?php

interface iParser
{
    static public function parse($file);
}

class txtParser implements iParser {
    static public function parse($file) {
        if (!file_exists($file)) {
            throw new Exception('File dosent exist.');
        }
        $formattedContent = array();
        $handle = fopen($file, "r");
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
        return $formattedContent;
    }
}

class jsonParser implements iParser {
    static public function parse($file) {
        if (!file_exists($file)) {
            throw new Exception('File dosent exist.');
        }
        $formattedContent = json_decode(file_get_contents($file), true);
        $formattedContent["tape"] = str_split($formattedContent["tape"]);
        return $formattedContent;
    }
}