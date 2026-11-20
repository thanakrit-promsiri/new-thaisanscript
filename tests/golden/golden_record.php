<?php

/**
 * บันทึก golden data จากโค้ด "ก่อน refactor"
 *
 *   php tests/golden/golden_record.php          # สร้าง golden_expected.json (ปฏิเสธถ้ามีไฟล์อยู่แล้ว)
 *   php tests/golden/golden_record.php --force  # เขียนทับ (ทำเฉพาะเมื่อตั้งใจเปลี่ยนพฤติกรรม)
 *
 * คำเตือน: ห้าม record ใหม่หลัง refactor เพื่อให้เทสต์ผ่าน เพราะจะทำให้พลาด regression
 */
require_once __DIR__ . '/golden_lib.php';

$force = in_array('--force', $argv, true);

if (file_exists(GOLDEN_EXPECTED_FILE) && !$force) {
    fwrite(STDERR, "golden_expected.json มีอยู่แล้ว ไม่เขียนทับ (ใช้ --force ถ้าตั้งใจ)\n");
    exit(2);
}

$data = golden_compute();
$json = golden_encode($data);
if ($json === false) {
    fwrite(STDERR, "json_encode ล้มเหลว: " . json_last_error_msg() . "\n");
    exit(3);
}

file_put_contents(GOLDEN_EXPECTED_FILE, $json);

$errors = 0;
foreach ($data as $group) {
    foreach ($group as $row) {
        foreach ($row as $out) {
            if (strpos($out, '!ERROR') === 0) {
                $errors++;
            }
        }
    }
}

echo "บันทึกแล้ว: " . GOLDEN_EXPECTED_FILE . "\n";
echo "words=" . count($data['words']) . " texts=" . count($data['texts']) . "\n";
echo "ผลลัพธ์ที่เป็น error (ถูกบันทึกเป็น golden ด้วย)=$errors\n";
