<?php

namespace ThaiSanskrit;

use ThaiSanskrit\Util;
use ThaiSanskrit\ThaiSanscript;

class ThaiSanscriptInformRule {

    public $informmapper;
    public $util;

    public function __construct(?Util $util = NULL) {
        $this->informmapper = new ThaiSanscript(TRUE);
        $this->util = $util ?: new Util(TRUE, $this->informmapper);
    }

    public function convert($txt, $lang = "sans", $destType = "thai") {
        $txt = $this->util->convertRomanChandrabinduToSingle($txt);
        $txt = $this->util->convertNumber($txt);
        $txt = $this->util->convertRomanizeMixConsonant($txt);
        $txt = $this->util->convertRomanizeMixVowel($txt, $lang);
        $txt = $this->util->convertRomanizeSingleConsonant($txt);
        $txt = $this->util->convertRomanizeSingleVowel($txt);
        $txt = $this->convertBindu($txt);
        $txt = $this->util->convertThaiVowelInFist($txt);
        $txt = $this->util->convertThaiVowelPrefix($txt);
        $txt = $this->removeA($txt);
        $txt = $this->swapAnusvaraAndChandrabindu($txt);
        $txt = $this->convertChandrabindu($txt);
        $txt = $this->util->convertThaiAAInFist($txt);

        if ($destType == "lao") {
            $txt = $this->util->convertThaiToLao($txt);
        }

        return $txt;
    }

    public function convertTrackMode($txt, $lang = "sans") {
        $this->printTrackMode($txt, "Start");
        $txt = $this->util->convertRomanChandrabinduToSingle($txt);
        $this->printTrackMode($txt, "Chandrabindu");
        $txt = $this->util->convertNumber($txt);
        $this->printTrackMode($txt, "Number");
        $txt = $this->util->convertRomanizeMixConsonant($txt);
        $this->printTrackMode($txt, "MixConsonant");
        $txt = $this->util->convertRomanizeMixVowel($txt, $lang);
        $this->printTrackMode($txt, "MixVowel");
        $txt = $this->util->convertRomanizeSingleConsonant($txt);
        $this->printTrackMode($txt, "SingleConsonant");
        $txt = $this->util->convertRomanizeSingleVowel($txt);
        $this->printTrackMode($txt, "SingleVowel");
        $txt = $this->convertBindu($txt);
        $this->printTrackMode($txt, "Bindu");
        $txt = $this->util->convertThaiVowelInFist($txt);
        $this->printTrackMode($txt, "ThaiVowelInFist");
        $txt = $this->util->convertThaiVowelPrefix($txt);
        $this->printTrackMode($txt, "ThaiVowelPrefix");
        $txt = $this->removeA($txt);
        $this->printTrackMode($txt, "removeA");
        $txt = $this->swapAnusvaraAndChandrabindu($txt);
        $this->printTrackMode($txt, "swapAnusvara");
        $txt = $this->convertChandrabindu($txt);
        $this->printTrackMode($txt, "Chandrabindu");
        $txt = $this->util->convertThaiAAInFist($txt);
        $this->printTrackMode($txt, "ThaiAAInFist");

        return $txt;
    }

    public function printTrackMode($txt, $type) {
        echo ("[" . $type . "]" . $txt . " -> ");
    }

    public function convertA($txt) {
        return str_replace("a", "A", $txt);
    }

    public function convertBindu($txt) {
        $txt = $txt . " ";
        $charList = $this->util->charList($txt);
        $anusvara = $this->informmapper->anusvara;
        $chandrabindu = $this->informmapper->chandrabindu;

        foreach ($charList as $i => $char) {
            if ($char != " ") {
                $_after1 = $charList[$i + 1];
                $condition = $this->util->isThaiConsonant($char) &&
                        $anusvara != $char &&
                        $chandrabindu != $char &&
                        "ะ" != $char &&
                        "'" != $char &&
                        $_after1 != "a" &&
                        !$this->util->isThaiVowel($_after1);
                if ($condition) {
                    $charList[$i] = $char . "ฺ";
                }
            }
        }
        return $this->util->convertListTostring($charList);
    }

    public function removeA($txt) {
        return str_replace("a", "", $txt);
    }

    public function convertChandrabindu($txt) {
        return str_replace($this->informmapper->chandrabindu, $this->informmapper->chandrabinduThaiInform, $txt);
    }

    public function swapAnusvaraAndChandrabindu($txt) {
        $txt = str_replace("า" . $this->informmapper->anusvara, $this->informmapper->anusvara . "า", $txt);
        $txt = str_replace("า" . $this->informmapper->chandrabindu, $this->informmapper->chandrabindu . "า", $txt);
        return $txt;
    }

}
