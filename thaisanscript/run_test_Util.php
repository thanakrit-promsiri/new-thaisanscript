<?php
// ไฟล์ run_tests.php

require_once 'Util.php'; // ตรวจสอบว่า path ถูกต้อง

use ThaiSanskrit\Util;
use ThaiSanskrit\ThaiSanscript;

header('Content-Type: text/plain; charset=utf-8');

echo "Running Util.php tests...\n\n";

$util = new Util();
$passed = 0;
$failed = 0;
$total_tests = 0;

function runTest($testName, $actual, $expected, &$passed, &$failed) {
    global $total_tests;
    $total_tests++;
    if ($actual === $expected) {
        echo "✅ PASS: '{$testName}' passed.\n";
        $passed++;
    } else {
        echo "❌ FAIL: '{$testName}' failed. Expected '{$expected}', but got '{$actual}'.\n";
        $failed++;
    }
}

// --- Testing Util part ---
echo "--- Testing Util part ---\n\n";

runTest("convertThaiToLao (ช -> ຆ)", $util->convertThaiToLao("ช"), "ຆ", $passed, $failed);
runTest("convertThaiToLao (อักขระ -> ອັ຃ຣະ)", $util->convertThaiToLao("อักขระ"), "ອັ຃ຣະ", $passed, $failed);
runTest("convertThaiToLao (123 -> ໑໒໓)", $util->convertThaiToLao("123"), "໑໒໓", $passed, $failed);

$array = ['b', 'a', 'c'];
$swappedArray = $util->swapArray(true, $array, 1);
runTest("swapArray", implode("", $swappedArray), "abc", $passed, $failed);

$mapping = ['a' => 'A', 'b' => 'B'];
runTest("Mapper", $util->Mapper($mapping, "abc"), "ABc", $passed, $failed);

// --- Testing Check part ---
echo "\n--- Testing Check part ---\n\n";

runTest("isThaiVowel", $util->isThaiVowel("ะ"), true, $passed, $failed);
runTest("isThaiConsonant", $util->isThaiConsonant("ก"), true, $passed, $failed);
runTest("isThaiCharacter (Vowel)", $util->isThaiCharacter("ะ"), true, $passed, $failed);
runTest("isThaiCharacter (Consonant)", $util->isThaiCharacter("ก"), true, $passed, $failed);
runTest("isThaiCharacter (Non-Thai)", $util->isThaiCharacter("a"), false, $passed, $failed);

// --- Testing convertList part ---
echo "\n--- Testing convertList part ---\n\n";

$list = ['a', 'b', 'c'];
runTest("convertListToString", $util->convertListTostring($list), "abc", $passed, $failed);

runTest("charList", implode(",", $util->charList("กขฃ")), "ก,ข,ฃ", $passed, $failed);

// --- Testing Convert part ---
echo "\n--- Testing Convert part ---\n\n";

runTest("convertAvagarahaRemove", $util->convertAvagarahaRemove("อ'ะ"), "อะ", $passed, $failed);
runTest("convertAO", $util->convertAO("ัโ"), "ะโ", $passed, $failed);
runTest("convertAE", $util->convertAE("ัเ"), "ะเ", $passed, $failed);

// Testing Thai Vowel Prefix with more cases
runTest("convertThaiVowelPrefix (case 1)", $util->convertThaiVowelPrefix("โรฺกิตะ"), "โรกิตะ", $passed, $failed);
runTest("convertThaiVowelPrefix (case 2)", $util->convertThaiVowelPrefix("อโรคิต"), "อโรคิต", $passed, $failed);
runTest("convertThaiVowelPrefix (case 3)", $util->convertThaiVowelPrefix("เตรส"), "เตรส", $passed, $failed);
runTest("convertThaiVowelPrefix (case 4)", $util->convertThaiVowelPrefix("ไตร"), "ไตร", $passed, $failed);

// Testing Thai Vowel In Fist
runTest("convertThaiVowelInFist (case 1)", $util->convertThaiVowelInFist("อัคคะ"), "อัคคะ", $passed, $failed);
runTest("convertThaiVowelInFist (case 2)", $util->convertThaiVowelInFist("อิทะ"), "อิทะ", $passed, $failed);

// Testing Thai AA In Fist
runTest("convertThaiAAInFist (case 1)", $util->convertThaiAAInFist("าคม"), "อาคม", $passed, $failed);

echo "\n--- Test Summary ---\n";
echo "Total Tests: {$total_tests}\n";
echo "✅ Passed: {$passed}\n";
echo "❌ Failed: {$failed}\n";