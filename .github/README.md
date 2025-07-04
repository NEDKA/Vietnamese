# 🇻🇳 Vietnamese
The Vietnamese toolkit for PHP.

## Requirements
- PHP 8.0+.

## Installation
Require this package in your `composer.json`:
```
composer require nedka/vietnamese
composer install
```

---
Import the package:
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

Sorting by values in a simple array:
```php
Vietnamese::sortWord(['Ă', 'A', 'Â', 'À', 'Á'])
```
Result: `['A', 'Á', 'À', 'Ă', 'Â']`

Sorting a two-dimensional array by multiple keys in order:
```php
$array = [
	['name' => 'Cần Thơ', 'valid_date' => '2004-01-01'],
	['name' => 'Cà Mau', 'valid_date' => '1997-01-01'],
	['name' => 'Cần Thơ', 'valid_date' => '1992-01-01']
];
$array = Vietnamese::sortWord($array, ['name', 'valid_date']);
```
Result:
```php
array:3 [
	0 => array:2 [
		'name' => 'Cà Mau'
		'valid_date' => '1997-01-01'
	]
	1 => array:2 [
		'name' => 'Cần Thơ'
		'valid_date' => '1992-01-01'
	]
	2 => array:2 [
		'name' => 'Cần Thơ'
		'valid_date' => '2004-01-01'
	]
]
```

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
Scan and detect incorrect words in Vietnamese:

```php
Vietnamese::scanWords('Xứ Wales thắng Nga, đứng nhất bảng B')
```
Result: `['Wales']`

Otherwise, get correct words:
```php
Vietnamese::scanWords('Xứ Wales thắng Nga, đứng nhất bảng B', false)
```
Result: `['Xứ', 'thắng', 'Nga', 'đứng', 'nhất', 'bảng', 'B']`

---
Print the way to speak a Vietnamese text string:
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
