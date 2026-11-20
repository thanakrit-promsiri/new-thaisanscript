<?php

namespace ThaiSanskrit;

use ThaiSanskrit\ThaiSanscript;
use ThaiSanskrit\ThaiVisargaConvert;
use ThaiSanskrit\Util;

class ThaiSanscriptRule {

    public $thaimapper;
    public $visarga;
    private $util;

    public function __construct(?Util $util = NULL) {
        $this->thaimapper = new ThaiSanscript(FALSE);
        $this->util = $util ?: new Util(FALSE, $this->thaimapper);
        $this->visarga = new ThaiVisargaConvert($this->util);
    }

    public function convert($romanize, $lang = "sans", $destType = "thai") {
        $txt = $romanize;
        $txt = $this->util->convertAvagarahaRemove($txt);
        $txt = $this->util->convertRomanChandrabinduToSingle($txt);
        $txt = $this->util->convertNumber($txt);
        $txt = $this->util->convertRomanizeMixConsonant($txt);
        $txt = $this->util->convertRomanizeMixVowel($txt, $lang);
        $txt = $this->util->convertRomanizeSingleConsonant($txt);
        $txt = $this->util->convertRomanizeSingleVowel($txt);
        $txt = $this->convertAnusvaraAndChandrabindu($txt, $lang);
        $txt = $this->util->convertThaiVowelInFist($txt);
        $txt = $this->convertThaiVisarga($txt);
        $txt = $this->util->convertThaiVowelPrefix($txt);
        $txt = $this->util->convertThaiAAInFist($txt);
        $txt = $this->util->convertAE($txt);
        $txt = $this->util->convertAO($txt);

        if ($destType == "lao") {
            $txt = $this->util->convertThaiToLao($txt);
        }
        return $txt;
    }

    public function convertTrackMode($romanize, $lang = "sans") {
        $txt = $romanize;
        $txt = $this->util->convertAvagarahaRemove($txt);
        self::printTrackMode($txt);
        $txt = $this->util->convertRomanChandrabinduToSingle($txt);
        self::printTrackMode($txt, "begin");
        $txt = $this->util->convertNumber($txt);
        self::printTrackMode($txt, "Number");
        $txt = $this->util->convertRomanizeMixConsonant($txt);
        self::printTrackMode($txt, "MixCon");
        $txt = $this->util->convertRomanizeMixVowel($txt, $lang);
        self::printTrackMode($txt, "MixVow");
        $txt = $this->util->convertRomanizeSingleConsonant($txt);
        self::printTrackMode($txt, "SingleCon");
        $txt = $this->util->convertRomanizeSingleVowel($txt);
        self::printTrackMode($txt, "SingleVow");
        $txt = $this->convertAnusvaraAndChandrabindu($txt, $lang);
        self::printTrackMode($txt, "AnusvaraAndChandrabindu");
        $txt = $this->util->convertThaiVowelInFist($txt);
        self::printTrackMode($txt, "ThaiVowelInFist");
        $txt = $this->convertThaiVisarga($txt);
        self::printTrackMode($txt, "ThaiVisarga");
        $txt = $this->util->convertThaiVowelPrefix($txt);
        self::printTrackMode($txt, "ThaiVowelPrefix");
        $txt = $this->util->convertThaiAAInFist($txt);
        self::printTrackMode($txt, "ThaiAAInFist");
        $txt = $this->util->convertAE($txt);
        $txt = $this->util->convertAO($txt);

        return $txt;
    }

    public static function printTrackMode($romanize, $state = "") {
        echo ("[" . $state . "] " . $romanize . " -> ");
    }

    public function convertAnusvaraAndChandrabindu($thaiChar, $lang) {
        $thaiChar = $thaiChar . " ";
        $charList = $this->util->charList($thaiChar);
        $this->thaimapper->setLang($lang);

        $anusvara = $this->thaimapper->anusvara;
        $chandrabindu = $this->thaimapper->chandrabindu;

        foreach ($charList as $i => $char) {
            if ($char === $anusvara || $char === $chandrabindu) {
                $charList[$i] = $this->thaimapper->getAnusvara($charList[$i + 1]);
            }
        }
        return trim($this->util->convertListTostring($charList));
    }

    public function convertThaiVisarga($thaiChar) {
        return $this->visarga->convert($thaiChar);
    }

}
