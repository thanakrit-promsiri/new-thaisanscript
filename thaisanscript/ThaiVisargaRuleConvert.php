<?php

namespace ThaiSanskrit;

use ThaiSanskrit\Util;

class ThaiVisargaConvert {

    private $util;

    public function __construct(?Util $util = NULL) {
        $this->util = $util ?: new Util();
    }

    public function convert($thaiChar) {
        $thaiChar = "    " . $thaiChar . "       "; // 4 leading + 6 trailing spaces for lookbehind/lookahead
        $charList = $this->util->charList($thaiChar);
        foreach ($charList as $i => $char) {
            if ($char === "ะ") {
                $charList = $this->a_before_rule($charList, $i);
                $charList = $this->ah_rule($charList, $i);
                $charList = $this->ar_rule($charList, $i);
                $charList = $this->thaivisarga_rule($charList, $i);
            }
        }
        return trim($this->util->convertListTostring($charList));
    }

    protected function a_before_rule($charList, $i) {
        // "ะนกะ" -> "อันกะ"; กรณี สระ ะ นำหน้าให้ใช้ อั
        if ($charList[$i - 1] == " " && $charList[$i] == "ะ") {
            $charList[$i] = "อั";
        }
        return $charList;
    }

    protected function ah_rule($charList, $i) {
        // brahma ยกเว้น namaḥ ตัด พรัหมะ เป็น พรหมะ เว้นไว้แต่ นะมะห์
        $_current = $charList[$i];
        $_after1 = $charList[$i + 1];
        $_after2 = $charList[$i + 2];

        $condition = $_current == "ะ" &&
                $_after1 == "ห" &&
                $this->util->isThaiConsonant($_after1) &&
                !$this->util->isThaiVowel($_after2) &&
                $_after2 != "์";
        if ($condition) {
            $charList[$i] = "";
        }
        return $charList;
    }

    protected function ar_rule($charList, $i) {
        // lokottaravāda sarva เปลี่ยน สะรวา เป็น ร หัน สรรวะ
        $_before = $charList[$i - 1];
        $_current = $charList[$i];
        $_after1 = $charList[$i + 1];
        $_after2 = $charList[$i + 2];

        $condition = $_current == "ะ" &&
                $this->util->isThaiConsonant($_before) &&
                $_after1 == "ร" &&
                !$this->util->isThaiVowel($_after2);

        if ($condition) {
            $charList[$i] = "ร";
        }
        return $charList;
    }

    protected function thaivisarga_rule($charList, $i) {
        if ($charList[$i + 1] != "ว") { // เว้น ว ไว้ทุกกรณี เช่น ตะว เพื่อป้องกันการออกเสียงสระ อัว เช่น ตัว
            $charList = $this->thaivisarga_non_consonantclusters($charList, $i);
            $charList = $this->thaivisarga_consonantclusters($charList, $i);
            $charList = $this->thaivisarga_lastword($charList, $i);
            $charList = $this->thaivisarga_non_sunskritvisarga($charList, $i);
        }
        return $charList;
    }

    protected function thaivisarga_non_consonantclusters($charList, $i) {
        // 'ะกธ' !ร เว้นแต่คำควบกล้ำ เช่น พีชะครามะ CVCRVCV จะไม่เป็น พีชัครามะ
        $_current = $charList[$i];
        $_after1 = $charList[$i + 1];
        $_after2 = $charList[$i + 2];
        $_after3 = $charList[$i + 3];

        $condition = $_current === "ะ" &&
                $this->util->isThaiConsonant($_after1) &&
                $this->util->isThaiConsonant($_after2);

        $condition1 = $condition && $_after2 != "ร";
        $condition2 = $condition && $_after2 == "ร" && !$this->util->isThaiVowel($_after3);

        if ($condition1 || $condition2) {
            $charList[$i] = "ั";
        }
        return $charList;
    }

    protected function thaivisarga_consonantclusters($charList, $i) {
        // C A C R V C[C] -> visarga (ยกเว้น ประช เช่น āryaprajñāpāramitāyai)
        $_before1 = $charList[$i - 1];
        $_current = $charList[$i];
        $_after1 = $charList[$i + 1];
        $_after2 = $charList[$i + 2];
        $_after3 = $charList[$i + 3];
        $_after4 = $charList[$i + 4];
        $_after5 = $charList[$i + 5];

        $condition = $this->util->isThaiConsonant($_before1) &&
                $_current == "ะ" &&
                $this->util->isThaiConsonant($_after1) &&
                $_after2 == "ร" &&
                $this->util->isThaiVowel($_after3) &&
                $this->util->isThaiConsonant($_after4) &&
                $_after4 != "ห" &&
                $this->util->isThaiConsonant($_after5) &&
                !($_after1 == "ป" && $_after2 == "ร" && $_after3 == "ะ" && $_after4 == "ช");

        if ($condition) {
            $charList[$i] = "ั";
        }
        return $charList;
    }

    protected function thaivisarga_lastword($charList, $i) {
        // 'ะก ' -> 'รัก ' ตัวสุดท้ายของคำ
        $_current = $charList[$i];
        $_after1 = $charList[$i + 1];
        $_after2 = $charList[$i + 2];

        $condition = $_current == "ะ" &&
                $this->util->isThaiConsonant($_after1) &&
                $_after1 != " " &&
                $_after2 == " ";

        if ($condition) {
            $charList[$i] = "ั";
        }
        return $charList;
    }

    protected function thaivisarga_non_sunskritvisarga($charList, $i) {
        // เว้นวิสรรค์ของสันสกฤต namaḥ 'นะมะห์' trayaḥ 'ตระยะห์' ไม่ให้เป็น นะมัห
        $_current = $charList[$i];
        $_after1 = $charList[$i + 1];
        $_after2 = $charList[$i + 2];

        $condition = $_current == "ะ" &&
                $this->util->isThaiConsonant($_after1) &&
                !$this->util->isThaiCharacter($_after2) &&
                $_after2 != "์";

        if ($condition) {
            $charList[$i] = "ั";
        }
        return $charList;
    }

}
