<?php

namespace ThaiSanskrit;

use ThaiSanskrit\ThaiSanscript;

class Util {

    public $thaimapper;
    public $consonantThai = 'ก ข ค ฆ ง จ ฉ ช ฌ ญ ฏ ฐ ฑ ฒ ณ ต ถ ท ธ น ป ผ พ ภ ม ย ร ล ว ศ ษ ส ห ฬ อ';
    public $consonantLao = 'ກ ຂ ຄ ຆ ງ ຈ ຉ ຊ ຌ ຎ ຏ ຐ ຑ ຒ ຓ ຕ ຖ ທ ຘ ນ ປ ຜ ພ ຠ ມ ຍ ຣ ລ ວ ຨ ຩ ສ ຫ ຬ ອ';
    public $thaiSymbol = "๐ ๑ ๒ ๓ ๔ ๕ ๖ ๗ ๘ ๙ ' ฯ ๚";
    public $laoSymbol = "໐ ໑ ໒ ໓ ໔ ໕ ໖ ໗ ໘ ໙ ' ฯ ๚";
    public $chandrabinduThai = "ัํ";
    public $chandrabinduLao = "ັ";
    public $anusvaraThai = "ํ";
    public $anusvaraLao = "ໍ";
    public $viramaThai = "ฺ";
    public $viramaLao = "຺";
    public $cancleMarkLao = "໌";
    public $cancleMarkThai = "์";
    public $vowelThai = "ั ะ า ิ ี ุ ู โ ไ เ ฤๅ ฦๅ ฤ ฦ";
    public $vowelLao = "ັ ະ າ ິ ີ ຸ ູ ໂ ໄ ເ ຤\u{E06C} ຦\u{E06C} ຤ ຦";

    public function __construct($inForm = FALSE, ?ThaiSanscript $mapper = NULL) {
        $this->thaimapper = $mapper ?: new ThaiSanscript($inForm);
        $this->consonantThai = preg_split("/[\s,]+/", $this->consonantThai);
        $this->consonantLao = preg_split("/[\s,]+/", $this->consonantLao);
        $this->laoSymbol = preg_split("/[\s,]+/", $this->laoSymbol);
        $this->thaiSymbol = preg_split("/[\s,]+/", $this->thaiSymbol);
        $this->vowelThai = preg_split("/[\s,]+/", $this->vowelThai);
        $this->vowelLao = preg_split("/[\s,]+/", $this->vowelLao);
    }

    /* ******************* util part ************************ */

    public function convertThaiToLao($txt) {
        $txt = str_replace($this->chandrabinduThai, $this->chandrabinduLao, $txt);
        $txt = str_replace($this->cancleMarkThai, $this->cancleMarkLao, $txt);
        $txt = str_replace($this->anusvaraThai, $this->anusvaraLao, $txt);
        $txt = str_replace($this->viramaThai, $this->viramaLao, $txt);
        $txt = str_replace($this->thaiSymbol, $this->laoSymbol, $txt);
        $txt = str_replace($this->consonantThai, $this->consonantLao, $txt);
        $txt = str_replace($this->vowelThai, $this->vowelLao, $txt);

        return $txt;
    }

    public function swapArray($swapCondition, $charList, $index) {
        if ($swapCondition && isset($charList[1])) {
            $tmp = $charList[$index];
            $charList[$index] = $charList[$index - 1];
            $charList[$index - 1] = $tmp;
        }
        return $charList;
    }

    public function Mapper($mapping, $romanize) {
        return str_replace(array_keys($mapping), array_values($mapping), $romanize);
    }

    /* ******************* check part ************************ */

    public function isThaiVowel($strChar) {
        $mapping = $this->thaimapper->mappingIsThaiVowel();
        return isset($mapping[$strChar]);
    }

    public function isThaiConsonant($strChar) {
        $mapping = $this->thaimapper->mappingIsThaiConsonant();
        return isset($mapping[$strChar]);
    }

    public function isThaiCharacter($strChar) {
        return $this->isThaiConsonant($strChar) || $this->isThaiVowel($strChar);
    }

    /* ******************* convertList ************************ */

    public function convertListTostring($charList) {
        return implode("", $charList);
    }

    public function charList($thaiChar) {
        if ($thaiChar === '' || $thaiChar === NULL) {
            return array();
        }
        return mb_str_split((string) $thaiChar, 1, 'UTF-8');
    }

    /* ******************* convert ************************ */

    public function convertAvagarahaRemove($txt) {
        return str_replace("'", "", $txt);
    }

    public function convertRomanChandrabinduToSingle($txt) {
        if ($this->thaimapper->chandrabinduRoman === $this->thaimapper->chandrabinduRomanSingle) {
            return $txt;
        }
        return str_replace($this->thaimapper->chandrabinduRoman, $this->thaimapper->chandrabinduRomanSingle, $txt);
    }

    public function convertNumber($txt) {
        return str_replace($this->thaimapper->numRm, $this->thaimapper->numTh, $txt);
    }

    public function convertRomanizeSingleConsonant($romanize) {
        return str_replace($this->thaimapper->singleConsonantRm, $this->thaimapper->singleConsonantTh, $romanize);
    }

    public function convertRomanizeMixConsonant($romanize) {
        return str_replace($this->thaimapper->mixConsonantRm, $this->thaimapper->mixConsonantTh, $romanize);
    }

    public function convertRomanizeSingleVowel($romanize) {
        return str_replace($this->thaimapper->singleVowelRm, $this->thaimapper->singleVowelTh, $romanize);
    }

    public function convertRomanizeMixVowel($romanize, $lang = "sans") {
        $this->thaimapper->setLang($lang);
        return str_replace($this->thaimapper->mixVowelRm, $this->thaimapper->mixVowelTh, $romanize);
    }

    public function convertAO($txt) {
        return str_replace("ัโ", "ะโ", $txt);
    }

    public function convertAE($txt) {
        return str_replace("ัเ", "ะเ", $txt);
    }

    public function convertThaiVowelPrefix($thaiChar) {
        $thaiChar = "     " . $thaiChar;
        $charList = $this->charList($thaiChar);
        foreach ($charList as $i => $char) {
            $check = ($char === "เ" || $char === "โ" || $char === "ไ");
            if ($check) {
                $samyuta = ($charList[$i - 1] == "ร" || $charList[$i - 1] == "ล");

                // แบบปรับรูป (FCR)
                $condition1 = $this->isThaiConsonant($charList[$i - 2]) &&
                        $samyuta &&
                        ($charList[$i - 2] != "ร" && $charList[$i - 2] != "ล") &&
                        $charList[$i - 2] != "อ" &&
                        $charList[$i - 3] != "เ" &&
                        $charList[$i - 3] != "โ" &&
                        $charList[$i - 3] != "ไ";

                // แบบคงรูป (FCDR)
                $condition2 = $this->isThaiConsonant($charList[$i - 3]) &&
                        $samyuta &&
                        ($charList[$i - 3] != "ร" && $charList[$i - 3] != "ล") &&
                        $charList[$i - 2] == "ฺ" &&
                        $charList[$i - 4] != "เ" &&
                        $charList[$i - 4] != "โ" &&
                        $charList[$i - 4] != "ไ";

                if ($condition1) {
                    $charList = $this->swapArray(TRUE, $charList, $i);
                    $charList = $this->swapArray(TRUE, $charList, $i - 1);
                } elseif ($condition2) {
                    $charList = $this->swapArray(TRUE, $charList, $i);
                    $charList = $this->swapArray(TRUE, $charList, $i - 1);
                    $charList = $this->swapArray(TRUE, $charList, $i - 2);
                } elseif ($this->isThaiConsonant($charList[$i - 1])) {
                    $charList = $this->swapArray(TRUE, $charList, $i);
                }
            }
        }
        return trim($this->convertListTostring($charList));
    }

    public function convertThaiVowelInFist($thaiChar) {
        $thaiChar = " " . $thaiChar . " ";
        $mapping = $this->thaimapper->thaiVowelInFist;
        $charList = $this->charList($thaiChar);

        if (isset($charList[0])) {
            if (isset($mapping[$charList[0]])) {
                $charList[0] = $mapping[$charList[0]];
            }
            foreach ($charList as $index => $char) {
                if ($index > 0 && isset($mapping[$char])) {
                    $prev = $charList[$index - 1];
                    $check1 = ($prev == "อ" || !$this->isThaiConsonant($prev));
                    if (!$check1) {
                        continue;
                    }
                    if ($char != "เ" && $char != "า") {
                        $charList[$index] = $mapping[$char];
                    } elseif ($char == "เ" && $charList[$index + 1] != "า") {
                        $charList[$index] = $mapping[$char];
                    } elseif ($char == "า" && $prev != "เ") {
                        $charList[$index] = $mapping[$char];
                    }
                }
            }
            $thaiChar = trim($this->convertListTostring($charList));
            $thaiChar = str_replace("\xE2\x80\x8D", "", $thaiChar); // Remove ZERO WIDTH JOINER
        }
        return $thaiChar;
    }

    public function convertThaiAAInFist($thaiChar) {
        $charList = $this->charList($thaiChar);
        foreach ($charList as $i => $char) {
            if ($i > 0 && $char == 'า' && !$this->isThaiConsonant($charList[$i - 1])) {
                $charList[$i] = "อา";
            }
        }
        $thaiChar = $this->convertListTostring($charList);
        $thaiChar = str_replace("\xE2\x80\x8D", "", $thaiChar); // Remove ZERO WIDTH JOINER
        return $thaiChar;
    }

}
