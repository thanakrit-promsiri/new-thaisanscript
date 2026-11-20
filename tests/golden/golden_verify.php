<?php

/**
 * ตรวจผลลัพธ์ปัจจุบันเทียบกับ golden_expected.json
 *
 *   php tests/golden/golden_verify.php            # แสดง diff แรก 20 รายการ
 *   php tests/golden/golden_verify.php --all      # แสดง diff ทั้งหมด
 *
 * exit code: 0 = ตรงทั้งหมด, 1 = มีความต่าง, 2 = ไม่มีไฟล์ golden
 */
require_once __DIR__ . '/golden_lib.php';

if (!file_exists(GOLDEN_EXPECTED_FILE)) {
    fwrite(STDERR, "ไม่พบ golden_expected.json รัน golden_record.php บนโค้ดก่อน refactor ก่อน\n");
    exit(2);
}

$expected = json_decode(file_get_contents(GOLDEN_EXPECTED_FILE), true);
$actual = golden_compute();
$showAll = in_array('--all', $argv, true);
$limit = $showAll ? PHP_INT_MAX : 20;

$total = 0;
$diffs = 0;
foreach (array('words', 'texts') as $group) {
    $exp = isset($expected[$group]) ? $expected[$group] : array();
    $act = $actual[$group];

    foreach ($exp as $input => $row) {
        foreach ($row as $key => $want) {
            $total++;
            $got = isset($act[$input][$key]) ? $act[$input][$key] : '(ไม่มีผลลัพธ์)';
            if ($got !== $want) {
                $diffs++;
                if ($diffs <= $limit) {
                    echo "DIFF [$group] input=" . json_encode($input, JSON_UNESCAPED_UNICODE) . " case=$key\n";
                    echo "  expected: $want\n";
                    echo "  actual  : $got\n";
                }
            }
        }
    }
    foreach ($act as $input => $row) {
        if (!isset($exp[$input])) {
            $diffs++;
            if ($diffs <= $limit) {
                echo "NEW  [$group] input=" . json_encode($input, JSON_UNESCAPED_UNICODE) . " (ไม่มีใน golden)\n";
            }
        }
    }
}

if ($diffs === 0) {
    echo "PASS: ตรงกับ golden ทั้งหมด ($total ผลลัพธ์)\n";
    exit(0);
}

echo "FAIL: ต่างจาก golden $diffs รายการ จากทั้งหมด $total\n";
exit(1);
