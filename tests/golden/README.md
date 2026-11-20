# Golden test ของ ThaiSanscript

บันทึกผลลัพธ์ปัจจุบันของการแปลง แล้วตรวจว่า refactor แล้ว **ผลไม่เปลี่ยน**
(ตรวจว่า "เหมือนเดิม" ไม่ได้ตรวจว่า "ถูกหลักภาษา")

## ที่ครอบคลุม
- `ThaiSanscriptAPI::convertThai` และ `convertThaiInform`
  ทุกคอมโบ `lang` (`sans`, `pali`) × `dest` (`thai`, `lao`)
- `ThaiSanscriptAPI::jsonOutput` (ผ่าน `prepareTxt` + `convertLineTxt`) กับข้อความหลายบรรทัด
- ข้อมูลเข้า:
  - รายการเขียนมือ (วิสรรค์, อนุสวาร, จันทรพินทุ, สระต้นคำ, ควบกล้ำ ร/ล, เลข, `|`/`||`, อวครหะ, หลายคำ, ขอบเช่น ว่าง/ไม่ใช่โรมัน)
  - สุ่มแบบกำหนดแน่นอนจาก `thaisanscript/test.csv` ทุก ๆ `GOLDEN_CSV_SAMPLE_EVERY` (100) บรรทัด
- ใช้ `ThaiSanscriptAPI` instance ใหม่ต่อการเรียก จึงไม่ขึ้นกับ state ที่รั่ว (`setLang`)
- exception หรือ `echo` ที่เกิดขึ้นถูกบันทึกเป็น golden ด้วย

## วิธีใช้
```bash
# 1) บนโค้ดก่อน refactor: บันทึก golden (ทำครั้งเดียว แล้ว commit ไฟล์ json)
php tests/golden/golden_record.php

# 2) ระหว่าง/หลัง refactor: ตรวจ
php tests/golden/golden_verify.php          # diff 20 รายการแรก
php tests/golden/golden_verify.php --all
```

รันผ่าน WSL บน Windows (ต้องมี `php-cli` และ `php-mbstring` ใน distro):
```powershell
wsl -d Ubuntu-26.04 --cd /mnt/d/PHP_SERVER/tests/golden -- php golden_verify.php
```

> golden ชุดปัจจุบันบันทึกด้วย **PHP 8.5.4** (ผลของ `mbstring`/`str_replace` ควรเหมือนกันทุกเวอร์ชัน แต่ถ้า production ใช้ PHP เก่ามาก ควรรัน verify บนเวอร์ชันนั้นอีกครั้ง)

exit code ของ `golden_verify.php`: `0` ตรงทั้งหมด, `1` มีความต่าง, `2` ไม่มีไฟล์ golden

## ข้อควรระวัง
- **ห้าม** รัน `golden_record.php --force` หลัง refactor เพื่อให้เทสต์ผ่าน ใช้เมื่อ *ตั้งใจ* เปลี่ยนพฤติกรรมเท่านั้น (เช่น แก้บั๊ก) และควรรีวิว diff ของ `golden_expected.json` ก่อน commit
- การแก้บั๊กในข้อ 1 ของแผน (เช่น state `setLang` รั่ว) อาจทำให้ผลลัพธ์บางเคสเปลี่ยนโดยตั้งใจ ให้แยก commit ออกจาก refactor ล้วน
- ไฟล์เคสต้องบันทึกเป็น UTF-8 แบบ NFC (ตัวอักษรที่เป็น combining อย่าง `m̐` และ `r̥` เขียนเป็น escape ไบต์ไว้แล้ว)
- ต้องมี PHP ใน PATH (ใช้ `json_encode` / `mbstring`)
