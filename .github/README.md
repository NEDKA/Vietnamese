# 🇻🇳 Vietnamese
The Vietnamese library for PHP.

## Requirements
- PHP 8.0+.

## Installation
Require this library in your `composer.json`:
```
composer require nedka/vietnamese
```

---
Import the library:
```php
use NEDKA\Vietnamese\Vietnamese;
```

## Usage
Format names:
```php
Vietnamese::formatName('ViỆt NaM')
```
Result: `Việt Nam`

---
Remove all accents:
```php
Vietnamese::removeAccent('Việt Nam')
```
Result: `Viet Nam`

---
Convert into NCR Decimal:
```php
Vietnamese::removeAccent('Việt Nam', 'ncr_decimal')
```
Result: `Vi&#7879;t Nam`

---
Correct wrong accent placements:
```php
Vietnamese::fixAccent('Vịêt Nam')
```
Result: `Việt Nam`

---
Correct wrong cases between "i" and "y":
```php
Vietnamese::fixIY('Thi tuổi Kỉ Tị')
```
Result: `Thi tuổi Kỷ Tỵ`

---
Sorting words:
```php
Vietnamese::sortWord(['Ă', 'A', 'Â', 'À', 'Á'])
```
Result: `['A', 'Á', 'À', 'Ă', 'Â']`

---
Sorting people names:
```php
Vietnamese::sortPeopleName(['Nguyễn Văn Đảnh', 'Nguyễn VĂN Đàn', 'nguYỄn Văn Đàng', 'NGUYỄN Văn Đang', 'nguyễn anh đang'])
```
Result: `['Nguyễn Anh Đang', 'Nguyễn Văn Đang', 'Nguyễn Văn Đàn', 'Nguyễn Văn Đàng', 'Nguyễn Văn Đảnh']`

---
Check a character in the Vietnamese alphabet:
```php
Vietnamese::checkChar('w')
```
Result: `false`

---
Print the way to speak:
```php
Vietnamese::speak('Việt Nam')
```
Result: `i ê tờ iêt, vờ iêt viêt nặng /việt/; a mờ am, nờ am /nam/; /việt nam/`

---
Convert number to text:
```php
Vietnamese::numberToText(1452369)
```
Result: `một triệu bốn trăm năm mươi hai nghìn ba trăm sáu mươi chín`

## License
Copyright (c) NEDKA. All rights reserved.

Licensed under the MIT License.
