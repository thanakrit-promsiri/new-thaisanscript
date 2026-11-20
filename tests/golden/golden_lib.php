<?php

/**
 * Golden (characterization) test library for ThaiSanscript.
 *
 * จุดประสงค์: บันทึกผลลัพธ์ "ปัจจุบัน" ของการแปลง แล้วใช้ตรวจว่า refactor แล้วผลไม่เปลี่ยน
 * ไม่ได้ตัดสินว่าผลลัพธ์ถูกหรือผิดตามหลักภาษา แค่ตรวจว่า "เหมือนเดิม"
 *
 * ใช้ผ่าน golden_record.php (บันทึก) และ golden_verify.php (ตรวจ)
 */
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT);
mb_internal_encoding('UTF-8');

$thaiSanscriptDir = realpath(__DIR__ . '/../../thaisanscript');
require_once $thaiSanscriptDir . '/ThaiSanscriptAPI.php';
require_once $thaiSanscriptDir . '/ThaiSanscriptRule.php';
require_once $thaiSanscriptDir . '/ThaiSanscriptInformRule.php';
require_once $thaiSanscriptDir . '/ThaiVisargaRuleConvert.php';
require_once $thaiSanscriptDir . '/Util.php';
require_once $thaiSanscriptDir . '/ThaiSanscript.php';

use ThaiSanskrit\ThaiSanscriptAPI;

/** ใช้ทุก N บรรทัดของ test.csv เป็นตัวอย่าง (ค่าน้อย = ตัวอย่างมาก / ช้าลง) */
define('GOLDEN_CSV_SAMPLE_EVERY', 100);

define('GOLDEN_EXPECTED_FILE', __DIR__ . '/golden_expected.json');

/**
 * ชุดข้อความที่ใช้แปลง: กรณีที่เขียนมือ (ครอบคลุมกฎต่าง ๆ) + ตัวอย่างจาก test.csv
 * @return string[]
 */
function golden_words() {
    $cm = "m\xCC\x90";   // m + COMBINING CANDRABINDU (U+0310)
    $rv = "r\xCC\xA5";   // r + COMBINING RING BELOW (U+0325)

    $curated = array(
        // วิสรรค์ / ะ → ั  (ThaiVisargaConvert)
        'namaḥ', 'trayaḥ', 'brahma', 'sabrahmacāriṇaśca', 'sarva', 'sarvajñāya',
        'lokottaravāda', 'bījagrāma', 'vajracchedikā', 'āryaprajñāpāramitāyai',
        'nāvadhyānaprekṣiṇa', 'upaśrotrasthāne', 'arogita', 'rakta', 'rāma',
        'avidyā', 'tattva', 'satya', 'buddha', 'dhamma', 'saṅgha', 'bhagavato',
        // อนุสวาร / จันทรพินทุ
        'saṃskṛta', 'saṃgha', 'saṃcāra', 'saṃtuṣṭa', 'saṃdhi', 'saṃpanna', 'saṃsāra',
        'aṃ', 'taṃ', 'dhammaṃ', 'evaṃ', 'sārthaṃ', 'kiṃ', 'ahaṃ', 'maṃsa', 'vaṃśa',
        'taṁ', 'dhammaṁ', 'ma' . $cm, 'sa' . $cm . 'ta',
        // สระ / พยัญชนะผสม / สระผสม
        'kaiśika', 'kauśalya', 'aiśvarya', 'auṣadha', 'ṛṣi', 'pitṝṇām', 'kḷpta', 'kṣetra', 'jñāna',
        'k' . $rv . 'ta', 'kṛta', 'khagha', 'chajha', 'ṭhaḍha', 'thadha', 'phabha', 'ñaṅa', 'ṇa',
        // สระต้นคำ (ThaiVowelInFist / AAInFist)
        'a', 'ā', 'i', 'ī', 'u', 'ū', 'e', 'o', 'ai', 'au', 'ākāśa', 'indra', 'īśvara', 'uttara', 'oṃ', 'eka',
        // เลข / เครื่องหมาย / อวครหะ
        '0123456789', 'om|', 'om||', "so'haṃ", "'pi", 'dhammo|', 'iti||',
        // หลายคำ (ช่องว่าง)
        'namo tassa bhagavato arahato sammāsambuddhassa',
        'buddhaṃ saraṇaṃ gacchāmi',
        'namo buddhāya',
        'ṭhāna ṭhita',
        // ขอบ: ว่าง / ตัวเดียว / ไม่ใช่อักษรโรมัน
        '', ' ', 'x', 'abc123', 'ธรรม', 'a b  c',
    );

    $words = $curated;
    $csv = realpath(__DIR__ . '/../../thaisanscript/test.csv');
    if ($csv && ($h = fopen($csv, 'r')) !== false) {
        $n = 0;
        while (($row = fgetcsv($h, 0, ',')) !== false) {
            $n++;
            if ($n % GOLDEN_CSV_SAMPLE_EVERY !== 0) {
                continue;
            }
            if (isset($row[0]) && trim($row[0]) !== '') {
                $words[] = mb_strtolower(trim($row[0]));
            }
        }
        fclose($h);
    }
    return array_values(array_unique($words));
}

/** ข้อความหลายบรรทัด (ทดสอบ jsonOutput / convertLineTxt) */
function golden_texts() {
    return array(
        "namo tassa bhagavato\narahato sammāsambuddhassa",
        "Buddhaṃ saraṇaṃ gacchāmi\r\nDhammaṃ saraṇaṃ gacchāmi\r\nSaṅghaṃ saraṇaṃ gacchāmi",
        "oṃ maṇi padme hūṃ||",
        "",
    );
}

/** เรียก closure แล้วคืนผลเป็นสตริง; ถ้า exception / echo ก็บันทึกไว้ด้วย */
function golden_call($fn) {
    ob_start();
    try {
        $result = $fn();
        $echo = ob_get_clean();
        $out = is_string($result) ? $result : json_encode($result, JSON_UNESCAPED_UNICODE);
        if ($echo !== '') {
            $out .= ' [[echo:' . $echo . ']]';
        }
        return $out;
    } catch (\Exception $e) {
        ob_end_clean();
        return '!ERROR ' . get_class($e) . ': ' . $e->getMessage();
    } catch (\Throwable $e) { // PHP 7+ (PHP 5 ไม่มีคลาสนี้ จึงไม่ถูกจับตรงนี้)
        ob_end_clean();
        return '!ERROR ' . get_class($e) . ': ' . $e->getMessage();
    }
}

/**
 * คำนวณผลทั้งหมดจากโค้ดปัจจุบัน
 * ใช้ API instance ใหม่ทุกครั้ง เพื่อไม่ให้ state (เช่น setLang("pali")) รั่วข้ามเคส
 * @return array  ['words' => [...], 'texts' => [...]]
 */
function golden_compute() {
    $langs = array('sans', 'pali');
    $dests = array('thai', 'lao');

    $words = array();
    foreach (golden_words() as $w) {
        $row = array();
        foreach ($langs as $lang) {
            foreach ($dests as $dest) {
                $row["$lang/$dest/normal"] = golden_call(function () use ($w, $lang, $dest) {
                    $api = new ThaiSanscriptAPI();
                    return $api->convertThai($w, $lang, $dest);
                });
                $row["$lang/$dest/inform"] = golden_call(function () use ($w, $lang, $dest) {
                    $api = new ThaiSanscriptAPI();
                    return $api->convertThaiInform($w, $lang, $dest);
                });
            }
        }
        $words[$w] = $row;
    }

    $texts = array();
    foreach (golden_texts() as $t) {
        $row = array();
        foreach ($langs as $lang) {
            foreach ($dests as $dest) {
                $row["json/$lang/$dest"] = golden_call(function () use ($t, $lang, $dest) {
                    $api = new ThaiSanscriptAPI();
                    return $api->jsonOutput($t, $lang, $dest);
                });
            }
        }
        $texts[$t] = $row;
    }

    return array('words' => $words, 'texts' => $texts);
}

function golden_encode($data) {
    return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
