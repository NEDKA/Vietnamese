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
Vietnamese::format('ViỆt NaM')
```
Result: `Việt Nam`

---
Remove all accents:
```php
Vietnamese::clean('Việt Nam')
```
Result: `Viet Nam`

---
Convert into NCR Decimal:
```php
Vietnamese::clean('Việt Nam', 'ncr_decimal')
```
Result: `Vi&#7879;t Nam`

---
Run all available methods for correcting spelling errors:
```php
Vietnamese::correct('THI tUổi KỈ Tị')
```
Result: `Thi tuổi Kỷ Tỵ`

---
Correct wrong accent placements:
```php
Vietnamese::correctAccent('Vịêt Nam')
```
Result: `Việt Nam`

---
Correct wrong cases between "i" and "y":
```php
Vietnamese::correctIY('Thi tuổi Kỉ Tị')
```
Result: `Thi tuổi Kỷ Tỵ`

---
Sorting words:

Sorting by values in a string with delimiter:
```php
Vietnamese::sort('Ă, A, Â, À, Á')
```
Result: `A, Á, À, Ă, Â`

Sorting by values in a simple array:
```php
Vietnamese::sort(['Ă', 'A', 'Â', 'À', 'Á'])
```
Result: `['A', 'Á', 'À', 'Ă', 'Â']`

Sorting a two-dimensional array by multiple keys in order:
```php
$array = [
	['name' => 'Cần Thơ', 'valid_date' => '2004-01-01'],
	['name' => 'Cà Mau', 'valid_date' => '1997-01-01'],
	['name' => 'Cần Thơ', 'valid_date' => '1992-01-01']
];
$array = Vietnamese::sort($array, ['name', 'valid_date']);
```
Result:
```php
array:3 [
	0 => array:2 [
		"name" => "Cà Mau"
		"valid_date" => "1997-01-01"
	]
	1 => array:2 [
		"name" => "Cần Thơ"
		"valid_date" => "1992-01-01"
	]
	2 => array:2 [
		"name" => "Cần Thơ"
		"valid_date" => "2004-01-01"
	]
]
```

---
Sorting people names:

Sorting by values in a string with delimiter:
```php
Vietnamese::sortPeopleName('Nguyễn Văn Đảnh, Nguyễn VĂN Đàn, nguYỄn Văn Đàng, NGUYỄN Văn Đang, nguyễn anh đang')
```
Result: `Nguyễn Anh Đang, Nguyễn Văn Đang, Nguyễn Văn Đàn, Nguyễn Văn Đàng, Nguyễn Văn Đảnh`

Sorting by values in a simple array:
```php
Vietnamese::sortPeopleName(['Nguyễn Văn Đảnh', 'Nguyễn VĂN Đàn', 'nguYỄn Văn Đàng', 'NGUYỄN Văn Đang', 'nguyễn anh đang'])
```
Result: `['Nguyễn Anh Đang', 'Nguyễn Văn Đang', 'Nguyễn Văn Đàn', 'Nguyễn Văn Đàng', 'Nguyễn Văn Đảnh']`

Sorting a two-dimensional array by multiple keys in order:
```php
$array = [
	['name' => 'Nguyễn Văn Đảnh', 'birth_date' => '1999-01-30'],
	['name' => 'Nguyễn VĂN Đàn', 'birth_date' => '1996-01-30'],
	['name' => 'Nguyễn Văn Đảnh', 'birth_date' => '1997-01-30'],
	['name' => 'NGUYỄN Văn Đang', 'birth_date' => '1995-01-30'],
	['name' => 'Nguyễn VĂN Đàn', 'birth_date' => '1994-01-30']
];
$array = Vietnamese::sortPeopleName($array, ['name', 'birth_date']);
```
Result:
```php
array:5 [
	0 => array:2 [
		"name" => "Nguyễn Văn Đang"
		"birth_date" => "1995-01-30"
	]
	1 => array:2 [
		"name" => "Nguyễn Văn Đàn"
		"birth_date" => "1994-01-30"
	]
	2 => array:2 [
		"name" => "Nguyễn Văn Đàn"
		"birth_date" => "1996-01-30"
	]
	3 => array:2 [
		"name" => "Nguyễn Văn Đảnh"
		"birth_date" => "1997-01-30"
	]
	4 => array:2 [
		"name" => "Nguyễn Văn Đảnh"
		"birth_date" => "1999-01-30"
	]
]
```

---
Check a character in the Vietnamese alphabet:
```php
Vietnamese::checkChar('w')
```
Result: `false`

---
Scan and detect incorrect words in Vietnamese:

```php
Vietnamese::scan('Xứ Wales thắng Nga, đứng nhất bảng B')
```
Result: `['Wales']`

Otherwise, get correct words:
```php
Vietnamese::scan('Xứ Wales thắng Nga, đứng nhất bảng B', false)
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
Vietnamese::speak(1452369)
```
Result: `một triệu bốn trăm năm mươi hai nghìn ba trăm sáu mươi chín`

## License
Copyright (c) NEDKA. All rights reserved.

Licensed under the MIT License.
