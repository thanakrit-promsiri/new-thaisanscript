<?php

namespace ThaiSanskrit;

/**
 * ThaiSanscript
 *
 * Character mapping tables and anusvara rules for Roman (IAST) -> Thai Sanskrit/Pali transliteration.
 * Released under the MIT and GPL Licenses.
 *
 * @author Thanakrit.P
 */
class ThaiSanscript {

    public $chandrabindu = "ँ";
    public $chandrabinduThaiInform = "ัํ";
    public $chandrabinduRoman = "m̐";
    public $chandrabinduRomanSingle = "m̐";
    public $anusvara = "ํ";

    public $thaiVowelInFist = array(
        'ะ' => 'อะ',
        "า" => "อา",
        "ิ" => "อิ",
        "ี" => "อี",
        "ุ" => "อุ",
        "ู" => "อู",
        "เ" => "เอ",
        "โ" => "โอ",
        "ไ" => "ไอ"
    );

    public $singleVowel = array(
        "a" => "ะ",
        "ā" => "า",
        "i" => "ิ",
        "ī" => "ี",
        "u" => "ุ",
        "ū" => "ู",
        "e" => "เ",
        "ē" => "เ",
        "ō" => "โ",
        "o" => "โ",
        "ṛ" => "ฤ",
        "ṝ" => "ฤๅ",
        "ḷ" => "ฦ",
        "ḹ" => "ฦๅ",
    );

    public $mixVowel = array(
        "ai" => "ไ",
        "au" => "เา",
        "r̥" => "ฤ",
        "m̐" => "ँ"
    );

    private static $mixVowelSans = array(
        "ai" => "ไ",
        "au" => "เา",
        "r̥" => "ฤ",
        "m̐" => "ँ"
    );

    private static $mixVowelPali = array(
        "r̥" => "ฤ",
        "m̐" => "ँ"
    );

    public $singleConsonant = array(
        "k" => "ก",
        "g" => "ค",
        "ṅ" => "ง",
        "c" => "จ",
        "j" => "ช",
        "ñ" => "ญ",
        "ṭ" => "ฏ",
        "ḍ" => "ฑ",
        "ṇ" => "ณ",
        "t" => "ต",
        "d" => "ท",
        "n" => "น",
        "p" => "ป",
        "b" => "พ",
        "m" => "ม",
        "y" => "ย",
        "r" => "ร",
        "l" => "ล",
        "v" => "ว",
        "ḻ" => "ฬ",
        "ś" => "ศ",
        "ṣ" => "ษ",
        "s" => "ส",
        "h" => "ห",
        "ṁ" => "ํ",
        "ṃ" => "ํ",
        "ḥ" => "ห์",
        "'" => "'"
    );

    public $mixConsonant = array(
        "kh" => "ข",
        "ch" => "ฉ",
        "ṭh" => "ฐ",
        "th" => "ถ",
        "ph" => "ผ",
        "n̄" => "ญ",
        "gh" => "ฆ",
        "jh" => "ฌ",
        "ḍh" => "ฒ",
        "dh" => "ธ",
        "bh" => "ภ"
    );

    public $num = array(
        "0" => "๐",
        "1" => "๑",
        "2" => "๒",
        "3" => "๓",
        "4" => "๔",
        "5" => "๕",
        "6" => "๖",
        "7" => "๗",
        "8" => "๘",
        "9" => "๙",
        "||" => "๚",
        "॥" => "๚",
        "|" => "ฯ",
        "।" => "ฯ",
    );

    public $mixVowelTh;
    public $mixVowelRm;
    public $singleVowelTh;
    public $singleVowelRm;
    public $mixConsonantTh;
    public $mixConsonantRm;
    public $singleConsonantTh;
    public $singleConsonantRm;
    public $numRm;
    public $numTh;
    public $revert_vowel;
    public $revert_consonant;
    public $anusvaraLast;
    public $thaiVowelInFist1;
    public $thaiVowelInFist2;
    private $lang;

    public function __construct($inform = FALSE) {
        $this->lang = 'sans';
        if ($inform) {
            $this->singleConsonant["ḥ"] = "ะ";
            $this->singleVowel["a"] = "a";
            $this->thaiVowelInFist = array_merge($this->thaiVowelInFist, array("a" => "อ"));
            unset($this->thaiVowelInFist['ะ']);
        }

        $this->revert_vowel = $this->setMappingIsThaiVowel();
        $this->revert_consonant = $this->setMappingIsThaiConsonant();
        $this->anusvaraLast = $this->setAnusvara();

        $this->mixVowelRm = array_keys($this->mixVowel);
        $this->mixVowelTh = array_values($this->mixVowel);

        $this->singleVowelRm = array_keys($this->singleVowel);
        $this->singleVowelTh = array_values($this->singleVowel);

        $this->mixConsonantRm = array_keys($this->mixConsonant);
        $this->mixConsonantTh = array_values($this->mixConsonant);

        $this->singleConsonantRm = array_keys($this->singleConsonant);
        $this->singleConsonantTh = array_values($this->singleConsonant);

        $this->numRm = array_keys($this->num);
        $this->numTh = array_values($this->num);

        $this->thaiVowelInFist1 = array_keys($this->thaiVowelInFist);
        $this->thaiVowelInFist2 = array_values($this->thaiVowelInFist);
    }

    public function setLang($lang) {
        $this->lang = $lang;
        $this->mixVowel = ($this->lang == "pali") ? self::$mixVowelPali : self::$mixVowelSans;
        $this->mixVowelRm = array_keys($this->mixVowel);
        $this->mixVowelTh = array_values($this->mixVowel);
    }

    public function mappingIsThaiVowel() {
        return $this->revert_vowel;
    }

    public function mappingIsThaiConsonant() {
        return $this->revert_consonant;
    }

    public function setRevertFlag($revert, $torevert) {
        foreach ($torevert as $key => $value) {
            if (!isset($revert[$value])) {
                $revert[$value] = $key;
            }
        }
        return $revert;
    }

    public function getAnusvara($nextConsonant = "") {
        if (isset($this->anusvaraLast[$nextConsonant])) {
            return $this->anusvaraLast[$nextConsonant];
        }
        return ($this->lang == 'sans') ? "ม" : "ง";
    }

    private function setAnusvara() {
        return array(
            // kaṇṭhya (Guttural)
            "ก" => "ง", "ข" => "ง", "ค" => "ง", "ฆ" => "ง", "ง" => "ง", "ห" => "ง",
            // tālavya (Palatal)
            "จ" => "ญ", "ฉ" => "ญ", "ช" => "ญ", "ฌ" => "ญ", "ญ" => "ญ", "ศ" => "ญ",
            // mūrdhanya (Retroflex)
            "ฏ" => "ณ", "ฐ" => "ณ", "ฑ" => "ณ", "ฒ" => "ณ", "ณ" => "ณ", "ษ" => "ณ",
            // dantya (Dental)
            "ต" => "น", "ถ" => "น", "ท" => "น", "ธ" => "น", "น" => "น", "ส" => "น",
            // oṣṭhya (Labial)
            "ป" => "ม", "ผ" => "ม", "พ" => "ม", "ภ" => "ม", "ม" => "ม",
        );
    }

    private function setMappingIsThaiVowel() {
        $revert = array("ั" => 'a');
        $revert = $this->setRevertFlag($revert, $this->singleVowel);
        $revert = $this->setRevertFlag($revert, $this->mixVowel);
        return $revert;
    }

    private function setMappingIsThaiConsonant() {
        $revert = array("อ" => 'a');
        $revert = $this->setRevertFlag($revert, $this->singleConsonant);
        $revert = $this->setRevertFlag($revert, $this->mixConsonant);
        return $revert;
    }

}
