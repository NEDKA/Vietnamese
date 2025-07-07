<?php
/**
 * This file is part of the Vietnamese package.
 *
 * @version 1.0.12
 * @copyright (c) NEDKA. All rights reserved.
 * @license MIT License.
 */

namespace NEDKA\Vietnamese;

/**
 * HOW TO USE?
 *
 *	Format names:
 *		Vietnamese::formatName('ViỆt NaM')
 *		Việt Nam
 *	Remove all accents:
 *		Vietnamese::removeAccent('Việt Nam')
 *		Viet Nam
 *	Convert into NCR Decimal:
 *		Vietnamese::removeAccent('Việt Nam', 'ncr_decimal')
 *		Vi&#7879;t Nam
 *	Correct wrong accent placements:
 *		Vietnamese::fixAccent('Vịêt Nam')
 *		Việt Nam
 *	Correct wrong cases between "i" and "y":
 *		Vietnamese::fixIY('Thi tuổi Kỉ Tị')
 *		Thi tuổi Kỷ Tỵ
 *	Sorting words:
 *		Sorting by values in a simple array:
 *			Vietnamese::sortWord(['Ă', 'A', 'Â', 'À', 'Á'])
 *			['A', 'Á', 'À', 'Ă', 'Â']
 *		Sorting a two-dimensional array by multiple keys in order:
 *			------
 * 			$array = [
 *				['name' => 'Cần Thơ', 'valid_date' => '2004-01-01'],
 *				['name' => 'Cà Mau', 'valid_date' => '1997-01-01'],
 *				['name' => 'Cần Thơ', 'valid_date' => '1992-01-01']
 *			];
 *			$array = Vietnamese::sortWord($array, ['name', 'valid_date']);
 *			------
 *			Result:
 *			------
 *			array:3 [
 *				0 => array:2 [
 *					"name" => "Cà Mau"
 *					"valid_date" => "1997-01-01"
 *				]
 *				1 => array:2 [
 *					"name" => "Cần Thơ"
 *					"valid_date" => "1992-01-01"
 *				]
 *				2 => array:2 [
 *					"name" => "Cần Thơ"
 *					"valid_date" => "2004-01-01"
 *				]
 *			]
 *			------
 *	Sorting people names:
 *		Sorting by values in a simple array:
 *			Vietnamese::sortPeopleName(['Nguyễn Văn Đảnh', 'Nguyễn VĂN Đàn', 'nguYỄn Văn Đàng', 'NGUYỄN Văn Đang', 'nguyễn anh đang'])
 *			['Nguyễn Anh Đang', 'Nguyễn Văn Đang', 'Nguyễn Văn Đàn', 'Nguyễn Văn Đàng', 'Nguyễn Văn Đảnh']
 *		Sorting a two-dimensional array by multiple keys in order:
 *			------
 *			$array = [
 *				['name' => 'Nguyễn Văn Đảnh', 'birth_date' => '1999-01-30'],
 *				['name' => 'Nguyễn VĂN Đàn', 'birth_date' => '1996-01-30'],
 *				['name' => 'Nguyễn Văn Đảnh', 'birth_date' => '1997-01-30'],
 *				['name' => 'NGUYỄN Văn Đang', 'birth_date' => '1995-01-30'],
 *				['name' => 'Nguyễn VĂN Đàn', 'birth_date' => '1994-01-30']
 *			];
 *			$array = Vietnamese::sortPeopleName($array, ['name', 'birth_date'])
 *			------
 *			Result:
 *			------
 *			array:5 [
 *				0 => array:2 [
 *					"name" => "Nguyễn Văn Đang"
 *					"birth_date" => "1995-01-30"
 *				]
 *				1 => array:2 [
 *					"name" => "Nguyễn Văn Đàn"
 *					"birth_date" => "1994-01-30"
 *				]
 *				2 => array:2 [
 *					"name" => "Nguyễn Văn Đàn"
 *					"birth_date" => "1996-01-30"
 *				]
 *				3 => array:2 [
 *					"name" => "Nguyễn Văn Đảnh"
 *					"birth_date" => "1997-01-30"
 *				]
 *				4 => array:2 [
 *					"name" => "Nguyễn Văn Đảnh"
 *					"birth_date" => "1999-01-30"
 *				]
 *			]
 *			------
 *	Check a character in the Vietnamese alphabet:
 *		Vietnamese::checkChar('w')
 *		false
 *	Scan and detect incorrect words in Vietnamese:
 *		Find incorrect words:
 *			Vietnamese::scanWords('Xứ Wales thắng Nga, đứng nhất bảng B')
 *			['Wales']
 *		Otherwise, get correct words:
 *			Vietnamese::scanWords('Xứ Wales thắng Nga, đứng nhất bảng B', false)
 *			['Xứ', 'thắng', 'Nga', 'đứng', 'nhất', 'bảng', 'B']
 *	Print the way to speak a Vietnamese text string:
 *		Vietnamese::speak('Việt Nam')
 *		i ê tờ iêt, vờ iêt viêt nặng /việt/; a mờ am, nờ am /nam/; /việt nam/
 *	Convert number to text:
 *		Vietnamese::numberToText(1452369)
 *		một triệu bốn trăm năm mươi hai nghìn ba trăm sáu mươi chín
 */

/**
 * ALPHABET ORDER
 *
 *	Aa Ăă Ââ Bb Cc Dd Đđ Ee Êê Gg Hh Ii Kk Ll Mm Nn Oo Ôô Ơơ Pp Qq Rr Ss Tt Uu Ưư Vv Xx Yy
 *
 *	Includes 22 alphabets from English (without Ff/Jj/Ww/Zz), plus 6 alphabets with diacritics:
 *		[˘] (breve = dấu ngân)		-> [Ăă]
 *		[ˆ] (circumflex = dấu mũ)	-> [Ââ] [Êê] [Ôô]
 *		[ ̛] (horn = dấu móc)		-> [Ơơ] [Ưư]
 *		[-] (crossed D = dấu gạch)	-> [Đđ]
 *	>> Total: 29 alphabets.
 *
 * ALPHABET+ ORDER
 *
 *	Aa Ăă Ââ Bb Cc Dd Đđ Ee Êê Ff Gg Hh Ii Jj Kk Ll Mm Nn Oo Ôô Ơơ Pp Qq Rr Ss Tt Uu Ưư Vv Ww Xx Yy Zz
 *
 *	Includes 29 original alphabets from Vietnamese, plus Ff/Jj/Ww/Zz from English alphabet.
 *	>> Total: 33 alphabets.
 *
 *	ACCENT ORDER
 *
 *	[ ] (flat = thanh bằng) or (non-accent = không dấu)	-> [Aa]
 *	[`] (grave = dấu huyền)								-> [Àà]
 *	[ ̉] (hook above = dấu hỏi)							-> [Ảả]
 *	[˜] (tilde = dấu ngã)								-> [Ãã]
 *	[´] (acute = dấu sắc)								-> [Áá]
 *	[.] (dot below = dấu nặng)							-> [Ạạ]
 *
 *	Accents applied only to vowels.
 *	>> Total: 6 accents.
 *
 * VOWELS (NGUYÊN ÂM)
 *
 *	Aa Ăă Ââ Ee Êê Ii Oo Ôô Ơơ Uu Ưư Yy
 *
 *	>> Total: 12 vowels.
 *
 * CONSONANTS (PHỤ ÂM)
 *
 *	Original Consonants from Alphabet: 16
 *		Bb Cc Dd Đđ Gg Hh Kk Ll Mm Nn Pp Rr Ss Tt Vv Xx
 *	Compounded Consonants: 11
 *		[Cc] + h		-> [Ch/ch]
 *		[Gg] + h		-> [Gh/gh]
 *		[Gg] + i		-> [Gi/gi]
 *		[Kk] + h		-> [Kh/kh]
 *		[Nn] + g		-> [Ng/ng]
 *		[Nn] + g + h	-> [Ngh/ngh]
 *		[Nn] + h		-> [Nh/nh]
 *		[Pp] + h		-> [Ph/ph]
 *		[Qq] + u		-> [Qu/qu]
 *		[Tt] + h		-> [Th/th]
 *		[Tt] + r		-> [Tr/tr]
 *	Consonants always on the first of a rhythm.
 *	>> Total: 27 consonants.
 *
 * SYLLABLES (VẦN)
 *
 *	Syllable is a combination of vowels [begin] and consonants [end], by 8 creation ways:
 *		(1) Only Vowel (an alphabet): 12
 *			a ă â e ê i o ô ơ u ư y
 *		(2) Vowel + Original Consonant (2 characters): 52
 *			ac am an ap at
 *			ăc ăm ăn ăp ăt
 *			âc âm ân âp ât
 *			ec em en ep et
 *			êm ên êp êt
 *			ic im in ip it
 *			oc om on op ot
 *			ôc ôm ôn ôp ôt
 *			ơm ơn ơp ơt
 *			uc um un up ut
 *			ưc ưm ưt
 *			yt
 *		(3) Double Vowels (2 characters): 23
 *			ai ao au ay
 *			âu ây
 *			eo
 *			êu
 *			ia iu
 *			oa oe oi
 *			ôi
 *			ơi
 *			ua uê ui uơ uy
 *			ưa ưi ưu
 *		(4) Vowel + 2-char Compounded Consonant (3 characters): 13
 *			ach ang anh
 *			ăng
 *			âng
 *			eng
 *			êch ênh
 *			ich
 *			ông
 *			ung
 *			ưng
 *			ynh
 *		(5) Double Vowels + Original Consonant (3 characters): 31
 *			iêc iêm iên iêp iêt inh
 *			oac oam oan oat oăc oăm oăn oăt oen oet ong
 *			uân uât uôc uôm uôn uôt uyt
 *			ươc ươm ươn ươp ươt
 *			yên yêt
 *		(6) Triple Vowels (3 characters): 11
 *			iêu
 *			oai oay oeo
 *			uây uôi uya uyu
 *			ươi ươu
 *			yêu
 *		(7) Double Vowels + 2-char Compounded Consonant (4 characters): 13
 *			iêng
 *			oach oang oanh oăng oong
 *			uâng uêch uênh uông uych uynh
 *			ương
 *		(8) Triple Vowels + Original Consonant (4 characters): 2
 *			uyên uyêt
 *	>> Total: 157 original syllables.
 *
 *	Each syllable comes with/without an accent. Adding an accent, we have 5 variant syllables from the original one:
 *		va -> và, vả, vã, vá, vạ
 *	>> Total: 157 x 6 = 942 syllables.
 *
 * RHYTHMS & SINGLE WORDS (ÂM TIẾT & TỪ ĐƠN)
 *
 *	Each single word in Vietnamese, which is also a rhythm, is created by 4 ways:
 *		(1) Syllable without Accents
 *		(2) Syllable + Accents
 *		(3) Consonant + Syllable without Accents
 *		(4) Consonant + (Syllable + Accents)
 *	>> Total: ? rhythms (Uhm.... counting :-/)
 */

class Vietnamese
{
	private static array $data = [
		/**
		 * List of all letters in the Vietnamese alphabet.
		 *
		 * Indexes:
		 *	0 -> Upper case.
		 *	1 -> The speaking way.
		 */
		'letters' => [
			'a' => ['A', 'a'],
			'ă' => ['Ă', 'á'],
			'â' => ['Â', 'ớ'],
			'b' => ['B', 'bờ'],
			'c' => ['C', 'cờ'],
			'd' => ['D', 'dờ'],
			'đ' => ['Đ', 'đờ'],
			'e' => ['E', 'e'],
			'ê' => ['Ê', 'ê'],
			'g' => ['G', 'gờ'],
			'h' => ['H', 'hờ'],
			'i' => ['I', 'i'],
			'k' => ['K', 'k'],
			'l' => ['L', 'lờ'],
			'm' => ['M', 'mờ'],
			'n' => ['N', 'nờ'],
			'o' => ['O', 'o'],
			'ô' => ['Ô', 'ô'],
			'ơ' => ['Ơ', 'ơ'],
			'p' => ['P', 'bờ'],
			'q' => ['Q', 'quờ'],
			'r' => ['R', 'rờ'],
			's' => ['S', 'sờ'],
			't' => ['T', 'tờ'],
			'u' => ['U', 'u'],
			'ư' => ['Ư', 'ư'],
			'v' => ['V', 'vờ'],
			'x' => ['X', 'xờ'],
			'y' => ['Y', 'y']
		],

		/**
		 * List of all accent names
		 */
		'accent_names' => [
			1 => 'huyền',
			2 => 'hỏi',
			3 => 'ngã',
			4 => 'sắc',
			5 => 'nặng'
		],

		/**
		 * List of all Vietnamese letters with accents.
		 *
		 * Indexes:
		 *	0 -> Upper case.
		 *	1 -> Lower case (English alphabet).
		 *	2 -> Upper case (English alphabet).
		 *	3 -> Lower case (Vietnamese alphabet).
		 *	4 -> Upper case (Vietnamese alphabet).
		 *	5 -> Lower case in NCR Decimal.
		 *	6 -> Upper case in NCR Decimal.
		 */
		'accent_letters' => [
			'à' => ['À', 'a', 'A', 'a', 'A', '&#224;', '&#192;'],
			'ả' => ['Ả', 'a', 'A', 'a', 'A', '&#7843;', '&#7842;'],
			'ã' => ['Ã', 'a', 'A', 'a', 'A', '&#227;', '&#195;'],
			'á' => ['Á', 'a', 'A', 'a', 'A', '&#225;', '&#193;'],
			'ạ' => ['Ạ', 'a', 'A', 'a', 'A', '&#7841;', '&#7840;'],
			'ă' => ['Ă', 'a', 'A', 'ă', 'Ă', '&#259;', '&#258;'],
			'ằ' => ['Ằ', 'a', 'A', 'ă', 'Ă', '&#7857;', '&#7856;'],
			'ẳ' => ['Ẳ', 'a', 'A', 'ă', 'Ă', '&#7859;', '&#7858;'],
			'ẵ' => ['Ẵ', 'a', 'A', 'ă', 'Ă', '&#7861;', '&#7860;'],
			'ắ' => ['Ắ', 'a', 'A', 'ă', 'Ă', '&#7855;', '&#7854;'],
			'ặ' => ['Ặ', 'a', 'A', 'ă', 'Ă', '&#7863;', '&#7862;'],
			'â' => ['Â', 'a', 'A', 'â', 'Â', '&#226;', '&#194;'],
			'ầ' => ['Ầ', 'a', 'A', 'â', 'Â', '&#7847;', '&#7846;'],
			'ẩ' => ['Ẩ', 'a', 'A', 'â', 'Â', '&#7849;', '&#7848;'],
			'ẫ' => ['Ẫ', 'a', 'A', 'â', 'Â', '&#7851;', '&#7850;'],
			'ấ' => ['Ấ', 'a', 'A', 'â', 'Â', '&#7845;', '&#7844;'],
			'ậ' => ['Ậ', 'a', 'A', 'â', 'Â', '&#7853;', '&#7852;'],
			'đ' => ['Đ', 'd', 'D', 'đ', 'Đ', '&#273;', '&#272;'],
			'è' => ['È', 'e', 'E', 'e', 'E', '&#232;', '&#200;'],
			'ẻ' => ['Ẻ', 'e', 'E', 'e', 'E', '&#7867;', '&#7866;'],
			'ẽ' => ['Ẽ', 'e', 'E', 'e', 'E', '&#7869;', '&#7868;'],
			'é' => ['É', 'e', 'E', 'e', 'E', '&#233;', '&#201;'],
			'ẹ' => ['Ẹ', 'e', 'E', 'e', 'E', '&#7865;', '&#7864;'],
			'ê' => ['Ê', 'e', 'E', 'ê', 'Ê', '&#234;', '&#202;'],
			'ề' => ['Ề', 'e', 'E', 'ê', 'Ê', '&#7873;', '&#7872;'],
			'ể' => ['Ể', 'e', 'E', 'ê', 'Ê', '&#7875;', '&#7874;'],
			'ễ' => ['Ễ', 'e', 'E', 'ê', 'Ê', '&#7877;', '&#7876;'],
			'ế' => ['Ế', 'e', 'E', 'ê', 'Ê', '&#7871;', '&#7870;'],
			'ệ' => ['Ệ', 'e', 'E', 'ê', 'Ê', '&#7879;', '&#7878;'],
			'ì' => ['Ì', 'i', 'I', 'i', 'I', '&#236;', '&#204;'],
			'ỉ' => ['Ỉ', 'i', 'I', 'i', 'I', '&#7881;', '&#7880;'],
			'ĩ' => ['Ĩ', 'i', 'I', 'i', 'I', '&#297;', '&#296;'],
			'í' => ['Í', 'i', 'I', 'i', 'I', '&#237;', '&#205;'],
			'ị' => ['Ị', 'i', 'I', 'i', 'I', '&#7883;', '&#7882;'],
			'ò' => ['Ò', 'o', 'O', 'o', 'O', '&#242;', '&#210;'],
			'ỏ' => ['Ỏ', 'o', 'O', 'o', 'O', '&#7887;', '&#7886;'],
			'õ' => ['Õ', 'o', 'O', 'o', 'O', '&#245;', '&#213;'],
			'ó' => ['Ó', 'o', 'O', 'o', 'O', '&#243;', '&#211;'],
			'ọ' => ['Ọ', 'o', 'O', 'o', 'O', '&#7885;', '&#7884;'],
			'ô' => ['Ô', 'o', 'O', 'ô', 'Ô', '&#244;', '&#212;'],
			'ồ' => ['Ồ', 'o', 'O', 'ô', 'Ô', '&#7891;', '&#7890;'],
			'ổ' => ['Ổ', 'o', 'O', 'ô', 'Ô', '&#7893;', '&#7892;'],
			'ỗ' => ['Ỗ', 'o', 'O', 'ô', 'Ô', '&#7895;', '&#7894;'],
			'ố' => ['Ố', 'o', 'O', 'ô', 'Ô', '&#7889;', '&#7888;'],
			'ộ' => ['Ộ', 'o', 'O', 'ô', 'Ô', '&#7897;', '&#7896;'],
			'ơ' => ['Ơ', 'o', 'O', 'ơ', 'Ơ', '&#417;', '&#416;'],
			'ờ' => ['Ờ', 'o', 'O', 'ơ', 'Ơ', '&#7901;', '&#7900;'],
			'ở' => ['Ở', 'o', 'O', 'ơ', 'Ơ', '&#7903;', '&#7902;'],
			'ỡ' => ['Ỡ', 'o', 'O', 'ơ', 'Ơ', '&#7905;', '&#7904;'],
			'ớ' => ['Ớ', 'o', 'O', 'ơ', 'Ơ', '&#7899;', '&#7898;'],
			'ợ' => ['Ợ', 'o', 'O', 'ơ', 'Ơ', '&#7907;', '&#7906;'],
			'ù' => ['Ù', 'u', 'U', 'u', 'U', '&#249;', '&#217;'],
			'ủ' => ['Ủ', 'u', 'U', 'u', 'U', '&#7911;', '&#7910;'],
			'ũ' => ['Ũ', 'u', 'U', 'u', 'U', '&#361;', '&#360;'],
			'ú' => ['Ú', 'u', 'U', 'u', 'U', '&#250;', '&#218;'],
			'ụ' => ['Ụ', 'u', 'U', 'u', 'U', '&#7909;', '&#7908;'],
			'ư' => ['Ư', 'u', 'U', 'ư', 'Ư', '&#432;', '&#431;'],
			'ừ' => ['Ừ', 'u', 'U', 'ư', 'Ư', '&#7915;', '&#7914;'],
			'ử' => ['Ử', 'u', 'U', 'ư', 'Ư', '&#7917;', '&#7916;'],
			'ữ' => ['Ữ', 'u', 'U', 'ư', 'Ư', '&#7919;', '&#7918;'],
			'ứ' => ['Ứ', 'u', 'U', 'ư', 'Ư', '&#7913;', '&#7912;'],
			'ự' => ['Ự', 'u', 'U', 'ư', 'Ư', '&#7921;', '&#7920;'],
			'ỳ' => ['Ỳ', 'y', 'Y', 'y', 'Y', '&#7923;', '&#7922;'],
			'ỷ' => ['Ỷ', 'y', 'Y', 'y', 'Y', '&#7927;', '&#7926;'],
			'ỹ' => ['Ỹ', 'y', 'Y', 'y', 'Y', '&#7929;', '&#7928;'],
			'ý' => ['Ý', 'y', 'Y', 'y', 'Y', '&#253;', '&#221;'],
			'ỵ' => ['Ỵ', 'y', 'Y', 'y', 'Y', '&#7925;', '&#7924;']
		],

		/**
		 * List of all consonants.
		 *
		 * Indexes:
		 *	0 -> Upper case.
		 *	1 -> The speaking way.
		 */
		'consonants' => [
			'b'		=> ['B', 'bờ'],
			'c'		=> ['C', 'cờ'],
			'ch'	=> ['CH', 'chờ'],
			'd'		=> ['D', 'dờ'],
			'đ'		=> ['Đ', 'đờ'],
			'g'		=> ['G', 'gờ'],
			'gh'	=> ['GH', 'gờ'],
			'gi'	=> ['GI', 'giờ'],
			'h'		=> ['H', 'hờ'],
			'k'		=> ['K', 'k'],
			'kh'	=> ['KH', 'khờ'],
			'l'		=> ['L', 'lờ'],
			'm'		=> ['M', 'mờ'],
			'n'		=> ['N', 'nờ'],
			'ng'	=> ['NG', 'ngờ'],
			'ngh'	=> ['NGH', 'ngờ'],
			'nh'	=> ['NH', 'nhờ'],
			'p'		=> ['P', 'bờ'],
			'ph'	=> ['PH', 'phờ'],
			'qu'	=> ['QU', 'quờ'],
			'r'		=> ['R', 'rờ'],
			's'		=> ['S', 'sờ'],
			't'		=> ['T', 'tờ'],
			'th'	=> ['TH', 'thờ'],
			'tr'	=> ['TR', 'trờ'],
			'v'		=> ['V', 'vờ'],
			'x'		=> ['X', 'xờ']
		],
		// All consonants were sorted by length DESC
		'consonants_desc' => ['ngh', 'ch', 'gh', 'gi', 'kh', 'ng', 'nh', 'ph', 'qu', 'th', 'tr', 'b', 'c', 'd', 'đ', 'g', 'h', 'k', 'l', 'm', 'n', 'p', 'r', 's', 't', 'v', 'x'],

		/**
		 * List of all ending consonants.
		 */
		'end_consonants' => ['c', 'ch', 'm', 'n', 'ng', 'nh', 'p', 't'],
		// All ending consonants were sorted by length DESC
		'end_consonants_desc' => ['ch', 'ng', 'nh', 'c', 'm', 'n', 'p', 't'],

		/**
		 * List of all vowels with accents.
		 *
		 * Indexes:
		 *	[alphabet]
		 *		1 (grave)		=> [lower_case, UPPER_CASE]
		 *		2 (hook above)	=> [lower_case, UPPER_CASE]
		 *		3 (tilde)		=> [lower_case, UPPER_CASE]
		 *		4 (acute)		=> [lower_case, UPPER_CASE]
		 *		5 (dot below)	=> [lower_case, UPPER_CASE]
		 */
		'accent_to_vowels' => [
			'a' => [
				1 => ['à', 'À'],
				2 => ['ả', 'Ả'],
				3 => ['ã', 'Ã'],
				4 => ['á', 'Á'],
				5 => ['ạ', 'Ạ']
			],
			'ă' => [
				1 => ['ằ', 'Ằ'],
				2 => ['ẳ', 'Ẳ'],
				3 => ['ẵ', 'Ẵ'],
				4 => ['ắ', 'Ắ'],
				5 => ['ặ', 'Ặ']
			],
			'â' => [
				1 => ['ầ', 'Ầ'],
				2 => ['ẩ', 'Ẩ'],
				3 => ['ẫ', 'Ẫ'],
				4 => ['ấ', 'Ấ'],
				5 => ['ậ', 'Ậ']
			],
			'e' => [
				1 => ['è', 'È'],
				2 => ['ẻ', 'Ẻ'],
				3 => ['ẽ', 'Ẽ'],
				4 => ['é', 'É'],
				5 => ['ẹ', 'Ẹ']
			],
			'ê' => [
				1 => ['ề', 'Ề'],
				2 => ['ể', 'Ể'],
				3 => ['ễ', 'Ễ'],
				4 => ['ế', 'Ế'],
				5 => ['ệ', 'Ệ']
			],
			'i' => [
				1 => ['ì', 'Ì'],
				2 => ['ỉ', 'Ỉ'],
				3 => ['ĩ', 'Ĩ'],
				4 => ['í', 'Í'],
				5 => ['ị', 'Ị']
			],
			'o' => [
				1 => ['ò', 'Ò'],
				2 => ['ỏ', 'Ỏ'],
				3 => ['õ', 'Õ'],
				4 => ['ó', 'Ó'],
				5 => ['ọ', 'Ọ']
			],
			'ô' => [
				1 => ['ồ', 'Ồ'],
				2 => ['ổ', 'Ổ'],
				3 => ['ỗ', 'Ỗ'],
				4 => ['ố', 'Ố'],
				5 => ['ộ', 'Ộ']
			],
			'ơ' => [
				1 => ['ờ', 'Ờ'],
				2 => ['ở', 'Ở'],
				3 => ['ỡ', 'Ỡ'],
				4 => ['ớ', 'Ớ'],
				5 => ['ợ', 'Ợ']
			],
			'u' => [
				1 => ['ù', 'Ù'],
				2 => ['ủ', 'Ủ'],
				3 => ['ũ', 'Ũ'],
				4 => ['ú', 'Ú'],
				5 => ['ụ', 'Ụ']
			],
			'ư' => [
				1 => ['ừ', 'Ừ'],
				2 => ['ử', 'Ử'],
				3 => ['ữ', 'Ữ'],
				4 => ['ứ', 'Ứ'],
				5 => ['ự', 'Ự']
			],
			'y' => [
				1 => ['ỳ', 'Ỳ'],
				2 => ['ỷ', 'Ỷ'],
				3 => ['ỹ', 'Ỹ'],
				4 => ['ý', 'Ý'],
				5 => ['ỵ', 'Ỵ']
			]
		],

		/**
		 * List of correct words.
		 * This list is generated by the file `.dev/generate_words.php`.
		 *
		 * @version 0.11.0
		 * @todo We will update this list as soon with a Vietnamese dictionary.
		 */
		'words' => ['a', 'ba', 'ca', 'cha', 'da', 'đa', 'ga', 'gia', 'ha', 'kha', 'la', 'ma', 'na', 'nga', 'nha', 'pa', 'pha', 'qua', 'ra', 'sa', 'ta', 'tha', 'tra', 'va', 'xa', 'à', 'bà', 'cà', 'chà', 'dà', 'đà', 'gà', 'già', 'hà', 'khà', 'là', 'mà', 'nà', 'ngà', 'nhà', 'phà', 'quà', 'rà', 'sà', 'tà', 'thà', 'trà', 'và', 'xà', 'ả', 'bả', 'cả', 'chả', 'dả', 'đả', 'gả', 'giả', 'hả', 'khả', 'lả', 'mả', 'nả', 'ngả', 'nhả', 'phả', 'quả', 'rả', 'sả', 'tả', 'thả', 'trả', 'vả', 'xả', 'ã', 'bã', 'cã', 'chã', 'dã', 'đã', 'gã', 'giã', 'lã', 'mã', 'nã', 'ngã', 'nhã', 'quã', 'rã', 'sã', 'tã', 'thã', 'trã', 'vã', 'xã', 'á', 'bá', 'cá', 'chá', 'dá', 'đá', 'gá', 'giá', 'há', 'khá', 'lá', 'má', 'ná', 'ngá', 'nhá', 'phá', 'quá', 'rá', 'sá', 'tá', 'thá', 'trá', 'vá', 'xá', 'ạ', 'bạ', 'cạ', 'chạ', 'dạ', 'đạ', 'gạ', 'giạ', 'hạ', 'lạ', 'mạ', 'nạ', 'ngạ', 'nhạ', 'quạ', 'rạ', 'sạ', 'tạ', 'thạ', 'trạ', 'vạ', 'xạ', 'ă', 'că', 'chă', 'dă', 'đă', 'mă', 'nă', 'ngă', 'nhă', 'quă', 'ră', 'să', 'tă', 'thă', 'tră', 'vă', 'xă', 'ằ', 'cằ', 'chằ', 'dằ', 'đằ', 'mằ', 'nằ', 'ngằ', 'nhằ', 'quằ', 'rằ', 'sằ', 'tằ', 'thằ', 'trằ', 'vằ', 'xằ', 'ẳ', 'cẳ', 'chẳ', 'dẳ', 'đẳ', 'mẳ', 'nẳ', 'ngẳ', 'nhẳ', 'quẳ', 'rẳ', 'sẳ', 'tẳ', 'thẳ', 'trẳ', 'vẳ', 'xẳ', 'ẵ', 'cẵ', 'chẵ', 'dẵ', 'đẵ', 'mẵ', 'nẵ', 'ngẵ', 'nhẵ', 'quẵ', 'rẵ', 'sẵ', 'tẵ', 'thẵ', 'trẵ', 'vẵ', 'xẵ', 'ắ', 'cắ', 'chắ', 'dắ', 'đắ', 'mắ', 'nắ', 'ngắ', 'nhắ', 'quắ', 'rắ', 'sắ', 'tắ', 'thắ', 'trắ', 'vắ', 'xắ', 'ặ', 'cặ', 'chặ', 'dặ', 'đặ', 'mặ', 'nặ', 'ngặ', 'nhặ', 'quặ', 'rặ', 'sặ', 'tặ', 'thặ', 'trặ', 'vặ', 'xặ', 'â', 'câ', 'châ', 'dâ', 'đâ', 'mâ', 'nâ', 'ngâ', 'nhâ', 'quâ', 'râ', 'sâ', 'tâ', 'thâ', 'trâ', 'vâ', 'xâ', 'ầ', 'cầ', 'chầ', 'dầ', 'đầ', 'mầ', 'nầ', 'ngầ', 'nhầ', 'quầ', 'rầ', 'sầ', 'tầ', 'thầ', 'trầ', 'vầ', 'xầ', 'ẩ', 'cẩ', 'chẩ', 'dẩ', 'đẩ', 'mẩ', 'nẩ', 'ngẩ', 'nhẩ', 'quẩ', 'rẩ', 'sẩ', 'tẩ', 'thẩ', 'trẩ', 'vẩ', 'xẩ', 'ẫ', 'cẫ', 'chẫ', 'dẫ', 'đẫ', 'mẫ', 'nẫ', 'ngẫ', 'nhẫ', 'quẫ', 'rẫ', 'sẫ', 'tẫ', 'thẫ', 'trẫ', 'vẫ', 'xẫ', 'ấ', 'cấ', 'chấ', 'dấ', 'đấ', 'mấ', 'nấ', 'ngấ', 'nhấ', 'quấ', 'rấ', 'sấ', 'tấ', 'thấ', 'trấ', 'vấ', 'xấ', 'ậ', 'cậ', 'chậ', 'dậ', 'đậ', 'mậ', 'nậ', 'ngậ', 'nhậ', 'quậ', 'rậ', 'sậ', 'tậ', 'thậ', 'trậ', 'vậ', 'xậ', 'e', 'be', 'ce', 'che', 'de', 'đe', 'ghe', 'gie', 'he', 'ke', 'khe', 'le', 'me', 'ne', 'nge', 'nghe', 'nhe', 'phe', 'que', 're', 'se', 'te', 'the', 'tre', 've', 'xe', 'è', 'bè', 'cè', 'chè', 'dè', 'đè', 'ghè', 'hè', 'kè', 'khè', 'lè', 'mè', 'nè', 'ngè', 'nghè', 'nhè', 'phè', 'què', 'rè', 'sè', 'tè', 'thè', 'trè', 'vè', 'xè', 'ẻ', 'bẻ', 'cẻ', 'chẻ', 'dẻ', 'đẻ', 'ghẻ', 'giẻ', 'kẻ', 'khẻ', 'lẻ', 'mẻ', 'nẻ', 'ngẻ', 'nhẻ', 'quẻ', 'rẻ', 'sẻ', 'tẻ', 'thẻ', 'trẻ', 'vẻ', 'xẻ', 'ẽ', 'bẽ', 'cẽ', 'chẽ', 'dẽ', 'đẽ', 'kẽ', 'khẽ', 'lẽ', 'mẽ', 'nẽ', 'ngẽ', 'nhẽ', 'quẽ', 'rẽ', 'sẽ', 'tẽ', 'thẽ', 'trẽ', 'vẽ', 'xẽ', 'é', 'bé', 'cé', 'ché', 'dé', 'đé', 'ghé', 'gié', 'hé', 'ké', 'khé', 'lé', 'mé', 'né', 'ngé', 'nghé', 'nhé', 'qué', 'ré', 'sé', 'té', 'thé', 'tré', 'vé', 'xé', 'ẹ', 'bẹ', 'cẹ', 'chẹ', 'dẹ', 'đẹ', 'ghẹ', 'hẹ', 'kẹ', 'lẹ', 'mẹ', 'nẹ', 'ngẹ', 'nhẹ', 'quẹ', 'rẹ', 'sẹ', 'tẹ', 'thẹ', 'trẹ', 'vẹ', 'xẹ', 'ê', 'bê', 'cê', 'chê', 'dê', 'đê', 'ghê', 'hê', 'kê', 'khê', 'lê', 'mê', 'nê', 'ngê', 'nghê', 'nhê', 'phê', 'quê', 'rê', 'sê', 'tê', 'thê', 'trê', 'vê', 'xê', 'ề', 'bề', 'cề', 'chề', 'dề', 'đề', 'ghề', 'hề', 'kề', 'khề', 'lề', 'mề', 'nề', 'ngề', 'nghề', 'nhề', 'phề', 'quề', 'rề', 'sề', 'tề', 'thề', 'trề', 'về', 'xề', 'ể', 'bể', 'cể', 'chể', 'dể', 'để', 'hể', 'kể', 'mể', 'nể', 'ngể', 'nghể', 'nhể', 'quể', 'rể', 'sể', 'tể', 'thể', 'trể', 'vể', 'xể', 'ễ', 'bễ', 'cễ', 'chễ', 'dễ', 'đễ', 'hễ', 'lễ', 'mễ', 'nễ', 'ngễ', 'nhễ', 'quễ', 'rễ', 'sễ', 'tễ', 'thễ', 'trễ', 'vễ', 'xễ', 'ế', 'bế', 'cế', 'chế', 'dế', 'đế', 'ghế', 'kế', 'khế', 'mế', 'nế', 'ngế', 'nhế', 'phế', 'quế', 'rế', 'sế', 'tế', 'thế', 'trế', 'vế', 'xế', 'ệ', 'bệ', 'cệ', 'chệ', 'dệ', 'đệ', 'ghệ', 'hệ', 'kệ', 'khệ', 'lệ', 'mệ', 'nệ', 'ngệ', 'nghệ', 'nhệ', 'phệ', 'quệ', 'rệ', 'sệ', 'tệ', 'thệ', 'trệ', 'vệ', 'xệ', 'i', 'bi', 'ci', 'chi', 'di', 'đi', 'ghi', 'hi', 'ki', 'khi', 'li', 'mi', 'ni', 'ngi', 'nghi', 'nhi', 'phi', 'qui', 'ri', 'si', 'ti', 'thi', 'tri', 'vi', 'xi', 'ì', 'bì', 'cì', 'chì', 'dì', 'đì', 'gì', 'ghì', 'hì', 'kì', 'khì', 'lì', 'mì', 'nì', 'ngì', 'nghì', 'nhì', 'phì', 'quì', 'rì', 'sì', 'tì', 'thì', 'trì', 'vì', 'xì', 'ỉ', 'bỉ', 'cỉ', 'chỉ', 'dỉ', 'đỉ', 'gỉ', 'hỉ', 'khỉ', 'mỉ', 'nỉ', 'ngỉ', 'nghỉ', 'nhỉ', 'phỉ', 'quỉ', 'rỉ', 'sỉ', 'tỉ', 'thỉ', 'trỉ', 'vỉ', 'xỉ', 'ĩ', 'bĩ', 'cĩ', 'chĩ', 'dĩ', 'đĩ', 'kĩ', 'lĩ', 'mĩ', 'nĩ', 'ngĩ', 'nghĩ', 'nhĩ', 'quĩ', 'rĩ', 'sĩ', 'tĩ', 'thĩ', 'trĩ', 'vĩ', 'xĩ', 'í', 'bí', 'cí', 'chí', 'dí', 'đí', 'gí', 'hí', 'kí', 'khí', 'lí', 'mí', 'ní', 'ngí', 'nghí', 'nhí', 'phí', 'quí', 'rí', 'sí', 'tí', 'thí', 'trí', 'ví', 'xí', 'ị', 'bị', 'cị', 'chị', 'dị', 'đị', 'kị', 'lị', 'mị', 'nị', 'ngị', 'nghị', 'nhị', 'phị', 'quị', 'rị', 'sị', 'tị', 'thị', 'trị', 'vị', 'xị', 'o', 'bo', 'co', 'cho', 'do', 'đo', 'go', 'ho', 'kho', 'lo', 'mo', 'no', 'ngo', 'nho', 'pho', 'quo', 'ro', 'so', 'to', 'tho', 'tro', 'vo', 'xo', 'ò', 'bò', 'cò', 'chò', 'dò', 'đò', 'gò', 'giò', 'hò', 'khò', 'lò', 'mò', 'nò', 'ngò', 'nhò', 'phò', 'quò', 'rò', 'sò', 'tò', 'thò', 'trò', 'vò', 'xò', 'ỏ', 'bỏ', 'cỏ', 'chỏ', 'dỏ', 'đỏ', 'giỏ', 'mỏ', 'nỏ', 'ngỏ', 'nhỏ', 'quỏ', 'rỏ', 'sỏ', 'tỏ', 'thỏ', 'trỏ', 'vỏ', 'xỏ', 'õ', 'bõ', 'cõ', 'chõ', 'dõ', 'đõ', 'gõ', 'lõ', 'mõ', 'nõ', 'ngõ', 'nhõ', 'quõ', 'rõ', 'sõ', 'tõ', 'thõ', 'trõ', 'võ', 'xõ', 'ó', 'bó', 'có', 'chó', 'dó', 'đó', 'gió', 'hó', 'khó', 'ló', 'mó', 'nó', 'ngó', 'nhó', 'phó', 'quó', 'ró', 'só', 'tó', 'thó', 'tró', 'vó', 'xó', 'ọ', 'bọ', 'cọ', 'chọ', 'dọ', 'đọ', 'gọ', 'giọ', 'họ', 'lọ', 'mọ', 'nọ', 'ngọ', 'nhọ', 'quọ', 'rọ', 'sọ', 'tọ', 'thọ', 'trọ', 'vọ', 'xọ', 'ô', 'bô', 'cô', 'chô', 'dô', 'đô', 'gô', 'hô', 'khô', 'lô', 'mô', 'nô', 'ngô', 'nhô', 'phô', 'quô', 'rô', 'sô', 'tô', 'thô', 'trô', 'vô', 'xô', 'ồ', 'bồ', 'cồ', 'chồ', 'dồ', 'đồ', 'gồ', 'hồ', 'lồ', 'mồ', 'nồ', 'ngồ', 'nhồ', 'quồ', 'rồ', 'sồ', 'tồ', 'thồ', 'trồ', 'vồ', 'xồ', 'ổ', 'bổ', 'cổ', 'chổ', 'dổ', 'đổ', 'gổ', 'hổ', 'khổ', 'lổ', 'mổ', 'nổ', 'ngổ', 'nhổ', 'phổ', 'quổ', 'rổ', 'sổ', 'tổ', 'thổ', 'trổ', 'vổ', 'xổ', 'ỗ', 'bỗ', 'cỗ', 'chỗ', 'dỗ', 'đỗ', 'gỗ', 'giỗ', 'hỗ', 'lỗ', 'mỗ', 'nỗ', 'ngỗ', 'nhỗ', 'quỗ', 'rỗ', 'sỗ', 'tỗ', 'thỗ', 'trỗ', 'vỗ', 'xỗ', 'ố', 'bố', 'cố', 'chố', 'dố', 'đố', 'hố', 'khố', 'lố', 'mố', 'nố', 'ngố', 'nhố', 'phố', 'quố', 'rố', 'số', 'tố', 'thố', 'trố', 'vố', 'xố', 'ộ', 'bộ', 'cộ', 'chộ', 'dộ', 'độ', 'gộ', 'hộ', 'lộ', 'mộ', 'nộ', 'ngộ', 'nhộ', 'quộ', 'rộ', 'sộ', 'tộ', 'thộ', 'trộ', 'vộ', 'xộ', 'ơ', 'bơ', 'cơ', 'chơ', 'dơ', 'đơ', 'gơ', 'giơ', 'hơ', 'khơ', 'lơ', 'mơ', 'nơ', 'ngơ', 'nhơ', 'phơ', 'quơ', 'rơ', 'sơ', 'tơ', 'thơ', 'trơ', 'vơ', 'xơ', 'ờ', 'bờ', 'cờ', 'chờ', 'dờ', 'đờ', 'gờ', 'giờ', 'hờ', 'khờ', 'lờ', 'mờ', 'nờ', 'ngờ', 'nhờ', 'phờ', 'quờ', 'rờ', 'sờ', 'tờ', 'thờ', 'trờ', 'vờ', 'xờ', 'ở', 'bở', 'cở', 'chở', 'dở', 'đở', 'gở', 'giở', 'hở', 'lở', 'mở', 'nở', 'ngở', 'nhở', 'phở', 'quở', 'rở', 'sở', 'tở', 'thở', 'trở', 'vở', 'xở', 'ỡ', 'bỡ', 'cỡ', 'chỡ', 'dỡ', 'đỡ', 'gỡ', 'lỡ', 'mỡ', 'nỡ', 'ngỡ', 'nhỡ', 'quỡ', 'rỡ', 'sỡ', 'tỡ', 'thỡ', 'trỡ', 'vỡ', 'xỡ', 'ớ', 'bớ', 'cớ', 'chớ', 'dớ', 'đớ', 'hớ', 'khớ', 'lớ', 'mớ', 'nớ', 'ngớ', 'nhớ', 'quớ', 'rớ', 'sớ', 'tớ', 'thớ', 'trớ', 'vớ', 'xớ', 'ợ', 'bợ', 'cợ', 'chợ', 'dợ', 'đợ', 'lợ', 'mợ', 'nợ', 'ngợ', 'nhợ', 'quợ', 'rợ', 'sợ', 'tợ', 'thợ', 'trợ', 'vợ', 'xợ', 'u', 'bu', 'cu', 'chu', 'du', 'đu', 'gu', 'hu', 'khu', 'lu', 'mu', 'nu', 'ngu', 'nhu', 'phu', 'quu', 'ru', 'su', 'tu', 'thu', 'tru', 'vu', 'xu', 'ù', 'bù', 'cù', 'chù', 'dù', 'đù', 'gù', 'hù', 'khù', 'lù', 'mù', 'nù', 'ngù', 'nhù', 'phù', 'quù', 'rù', 'sù', 'tù', 'thù', 'trù', 'vù', 'xù', 'ủ', 'bủ', 'củ', 'chủ', 'dủ', 'đủ', 'hủ', 'khủ', 'lủ', 'mủ', 'nủ', 'ngủ', 'nhủ', 'phủ', 'quủ', 'rủ', 'sủ', 'tủ', 'thủ', 'trủ', 'vủ', 'xủ', 'ũ', 'cũ', 'chũ', 'dũ', 'đũ', 'giũ', 'hũ', 'lũ', 'mũ', 'nũ', 'ngũ', 'nhũ', 'phũ', 'quũ', 'rũ', 'sũ', 'tũ', 'thũ', 'trũ', 'vũ', 'xũ', 'ú', 'bú', 'cú', 'chú', 'dú', 'đú', 'gú', 'hú', 'khú', 'lú', 'mú', 'nú', 'ngú', 'nhú', 'phú', 'quú', 'rú', 'sú', 'tú', 'thú', 'trú', 'vú', 'xú', 'ụ', 'bụ', 'cụ', 'chụ', 'dụ', 'đụ', 'gụ', 'hụ', 'khụ', 'lụ', 'mụ', 'nụ', 'ngụ', 'nhụ', 'phụ', 'quụ', 'rụ', 'sụ', 'tụ', 'thụ', 'trụ', 'vụ', 'xụ', 'ư', 'cư', 'chư', 'dư', 'đư', 'hư', 'khư', 'lư', 'mư', 'nư', 'ngư', 'như', 'quư', 'rư', 'sư', 'tư', 'thư', 'trư', 'vư', 'xư', 'ừ', 'cừ', 'chừ', 'dừ', 'đừ', 'gừ', 'hừ', 'khừ', 'lừ', 'mừ', 'nừ', 'ngừ', 'nhừ', 'quừ', 'rừ', 'sừ', 'từ', 'thừ', 'trừ', 'vừ', 'xừ', 'ử', 'cử', 'chử', 'dử', 'đử', 'hử', 'khử', 'lử', 'mử', 'nử', 'ngử', 'nhử', 'quử', 'rử', 'sử', 'tử', 'thử', 'trử', 'vử', 'xử', 'ữ', 'cữ', 'chữ', 'dữ', 'đữ', 'giữ', 'lữ', 'mữ', 'nữ', 'ngữ', 'nhữ', 'quữ', 'rữ', 'sữ', 'tữ', 'thữ', 'trữ', 'vữ', 'xữ', 'ứ', 'bứ', 'cứ', 'chứ', 'dứ', 'đứ', 'hứ', 'khứ', 'mứ', 'nứ', 'ngứ', 'nhứ', 'quứ', 'rứ', 'sứ', 'tứ', 'thứ', 'trứ', 'vứ', 'xứ', 'ự', 'bự', 'cự', 'chự', 'dự', 'đự', 'hự', 'lự', 'mự', 'nự', 'ngự', 'nhự', 'quự', 'rự', 'sự', 'tự', 'thự', 'trự', 'vự', 'xự', 'y', 'cy', 'chy', 'dy', 'đy', 'hy', 'ly', 'my', 'ny', 'ngy', 'nhy', 'quy', 'ry', 'sy', 'ty', 'thy', 'try', 'vy', 'xy', 'ỳ', 'cỳ', 'chỳ', 'dỳ', 'đỳ', 'kỳ', 'mỳ', 'nỳ', 'ngỳ', 'nhỳ', 'quỳ', 'rỳ', 'sỳ', 'tỳ', 'thỳ', 'trỳ', 'vỳ', 'xỳ', 'ỷ', 'cỷ', 'chỷ', 'dỷ', 'đỷ', 'hỷ', 'kỷ', 'mỷ', 'nỷ', 'ngỷ', 'nhỷ', 'quỷ', 'rỷ', 'sỷ', 'tỷ', 'thỷ', 'trỷ', 'vỷ', 'xỷ', 'ỹ', 'cỹ', 'chỹ', 'dỹ', 'đỹ', 'kỹ', 'mỹ', 'nỹ', 'ngỹ', 'nhỹ', 'quỹ', 'rỹ', 'sỹ', 'tỹ', 'thỹ', 'trỹ', 'vỹ', 'xỹ', 'ý', 'cý', 'chý', 'dý', 'đý', 'hý', 'ký', 'lý', 'mý', 'ný', 'ngý', 'nhý', 'quý', 'rý', 'sý', 'tý', 'thý', 'trý', 'vý', 'xý', 'ỵ', 'cỵ', 'chỵ', 'dỵ', 'đỵ', 'kỵ', 'lỵ', 'mỵ', 'nỵ', 'ngỵ', 'nhỵ', 'quỵ', 'rỵ', 'sỵ', 'tỵ', 'thỵ', 'trỵ', 'vỵ', 'xỵ', 'ac', 'cac', 'chac', 'dac', 'đac', 'mac', 'nac', 'ngac', 'nhac', 'quac', 'rac', 'sac', 'tac', 'thac', 'trac', 'vac', 'xac', 'àc', 'càc', 'chàc', 'dàc', 'đàc', 'màc', 'nàc', 'ngàc', 'nhàc', 'quàc', 'ràc', 'sàc', 'tàc', 'thàc', 'tràc', 'vàc', 'xàc', 'ảc', 'cảc', 'chảc', 'dảc', 'đảc', 'mảc', 'nảc', 'ngảc', 'nhảc', 'quảc', 'rảc', 'sảc', 'tảc', 'thảc', 'trảc', 'vảc', 'xảc', 'ãc', 'cãc', 'chãc', 'dãc', 'đãc', 'mãc', 'nãc', 'ngãc', 'nhãc', 'quãc', 'rãc', 'sãc', 'tãc', 'thãc', 'trãc', 'vãc', 'xãc', 'ác', 'bác', 'các', 'chác', 'dác', 'đác', 'gác', 'giác', 'hác', 'khác', 'lác', 'mác', 'nác', 'ngác', 'nhác', 'phác', 'quác', 'rác', 'sác', 'tác', 'thác', 'trác', 'vác', 'xác', 'ạc', 'bạc', 'cạc', 'chạc', 'dạc', 'đạc', 'gạc', 'hạc', 'khạc', 'lạc', 'mạc', 'nạc', 'ngạc', 'nhạc', 'quạc', 'rạc', 'sạc', 'tạc', 'thạc', 'trạc', 'vạc', 'xạc', 'ai', 'bai', 'cai', 'chai', 'dai', 'đai', 'gai', 'giai', 'hai', 'khai', 'lai', 'mai', 'nai', 'ngai', 'nhai', 'phai', 'quai', 'rai', 'sai', 'tai', 'thai', 'trai', 'vai', 'xai', 'ài', 'bài', 'cài', 'chài', 'dài', 'đài', 'gài', 'hài', 'lài', 'mài', 'nài', 'ngài', 'nhài', 'quài', 'rài', 'sài', 'tài', 'thài', 'trài', 'vài', 'xài', 'ải', 'bải', 'cải', 'chải', 'dải', 'đải', 'giải', 'hải', 'khải', 'lải', 'mải', 'nải', 'ngải', 'nhải', 'phải', 'quải', 'rải', 'sải', 'tải', 'thải', 'trải', 'vải', 'xải', 'ãi', 'bãi', 'cãi', 'chãi', 'dãi', 'đãi', 'gãi', 'giãi', 'hãi', 'lãi', 'mãi', 'nãi', 'ngãi', 'nhãi', 'quãi', 'rãi', 'sãi', 'tãi', 'thãi', 'trãi', 'vãi', 'xãi', 'ái', 'bái', 'cái', 'chái', 'dái', 'đái', 'gái', 'hái', 'khái', 'lái', 'mái', 'nái', 'ngái', 'nhái', 'phái', 'quái', 'rái', 'sái', 'tái', 'thái', 'trái', 'vái', 'xái', 'ại', 'bại', 'cại', 'chại', 'dại', 'đại', 'gại', 'giại', 'hại', 'lại', 'mại', 'nại', 'ngại', 'nhại', 'quại', 'rại', 'sại', 'tại', 'thại', 'trại', 'vại', 'xại', 'am', 'cam', 'cham', 'dam', 'đam', 'gam', 'giam', 'ham', 'kham', 'lam', 'mam', 'nam', 'ngam', 'nham', 'quam', 'ram', 'sam', 'tam', 'tham', 'tram', 'vam', 'xam', 'àm', 'càm', 'chàm', 'dàm', 'đàm', 'hàm', 'làm', 'màm', 'nàm', 'ngàm', 'nhàm', 'phàm', 'quàm', 'ràm', 'sàm', 'tàm', 'thàm', 'tràm', 'vàm', 'xàm', 'ảm', 'cảm', 'chảm', 'dảm', 'đảm', 'giảm', 'khảm', 'lảm', 'mảm', 'nảm', 'ngảm', 'nhảm', 'quảm', 'rảm', 'sảm', 'tảm', 'thảm', 'trảm', 'vảm', 'xảm', 'ãm', 'cãm', 'chãm', 'dãm', 'đãm', 'hãm', 'lãm', 'mãm', 'nãm', 'ngãm', 'nhãm', 'quãm', 'rãm', 'sãm', 'tãm', 'thãm', 'trãm', 'vãm', 'xãm', 'ám', 'bám', 'cám', 'chám', 'dám', 'đám', 'giám', 'hám', 'khám', 'mám', 'nám', 'ngám', 'nhám', 'quám', 'rám', 'sám', 'tám', 'thám', 'trám', 'vám', 'xám', 'ạm', 'cạm', 'chạm', 'dạm', 'đạm', 'hạm', 'lạm', 'mạm', 'nạm', 'ngạm', 'nhạm', 'phạm', 'quạm', 'rạm', 'sạm', 'tạm', 'thạm', 'trạm', 'vạm', 'xạm', 'an', 'ban', 'can', 'chan', 'dan', 'đan', 'gan', 'gian', 'han', 'khan', 'lan', 'man', 'nan', 'ngan', 'nhan', 'quan', 'ran', 'san', 'tan', 'than', 'tran', 'van', 'xan', 'àn', 'bàn', 'càn', 'chàn', 'dàn', 'đàn', 'gàn', 'giàn', 'hàn', 'khàn', 'làn', 'màn', 'nàn', 'ngàn', 'nhàn', 'phàn', 'quàn', 'ràn', 'sàn', 'tàn', 'thàn', 'tràn', 'vàn', 'xàn', 'ản', 'bản', 'cản', 'chản', 'dản', 'đản', 'giản', 'khản', 'mản', 'nản', 'ngản', 'nhản', 'phản', 'quản', 'rản', 'sản', 'tản', 'thản', 'trản', 'vản', 'xản', 'ãn', 'cãn', 'chãn', 'dãn', 'đãn', 'giãn', 'hãn', 'lãn', 'mãn', 'nãn', 'ngãn', 'nhãn', 'quãn', 'rãn', 'sãn', 'tãn', 'thãn', 'trãn', 'vãn', 'xãn', 'án', 'bán', 'cán', 'chán', 'dán', 'đán', 'gán', 'gián', 'hán', 'khán', 'lán', 'mán', 'nán', 'ngán', 'nhán', 'phán', 'quán', 'rán', 'sán', 'tán', 'thán', 'trán', 'ván', 'xán', 'ạn', 'bạn', 'cạn', 'chạn', 'dạn', 'đạn', 'gạn', 'hạn', 'mạn', 'nạn', 'ngạn', 'nhạn', 'phạn', 'quạn', 'rạn', 'sạn', 'tạn', 'thạn', 'trạn', 'vạn', 'xạn', 'ao', 'bao', 'cao', 'chao', 'dao', 'đao', 'gao', 'giao', 'hao', 'khao', 'lao', 'mao', 'nao', 'ngao', 'nhao', 'phao', 'quao', 'rao', 'sao', 'tao', 'thao', 'trao', 'vao', 'xao', 'ào', 'bào', 'cào', 'chào', 'dào', 'đào', 'gào', 'hào', 'lào', 'mào', 'nào', 'ngào', 'nhào', 'phào', 'quào', 'rào', 'sào', 'tào', 'thào', 'trào', 'vào', 'xào', 'ảo', 'bảo', 'cảo', 'chảo', 'dảo', 'đảo', 'giảo', 'hảo', 'khảo', 'lảo', 'mảo', 'nảo', 'ngảo', 'nhảo', 'quảo', 'rảo', 'sảo', 'tảo', 'thảo', 'trảo', 'vảo', 'xảo', 'ão', 'bão', 'cão', 'chão', 'dão', 'đão', 'hão', 'lão', 'mão', 'não', 'ngão', 'nhão', 'quão', 'rão', 'são', 'tão', 'thão', 'trão', 'vão', 'xão', 'áo', 'báo', 'cáo', 'cháo', 'dáo', 'đáo', 'gáo', 'giáo', 'háo', 'kháo', 'láo', 'máo', 'náo', 'ngáo', 'nháo', 'pháo', 'quáo', 'ráo', 'sáo', 'táo', 'tháo', 'tráo', 'váo', 'xáo', 'ạo', 'bạo', 'cạo', 'chạo', 'dạo', 'đạo', 'gạo', 'hạo', 'lạo', 'mạo', 'nạo', 'ngạo', 'nhạo', 'quạo', 'rạo', 'sạo', 'tạo', 'thạo', 'trạo', 'vạo', 'xạo', 'ap', 'cap', 'chap', 'dap', 'đap', 'map', 'nap', 'ngap', 'nhap', 'quap', 'rap', 'sap', 'tap', 'thap', 'trap', 'vap', 'xap', 'àp', 'càp', 'chàp', 'dàp', 'đàp', 'màp', 'nàp', 'ngàp', 'nhàp', 'quàp', 'ràp', 'sàp', 'tàp', 'thàp', 'tràp', 'vàp', 'xàp', 'ảp', 'cảp', 'chảp', 'dảp', 'đảp', 'mảp', 'nảp', 'ngảp', 'nhảp', 'quảp', 'rảp', 'sảp', 'tảp', 'thảp', 'trảp', 'vảp', 'xảp', 'ãp', 'cãp', 'chãp', 'dãp', 'đãp', 'mãp', 'nãp', 'ngãp', 'nhãp', 'quãp', 'rãp', 'sãp', 'tãp', 'thãp', 'trãp', 'vãp', 'xãp', 'áp', 'cáp', 'cháp', 'dáp', 'đáp', 'gáp', 'giáp', 'kháp', 'láp', 'máp', 'náp', 'ngáp', 'nháp', 'pháp', 'quáp', 'ráp', 'sáp', 'táp', 'tháp', 'tráp', 'váp', 'xáp', 'ạp', 'bạp', 'cạp', 'chạp', 'dạp', 'đạp', 'giạp', 'hạp', 'khạp', 'lạp', 'mạp', 'nạp', 'ngạp', 'nhạp', 'quạp', 'rạp', 'sạp', 'tạp', 'thạp', 'trạp', 'vạp', 'xạp', 'at', 'cat', 'chat', 'dat', 'đat', 'mat', 'nat', 'ngat', 'nhat', 'quat', 'rat', 'sat', 'tat', 'that', 'trat', 'vat', 'xat', 'àt', 'càt', 'chàt', 'dàt', 'đàt', 'màt', 'nàt', 'ngàt', 'nhàt', 'quàt', 'ràt', 'sàt', 'tàt', 'thàt', 'tràt', 'vàt', 'xàt', 'ảt', 'cảt', 'chảt', 'dảt', 'đảt', 'mảt', 'nảt', 'ngảt', 'nhảt', 'quảt', 'rảt', 'sảt', 'tảt', 'thảt', 'trảt', 'vảt', 'xảt', 'ãt', 'cãt', 'chãt', 'dãt', 'đãt', 'mãt', 'nãt', 'ngãt', 'nhãt', 'quãt', 'rãt', 'sãt', 'tãt', 'thãt', 'trãt', 'vãt', 'xãt', 'át', 'bát', 'cát', 'chát', 'dát', 'đát', 'giát', 'hát', 'khát', 'lát', 'mát', 'nát', 'ngát', 'nhát', 'phát', 'quát', 'rát', 'sát', 'tát', 'thát', 'trát', 'vát', 'xát', 'ạt', 'bạt', 'cạt', 'chạt', 'dạt', 'đạt', 'gạt', 'hạt', 'lạt', 'mạt', 'nạt', 'ngạt', 'nhạt', 'phạt', 'quạt', 'rạt', 'sạt', 'tạt', 'thạt', 'trạt', 'vạt', 'xạt', 'au', 'cau', 'chau', 'dau', 'đau', 'hau', 'lau', 'mau', 'nau', 'ngau', 'nhau', 'quau', 'rau', 'sau', 'tau', 'thau', 'trau', 'vau', 'xau', 'àu', 'bàu', 'càu', 'chàu', 'dàu', 'đàu', 'giàu', 'làu', 'màu', 'nàu', 'ngàu', 'nhàu', 'quàu', 'ràu', 'sàu', 'tàu', 'thàu', 'tràu', 'vàu', 'xàu', 'ảu', 'cảu', 'chảu', 'dảu', 'đảu', 'mảu', 'nảu', 'ngảu', 'nhảu', 'quảu', 'rảu', 'sảu', 'tảu', 'thảu', 'trảu', 'vảu', 'xảu', 'ãu', 'cãu', 'chãu', 'dãu', 'đãu', 'mãu', 'nãu', 'ngãu', 'nhãu', 'quãu', 'rãu', 'sãu', 'tãu', 'thãu', 'trãu', 'vãu', 'xãu', 'áu', 'báu', 'cáu', 'cháu', 'dáu', 'đáu', 'háu', 'kháu', 'láu', 'máu', 'náu', 'ngáu', 'nháu', 'quáu', 'ráu', 'sáu', 'táu', 'tháu', 'tráu', 'váu', 'xáu', 'ạu', 'bạu', 'cạu', 'chạu', 'dạu', 'đạu', 'lạu', 'mạu', 'nạu', 'ngạu', 'nhạu', 'quạu', 'rạu', 'sạu', 'tạu', 'thạu', 'trạu', 'vạu', 'xạu', 'ay', 'bay', 'cay', 'chay', 'day', 'đay', 'gay', 'hay', 'khay', 'lay', 'may', 'nay', 'ngay', 'nhay', 'phay', 'quay', 'ray', 'say', 'tay', 'thay', 'tray', 'vay', 'xay', 'ày', 'bày', 'cày', 'chày', 'dày', 'đày', 'giày', 'mày', 'này', 'ngày', 'nhày', 'quày', 'rày', 'sày', 'tày', 'thày', 'trày', 'vày', 'xày', 'ảy', 'bảy', 'cảy', 'chảy', 'dảy', 'đảy', 'gảy', 'mảy', 'nảy', 'ngảy', 'nhảy', 'quảy', 'rảy', 'sảy', 'tảy', 'thảy', 'trảy', 'vảy', 'xảy', 'ãy', 'cãy', 'chãy', 'dãy', 'đãy', 'gãy', 'giãy', 'hãy', 'mãy', 'nãy', 'ngãy', 'nhãy', 'quãy', 'rãy', 'sãy', 'tãy', 'thãy', 'trãy', 'vãy', 'xãy', 'áy', 'cáy', 'cháy', 'dáy', 'đáy', 'gáy', 'háy', 'kháy', 'láy', 'máy', 'náy', 'ngáy', 'nháy', 'quáy', 'ráy', 'sáy', 'táy', 'tháy', 'tráy', 'váy', 'xáy', 'ạy', 'cạy', 'chạy', 'dạy', 'đạy', 'lạy', 'mạy', 'nạy', 'ngạy', 'nhạy', 'quạy', 'rạy', 'sạy', 'tạy', 'thạy', 'trạy', 'vạy', 'xạy', 'ăc', 'căc', 'chăc', 'dăc', 'đăc', 'măc', 'năc', 'ngăc', 'nhăc', 'quăc', 'răc', 'săc', 'tăc', 'thăc', 'trăc', 'văc', 'xăc', 'ằc', 'cằc', 'chằc', 'dằc', 'đằc', 'mằc', 'nằc', 'ngằc', 'nhằc', 'quằc', 'rằc', 'sằc', 'tằc', 'thằc', 'trằc', 'vằc', 'xằc', 'ẳc', 'cẳc', 'chẳc', 'dẳc', 'đẳc', 'mẳc', 'nẳc', 'ngẳc', 'nhẳc', 'quẳc', 'rẳc', 'sẳc', 'tẳc', 'thẳc', 'trẳc', 'vẳc', 'xẳc', 'ẵc', 'cẵc', 'chẵc', 'dẵc', 'đẵc', 'mẵc', 'nẵc', 'ngẵc', 'nhẵc', 'quẵc', 'rẵc', 'sẵc', 'tẵc', 'thẵc', 'trẵc', 'vẵc', 'xẵc', 'ắc', 'bắc', 'cắc', 'chắc', 'dắc', 'đắc', 'hắc', 'khắc', 'lắc', 'mắc', 'nắc', 'ngắc', 'nhắc', 'phắc', 'quắc', 'rắc', 'sắc', 'tắc', 'thắc', 'trắc', 'vắc', 'xắc', 'ặc', 'cặc', 'chặc', 'dặc', 'đặc', 'gặc', 'giặc', 'hặc', 'khặc', 'lặc', 'mặc', 'nặc', 'ngặc', 'nhặc', 'quặc', 'rặc', 'sặc', 'tặc', 'thặc', 'trặc', 'vặc', 'xặc', 'ăm', 'băm', 'căm', 'chăm', 'dăm', 'đăm', 'găm', 'giăm', 'hăm', 'khăm', 'lăm', 'măm', 'năm', 'ngăm', 'nhăm', 'phăm', 'quăm', 'răm', 'săm', 'tăm', 'thăm', 'trăm', 'văm', 'xăm', 'ằm', 'bằm', 'cằm', 'chằm', 'dằm', 'đằm', 'gằm', 'giằm', 'mằm', 'nằm', 'ngằm', 'nhằm', 'quằm', 'rằm', 'sằm', 'tằm', 'thằm', 'trằm', 'vằm', 'xằm', 'ẳm', 'cẳm', 'chẳm', 'dẳm', 'đẳm', 'khẳm', 'lẳm', 'mẳm', 'nẳm', 'ngẳm', 'nhẳm', 'quẳm', 'rẳm', 'sẳm', 'tẳm', 'thẳm', 'trẳm', 'vẳm', 'xẳm', 'ẵm', 'cẵm', 'chẵm', 'dẵm', 'đẵm', 'mẵm', 'nẵm', 'ngẵm', 'nhẵm', 'quẵm', 'rẵm', 'sẵm', 'tẵm', 'thẵm', 'trẵm', 'vẵm', 'xẵm', 'ắm', 'cắm', 'chắm', 'dắm', 'đắm', 'gắm', 'khắm', 'lắm', 'mắm', 'nắm', 'ngắm', 'nhắm', 'quắm', 'rắm', 'sắm', 'tắm', 'thắm', 'trắm', 'vắm', 'xắm', 'ặm', 'bặm', 'cặm', 'chặm', 'dặm', 'đặm', 'gặm', 'giặm', 'mặm', 'nặm', 'ngặm', 'nhặm', 'quặm', 'rặm', 'sặm', 'tặm', 'thặm', 'trặm', 'vặm', 'xặm', 'ăn', 'băn', 'căn', 'chăn', 'dăn', 'đăn', 'khăn', 'lăn', 'măn', 'năn', 'ngăn', 'nhăn', 'phăn', 'quăn', 'răn', 'săn', 'tăn', 'thăn', 'trăn', 'văn', 'xăn', 'ằn', 'bằn', 'cằn', 'chằn', 'dằn', 'đằn', 'gằn', 'hằn', 'lằn', 'mằn', 'nằn', 'ngằn', 'nhằn', 'quằn', 'rằn', 'sằn', 'tằn', 'thằn', 'trằn', 'vằn', 'xằn', 'ẳn', 'bẳn', 'cẳn', 'chẳn', 'dẳn', 'đẳn', 'hẳn', 'khẳn', 'lẳn', 'mẳn', 'nẳn', 'ngẳn', 'nhẳn', 'quẳn', 'rẳn', 'sẳn', 'tẳn', 'thẳn', 'trẳn', 'vẳn', 'xẳn', 'ẵn', 'cẵn', 'chẵn', 'dẵn', 'đẵn', 'mẵn', 'nẵn', 'ngẵn', 'nhẵn', 'quẵn', 'rẵn', 'sẵn', 'tẵn', 'thẵn', 'trẵn', 'vẵn', 'xẵn', 'ắn', 'bắn', 'cắn', 'chắn', 'dắn', 'đắn', 'gắn', 'hắn', 'mắn', 'nắn', 'ngắn', 'nhắn', 'quắn', 'rắn', 'sắn', 'tắn', 'thắn', 'trắn', 'vắn', 'xắn', 'ặn', 'cặn', 'chặn', 'dặn', 'đặn', 'gặn', 'lặn', 'mặn', 'nặn', 'ngặn', 'nhặn', 'quặn', 'rặn', 'sặn', 'tặn', 'thặn', 'trặn', 'vặn', 'xặn', 'ăp', 'căp', 'chăp', 'dăp', 'đăp', 'măp', 'năp', 'ngăp', 'nhăp', 'quăp', 'răp', 'săp', 'tăp', 'thăp', 'trăp', 'văp', 'xăp', 'ằp', 'cằp', 'chằp', 'dằp', 'đằp', 'mằp', 'nằp', 'ngằp', 'nhằp', 'quằp', 'rằp', 'sằp', 'tằp', 'thằp', 'trằp', 'vằp', 'xằp', 'ẳp', 'cẳp', 'chẳp', 'dẳp', 'đẳp', 'mẳp', 'nẳp', 'ngẳp', 'nhẳp', 'quẳp', 'rẳp', 'sẳp', 'tẳp', 'thẳp', 'trẳp', 'vẳp', 'xẳp', 'ẵp', 'cẵp', 'chẵp', 'dẵp', 'đẵp', 'mẵp', 'nẵp', 'ngẵp', 'nhẵp', 'quẵp', 'rẵp', 'sẵp', 'tẵp', 'thẵp', 'trẵp', 'vẵp', 'xẵp', 'ắp', 'bắp', 'cắp', 'chắp', 'dắp', 'đắp', 'gắp', 'khắp', 'lắp', 'mắp', 'nắp', 'ngắp', 'nhắp', 'phắp', 'quắp', 'rắp', 'sắp', 'tắp', 'thắp', 'trắp', 'vắp', 'xắp', 'ặp', 'cặp', 'chặp', 'dặp', 'đặp', 'gặp', 'lặp', 'mặp', 'nặp', 'ngặp', 'nhặp', 'quặp', 'rặp', 'sặp', 'tặp', 'thặp', 'trặp', 'vặp', 'xặp', 'ăt', 'căt', 'chăt', 'dăt', 'đăt', 'măt', 'năt', 'ngăt', 'nhăt', 'quăt', 'răt', 'săt', 'tăt', 'thăt', 'trăt', 'văt', 'xăt', 'ằt', 'cằt', 'chằt', 'dằt', 'đằt', 'mằt', 'nằt', 'ngằt', 'nhằt', 'quằt', 'rằt', 'sằt', 'tằt', 'thằt', 'trằt', 'vằt', 'xằt', 'ẳt', 'cẳt', 'chẳt', 'dẳt', 'đẳt', 'mẳt', 'nẳt', 'ngẳt', 'nhẳt', 'quẳt', 'rẳt', 'sẳt', 'tẳt', 'thẳt', 'trẳt', 'vẳt', 'xẳt', 'ẵt', 'cẵt', 'chẵt', 'dẵt', 'đẵt', 'mẵt', 'nẵt', 'ngẵt', 'nhẵt', 'quẵt', 'rẵt', 'sẵt', 'tẵt', 'thẵt', 'trẵt', 'vẵt', 'xẵt', 'ắt', 'bắt', 'cắt', 'chắt', 'dắt', 'đắt', 'gắt', 'giắt', 'hắt', 'khắt', 'lắt', 'mắt', 'nắt', 'ngắt', 'nhắt', 'phắt', 'quắt', 'rắt', 'sắt', 'tắt', 'thắt', 'trắt', 'vắt', 'xắt', 'ặt', 'bặt', 'cặt', 'chặt', 'dặt', 'đặt', 'gặt', 'giặt', 'lặt', 'mặt', 'nặt', 'ngặt', 'nhặt', 'quặt', 'rặt', 'sặt', 'tặt', 'thặt', 'trặt', 'vặt', 'xặt', 'âc', 'câc', 'châc', 'dâc', 'đâc', 'mâc', 'nâc', 'ngâc', 'nhâc', 'quâc', 'râc', 'sâc', 'tâc', 'thâc', 'trâc', 'vâc', 'xâc', 'ầc', 'cầc', 'chầc', 'dầc', 'đầc', 'mầc', 'nầc', 'ngầc', 'nhầc', 'quầc', 'rầc', 'sầc', 'tầc', 'thầc', 'trầc', 'vầc', 'xầc', 'ẩc', 'cẩc', 'chẩc', 'dẩc', 'đẩc', 'mẩc', 'nẩc', 'ngẩc', 'nhẩc', 'quẩc', 'rẩc', 'sẩc', 'tẩc', 'thẩc', 'trẩc', 'vẩc', 'xẩc', 'ẫc', 'cẫc', 'chẫc', 'dẫc', 'đẫc', 'mẫc', 'nẫc', 'ngẫc', 'nhẫc', 'quẫc', 'rẫc', 'sẫc', 'tẫc', 'thẫc', 'trẫc', 'vẫc', 'xẫc', 'ấc', 'bấc', 'cấc', 'chấc', 'dấc', 'đấc', 'gấc', 'giấc', 'khấc', 'lấc', 'mấc', 'nấc', 'ngấc', 'nhấc', 'quấc', 'rấc', 'sấc', 'tấc', 'thấc', 'trấc', 'vấc', 'xấc', 'ậc', 'bậc', 'cậc', 'chậc', 'dậc', 'đậc', 'mậc', 'nậc', 'ngậc', 'nhậc', 'quậc', 'rậc', 'sậc', 'tậc', 'thậc', 'trậc', 'vậc', 'xậc', 'âm', 'câm', 'châm', 'dâm', 'đâm', 'giâm', 'hâm', 'khâm', 'lâm', 'mâm', 'nâm', 'ngâm', 'nhâm', 'quâm', 'râm', 'sâm', 'tâm', 'thâm', 'trâm', 'vâm', 'xâm', 'ầm', 'bầm', 'cầm', 'chầm', 'dầm', 'đầm', 'gầm', 'hầm', 'lầm', 'mầm', 'nầm', 'ngầm', 'nhầm', 'phầm', 'quầm', 'rầm', 'sầm', 'tầm', 'thầm', 'trầm', 'vầm', 'xầm', 'ẩm', 'bẩm', 'cẩm', 'chẩm', 'dẩm', 'đẩm', 'gẩm', 'hẩm', 'lẩm', 'mẩm', 'nẩm', 'ngẩm', 'nhẩm', 'phẩm', 'quẩm', 'rẩm', 'sẩm', 'tẩm', 'thẩm', 'trẩm', 'vẩm', 'xẩm', 'ẫm', 'bẫm', 'cẫm', 'chẫm', 'dẫm', 'đẫm', 'gẫm', 'giẫm', 'lẫm', 'mẫm', 'nẫm', 'ngẫm', 'nhẫm', 'quẫm', 'rẫm', 'sẫm', 'tẫm', 'thẫm', 'trẫm', 'vẫm', 'xẫm', 'ấm', 'bấm', 'cấm', 'chấm', 'dấm', 'đấm', 'gấm', 'giấm', 'hấm', 'lấm', 'mấm', 'nấm', 'ngấm', 'nhấm', 'quấm', 'rấm', 'sấm', 'tấm', 'thấm', 'trấm', 'vấm', 'xấm', 'ậm', 'bậm', 'cậm', 'chậm', 'dậm', 'đậm', 'giậm', 'hậm', 'lậm', 'mậm', 'nậm', 'ngậm', 'nhậm', 'quậm', 'rậm', 'sậm', 'tậm', 'thậm', 'trậm', 'vậm', 'xậm', 'ân', 'cân', 'chân', 'dân', 'đân', 'gân', 'hân', 'khân', 'lân', 'mân', 'nân', 'ngân', 'nhân', 'phân', 'quân', 'rân', 'sân', 'tân', 'thân', 'trân', 'vân', 'xân', 'ần', 'bần', 'cần', 'chần', 'dần', 'đần', 'gần', 'giần', 'lần', 'mần', 'nần', 'ngần', 'nhần', 'phần', 'quần', 'rần', 'sần', 'tần', 'thần', 'trần', 'vần', 'xần', 'ẩn', 'bẩn', 'cẩn', 'chẩn', 'dẩn', 'đẩn', 'khẩn', 'lẩn', 'mẩn', 'nẩn', 'ngẩn', 'nhẩn', 'quẩn', 'rẩn', 'sẩn', 'tẩn', 'thẩn', 'trẩn', 'vẩn', 'xẩn', 'ẫn', 'cẫn', 'chẫn', 'dẫn', 'đẫn', 'lẫn', 'mẫn', 'nẫn', 'ngẫn', 'nhẫn', 'phẫn', 'quẫn', 'rẫn', 'sẫn', 'tẫn', 'thẫn', 'trẫn', 'vẫn', 'xẫn', 'ấn', 'bấn', 'cấn', 'chấn', 'dấn', 'đấn', 'hấn', 'khấn', 'lấn', 'mấn', 'nấn', 'ngấn', 'nhấn', 'phấn', 'quấn', 'rấn', 'sấn', 'tấn', 'thấn', 'trấn', 'vấn', 'xấn', 'ận', 'bận', 'cận', 'chận', 'dận', 'đận', 'giận', 'hận', 'lận', 'mận', 'nận', 'ngận', 'nhận', 'phận', 'quận', 'rận', 'sận', 'tận', 'thận', 'trận', 'vận', 'xận', 'âp', 'câp', 'châp', 'dâp', 'đâp', 'mâp', 'nâp', 'ngâp', 'nhâp', 'quâp', 'râp', 'sâp', 'tâp', 'thâp', 'trâp', 'vâp', 'xâp', 'ầp', 'cầp', 'chầp', 'dầp', 'đầp', 'mầp', 'nầp', 'ngầp', 'nhầp', 'quầp', 'rầp', 'sầp', 'tầp', 'thầp', 'trầp', 'vầp', 'xầp', 'ẩp', 'cẩp', 'chẩp', 'dẩp', 'đẩp', 'mẩp', 'nẩp', 'ngẩp', 'nhẩp', 'quẩp', 'rẩp', 'sẩp', 'tẩp', 'thẩp', 'trẩp', 'vẩp', 'xẩp', 'ẫp', 'cẫp', 'chẫp', 'dẫp', 'đẫp', 'mẫp', 'nẫp', 'ngẫp', 'nhẫp', 'quẫp', 'rẫp', 'sẫp', 'tẫp', 'thẫp', 'trẫp', 'vẫp', 'xẫp', 'ấp', 'bấp', 'cấp', 'chấp', 'dấp', 'đấp', 'gấp', 'hấp', 'khấp', 'lấp', 'mấp', 'nấp', 'ngấp', 'nhấp', 'phấp', 'quấp', 'rấp', 'sấp', 'tấp', 'thấp', 'trấp', 'vấp', 'xấp', 'ập', 'bập', 'cập', 'chập', 'dập', 'đập', 'gập', 'giập', 'hập', 'khập', 'lập', 'mập', 'nập', 'ngập', 'nhập', 'phập', 'quập', 'rập', 'sập', 'tập', 'thập', 'trập', 'vập', 'xập', 'ât', 'cât', 'chât', 'dât', 'đât', 'mât', 'nât', 'ngât', 'nhât', 'quât', 'rât', 'sât', 'tât', 'thât', 'trât', 'vât', 'xât', 'ầt', 'cầt', 'chầt', 'dầt', 'đầt', 'mầt', 'nầt', 'ngầt', 'nhầt', 'quầt', 'rầt', 'sầt', 'tầt', 'thầt', 'trầt', 'vầt', 'xầt', 'ẩt', 'cẩt', 'chẩt', 'dẩt', 'đẩt', 'mẩt', 'nẩt', 'ngẩt', 'nhẩt', 'quẩt', 'rẩt', 'sẩt', 'tẩt', 'thẩt', 'trẩt', 'vẩt', 'xẩt', 'ẫt', 'cẫt', 'chẫt', 'dẫt', 'đẫt', 'mẫt', 'nẫt', 'ngẫt', 'nhẫt', 'quẫt', 'rẫt', 'sẫt', 'tẫt', 'thẫt', 'trẫt', 'vẫt', 'xẫt', 'ất', 'bất', 'cất', 'chất', 'dất', 'đất', 'hất', 'khất', 'lất', 'mất', 'nất', 'ngất', 'nhất', 'phất', 'quất', 'rất', 'sất', 'tất', 'thất', 'trất', 'vất', 'xất', 'ật', 'bật', 'cật', 'chật', 'dật', 'đật', 'gật', 'giật', 'khật', 'lật', 'mật', 'nật', 'ngật', 'nhật', 'phật', 'quật', 'rật', 'sật', 'tật', 'thật', 'trật', 'vật', 'xật', 'âu', 'bâu', 'câu', 'châu', 'dâu', 'đâu', 'gâu', 'hâu', 'khâu', 'lâu', 'mâu', 'nâu', 'ngâu', 'nhâu', 'quâu', 'râu', 'sâu', 'tâu', 'thâu', 'trâu', 'vâu', 'xâu', 'ầu', 'bầu', 'cầu', 'chầu', 'dầu', 'đầu', 'gầu', 'hầu', 'lầu', 'mầu', 'nầu', 'ngầu', 'nhầu', 'quầu', 'rầu', 'sầu', 'tầu', 'thầu', 'trầu', 'vầu', 'xầu', 'ẩu', 'cẩu', 'chẩu', 'dẩu', 'đẩu', 'hẩu', 'khẩu', 'lẩu', 'mẩu', 'nẩu', 'ngẩu', 'nhẩu', 'quẩu', 'rẩu', 'sẩu', 'tẩu', 'thẩu', 'trẩu', 'vẩu', 'xẩu', 'ẫu', 'cẫu', 'chẫu', 'dẫu', 'đẫu', 'gẫu', 'mẫu', 'nẫu', 'ngẫu', 'nhẫu', 'phẫu', 'quẫu', 'rẫu', 'sẫu', 'tẫu', 'thẫu', 'trẫu', 'vẫu', 'xẫu', 'ấu', 'bấu', 'cấu', 'chấu', 'dấu', 'đấu', 'gấu', 'giấu', 'hấu', 'khấu', 'lấu', 'mấu', 'nấu', 'ngấu', 'nhấu', 'quấu', 'rấu', 'sấu', 'tấu', 'thấu', 'trấu', 'vấu', 'xấu', 'ậu', 'bậu', 'cậu', 'chậu', 'dậu', 'đậu', 'giậu', 'hậu', 'lậu', 'mậu', 'nậu', 'ngậu', 'nhậu', 'quậu', 'rậu', 'sậu', 'tậu', 'thậu', 'trậu', 'vậu', 'xậu', 'ây', 'bây', 'cây', 'chây', 'dây', 'đây', 'gây', 'giây', 'hây', 'lây', 'mây', 'nây', 'ngây', 'nhây', 'phây', 'quây', 'rây', 'sây', 'tây', 'thây', 'trây', 'vây', 'xây', 'ầy', 'bầy', 'cầy', 'chầy', 'dầy', 'đầy', 'gầy', 'giầy', 'hầy', 'lầy', 'mầy', 'nầy', 'ngầy', 'nhầy', 'quầy', 'rầy', 'sầy', 'tầy', 'thầy', 'trầy', 'vầy', 'xầy', 'ẩy', 'bẩy', 'cẩy', 'chẩy', 'dẩy', 'đẩy', 'gẩy', 'hẩy', 'lẩy', 'mẩy', 'nẩy', 'ngẩy', 'nhẩy', 'phẩy', 'quẩy', 'rẩy', 'sẩy', 'tẩy', 'thẩy', 'trẩy', 'vẩy', 'xẩy', 'ẫy', 'bẫy', 'cẫy', 'chẫy', 'dẫy', 'đẫy', 'gẫy', 'giẫy', 'lẫy', 'mẫy', 'nẫy', 'ngẫy', 'nhẫy', 'quẫy', 'rẫy', 'sẫy', 'tẫy', 'thẫy', 'trẫy', 'vẫy', 'xẫy', 'ấy', 'bấy', 'cấy', 'chấy', 'dấy', 'đấy', 'gấy', 'giấy', 'lấy', 'mấy', 'nấy', 'ngấy', 'nhấy', 'quấy', 'rấy', 'sấy', 'tấy', 'thấy', 'trấy', 'vấy', 'xấy', 'ậy', 'bậy', 'cậy', 'chậy', 'dậy', 'đậy', 'gậy', 'mậy', 'nậy', 'ngậy', 'nhậy', 'quậy', 'rậy', 'sậy', 'tậy', 'thậy', 'trậy', 'vậy', 'xậy', 'ec', 'cec', 'chec', 'dec', 'đec', 'mec', 'nec', 'ngec', 'nhec', 'quec', 'rec', 'sec', 'tec', 'thec', 'trec', 'vec', 'xec', 'èc', 'cèc', 'chèc', 'dèc', 'đèc', 'mèc', 'nèc', 'ngèc', 'nhèc', 'quèc', 'rèc', 'sèc', 'tèc', 'thèc', 'trèc', 'vèc', 'xèc', 'ẻc', 'cẻc', 'chẻc', 'dẻc', 'đẻc', 'mẻc', 'nẻc', 'ngẻc', 'nhẻc', 'quẻc', 'rẻc', 'sẻc', 'tẻc', 'thẻc', 'trẻc', 'vẻc', 'xẻc', 'ẽc', 'cẽc', 'chẽc', 'dẽc', 'đẽc', 'mẽc', 'nẽc', 'ngẽc', 'nhẽc', 'quẽc', 'rẽc', 'sẽc', 'tẽc', 'thẽc', 'trẽc', 'vẽc', 'xẽc', 'éc', 'céc', 'chéc', 'déc', 'đéc', 'méc', 'néc', 'ngéc', 'nhéc', 'quéc', 'réc', 'séc', 'téc', 'théc', 'tréc', 'véc', 'xéc', 'ẹc', 'cẹc', 'chẹc', 'dẹc', 'đẹc', 'khẹc', 'mẹc', 'nẹc', 'ngẹc', 'nhẹc', 'quẹc', 'rẹc', 'sẹc', 'tẹc', 'thẹc', 'trẹc', 'vẹc', 'xẹc', 'em', 'bem', 'cem', 'chem', 'dem', 'đem', 'hem', 'kem', 'khem', 'lem', 'mem', 'nem', 'ngem', 'nhem', 'quem', 'rem', 'sem', 'tem', 'them', 'trem', 'vem', 'xem', 'èm', 'bèm', 'cèm', 'chèm', 'dèm', 'đèm', 'gièm', 'hèm', 'kèm', 'lèm', 'mèm', 'nèm', 'ngèm', 'nhèm', 'quèm', 'rèm', 'sèm', 'tèm', 'thèm', 'trèm', 'vèm', 'xèm', 'ẻm', 'bẻm', 'cẻm', 'chẻm', 'dẻm', 'đẻm', 'hẻm', 'lẻm', 'mẻm', 'nẻm', 'ngẻm', 'nhẻm', 'quẻm', 'rẻm', 'sẻm', 'tẻm', 'thẻm', 'trẻm', 'vẻm', 'xẻm', 'ẽm', 'cẽm', 'chẽm', 'dẽm', 'đẽm', 'kẽm', 'mẽm', 'nẽm', 'ngẽm', 'nhẽm', 'quẽm', 'rẽm', 'sẽm', 'tẽm', 'thẽm', 'trẽm', 'vẽm', 'xẽm', 'ém', 'cém', 'chém', 'dém', 'đém', 'ghém', 'kém', 'lém', 'mém', 'ném', 'ngém', 'nhém', 'quém', 'rém', 'sém', 'tém', 'thém', 'trém', 'vém', 'xém', 'ẹm', 'bẹm', 'cẹm', 'chẹm', 'dẹm', 'đẹm', 'lẹm', 'mẹm', 'nẹm', 'ngẹm', 'nhẹm', 'quẹm', 'rẹm', 'sẹm', 'tẹm', 'thẹm', 'trẹm', 'vẹm', 'xẹm', 'en', 'ben', 'cen', 'chen', 'den', 'đen', 'ghen', 'hen', 'ken', 'khen', 'len', 'men', 'nen', 'ngen', 'nghen', 'nhen', 'phen', 'quen', 'ren', 'sen', 'ten', 'then', 'tren', 'ven', 'xen', 'èn', 'bèn', 'cèn', 'chèn', 'dèn', 'đèn', 'ghèn', 'hèn', 'kèn', 'khèn', 'lèn', 'mèn', 'nèn', 'ngèn', 'nghèn', 'nhèn', 'phèn', 'quèn', 'rèn', 'sèn', 'tèn', 'thèn', 'trèn', 'vèn', 'xèn', 'ẻn', 'cẻn', 'chẻn', 'dẻn', 'đẻn', 'hẻn', 'lẻn', 'mẻn', 'nẻn', 'ngẻn', 'nhẻn', 'quẻn', 'rẻn', 'sẻn', 'tẻn', 'thẻn', 'trẻn', 'vẻn', 'xẻn', 'ẽn', 'bẽn', 'cẽn', 'chẽn', 'dẽn', 'đẽn', 'lẽn', 'mẽn', 'nẽn', 'ngẽn', 'nghẽn', 'nhẽn', 'quẽn', 'rẽn', 'sẽn', 'tẽn', 'thẽn', 'trẽn', 'vẽn', 'xẽn', 'én', 'bén', 'cén', 'chén', 'dén', 'đén', 'hén', 'kén', 'khén', 'lén', 'mén', 'nén', 'ngén', 'nghén', 'nhén', 'quén', 'rén', 'sén', 'tén', 'thén', 'trén', 'vén', 'xén', 'ẹn', 'bẹn', 'cẹn', 'chẹn', 'dẹn', 'đẹn', 'hẹn', 'lẹn', 'mẹn', 'nẹn', 'ngẹn', 'nghẹn', 'nhẹn', 'quẹn', 'rẹn', 'sẹn', 'tẹn', 'thẹn', 'trẹn', 'vẹn', 'xẹn', 'eo', 'beo', 'ceo', 'cheo', 'deo', 'đeo', 'gieo', 'heo', 'keo', 'kheo', 'leo', 'meo', 'neo', 'ngeo', 'nheo', 'queo', 'reo', 'seo', 'teo', 'theo', 'treo', 'veo', 'xeo', 'èo', 'bèo', 'cèo', 'chèo', 'dèo', 'đèo', 'hèo', 'kèo', 'lèo', 'mèo', 'nèo', 'ngèo', 'nghèo', 'nhèo', 'phèo', 'quèo', 'rèo', 'sèo', 'tèo', 'thèo', 'trèo', 'vèo', 'xèo', 'ẻo', 'bẻo', 'cẻo', 'chẻo', 'dẻo', 'đẻo', 'hẻo', 'kẻo', 'lẻo', 'mẻo', 'nẻo', 'ngẻo', 'nhẻo', 'quẻo', 'rẻo', 'sẻo', 'tẻo', 'thẻo', 'trẻo', 'vẻo', 'xẻo', 'ẽo', 'cẽo', 'chẽo', 'dẽo', 'đẽo', 'kẽo', 'lẽo', 'mẽo', 'nẽo', 'ngẽo', 'nghẽo', 'nhẽo', 'quẽo', 'rẽo', 'sẽo', 'tẽo', 'thẽo', 'trẽo', 'vẽo', 'xẽo', 'éo', 'béo', 'céo', 'chéo', 'déo', 'đéo', 'héo', 'kéo', 'khéo', 'léo', 'méo', 'néo', 'ngéo', 'nhéo', 'quéo', 'réo', 'séo', 'téo', 'théo', 'tréo', 'véo', 'xéo', 'ẹo', 'bẹo', 'cẹo', 'chẹo', 'dẹo', 'đẹo', 'ghẹo', 'giẹo', 'kẹo', 'lẹo', 'mẹo', 'nẹo', 'ngẹo', 'nhẹo', 'quẹo', 'rẹo', 'sẹo', 'tẹo', 'thẹo', 'trẹo', 'vẹo', 'xẹo', 'ep', 'cep', 'chep', 'dep', 'đep', 'mep', 'nep', 'ngep', 'nhep', 'quep', 'rep', 'sep', 'tep', 'thep', 'trep', 'vep', 'xep', 'èp', 'cèp', 'chèp', 'dèp', 'đèp', 'mèp', 'nèp', 'ngèp', 'nhèp', 'quèp', 'rèp', 'sèp', 'tèp', 'thèp', 'trèp', 'vèp', 'xèp', 'ẻp', 'cẻp', 'chẻp', 'dẻp', 'đẻp', 'mẻp', 'nẻp', 'ngẻp', 'nhẻp', 'quẻp', 'rẻp', 'sẻp', 'tẻp', 'thẻp', 'trẻp', 'vẻp', 'xẻp', 'ẽp', 'cẽp', 'chẽp', 'dẽp', 'đẽp', 'mẽp', 'nẽp', 'ngẽp', 'nhẽp', 'quẽp', 'rẽp', 'sẽp', 'tẽp', 'thẽp', 'trẽp', 'vẽp', 'xẽp', 'ép', 'bép', 'cép', 'chép', 'dép', 'đép', 'ghép', 'kép', 'khép', 'lép', 'mép', 'nép', 'ngép', 'nhép', 'phép', 'quép', 'rép', 'sép', 'tép', 'thép', 'trép', 'vép', 'xép', 'ẹp', 'bẹp', 'cẹp', 'chẹp', 'dẹp', 'đẹp', 'hẹp', 'kẹp', 'lẹp', 'mẹp', 'nẹp', 'ngẹp', 'nhẹp', 'quẹp', 'rẹp', 'sẹp', 'tẹp', 'thẹp', 'trẹp', 'vẹp', 'xẹp', 'et', 'cet', 'chet', 'det', 'đet', 'met', 'net', 'nget', 'nhet', 'quet', 'ret', 'set', 'tet', 'thet', 'tret', 'vet', 'xet', 'èt', 'cèt', 'chèt', 'dèt', 'đèt', 'mèt', 'nèt', 'ngèt', 'nhèt', 'quèt', 'rèt', 'sèt', 'tèt', 'thèt', 'trèt', 'vèt', 'xèt', 'ẻt', 'cẻt', 'chẻt', 'dẻt', 'đẻt', 'mẻt', 'nẻt', 'ngẻt', 'nhẻt', 'quẻt', 'rẻt', 'sẻt', 'tẻt', 'thẻt', 'trẻt', 'vẻt', 'xẻt', 'ẽt', 'cẽt', 'chẽt', 'dẽt', 'đẽt', 'mẽt', 'nẽt', 'ngẽt', 'nhẽt', 'quẽt', 'rẽt', 'sẽt', 'tẽt', 'thẽt', 'trẽt', 'vẽt', 'xẽt', 'ét', 'bét', 'cét', 'chét', 'dét', 'đét', 'ghét', 'hét', 'két', 'khét', 'lét', 'mét', 'nét', 'ngét', 'nghét', 'nhét', 'phét', 'quét', 'rét', 'sét', 'tét', 'thét', 'trét', 'vét', 'xét', 'ẹt', 'bẹt', 'cẹt', 'chẹt', 'dẹt', 'đẹt', 'kẹt', 'khẹt', 'lẹt', 'mẹt', 'nẹt', 'ngẹt', 'nghẹt', 'nhẹt', 'quẹt', 'rẹt', 'sẹt', 'tẹt', 'thẹt', 'trẹt', 'vẹt', 'xẹt', 'êm', 'cêm', 'chêm', 'dêm', 'đêm', 'mêm', 'nêm', 'ngêm', 'nhêm', 'quêm', 'rêm', 'sêm', 'têm', 'thêm', 'trêm', 'vêm', 'xêm', 'ềm', 'cềm', 'chềm', 'dềm', 'đềm', 'mềm', 'nềm', 'ngềm', 'nhềm', 'quềm', 'rềm', 'sềm', 'tềm', 'thềm', 'trềm', 'vềm', 'xềm', 'ểm', 'cểm', 'chểm', 'dểm', 'đểm', 'mểm', 'nểm', 'ngểm', 'nhểm', 'quểm', 'rểm', 'sểm', 'tểm', 'thểm', 'trểm', 'vểm', 'xểm', 'ễm', 'cễm', 'chễm', 'dễm', 'đễm', 'mễm', 'nễm', 'ngễm', 'nhễm', 'quễm', 'rễm', 'sễm', 'tễm', 'thễm', 'trễm', 'vễm', 'xễm', 'ếm', 'cếm', 'chếm', 'dếm', 'đếm', 'giếm', 'mếm', 'nếm', 'ngếm', 'nhếm', 'quếm', 'rếm', 'sếm', 'tếm', 'thếm', 'trếm', 'vếm', 'xếm', 'ệm', 'cệm', 'chệm', 'dệm', 'đệm', 'mệm', 'nệm', 'ngệm', 'nhệm', 'quệm', 'rệm', 'sệm', 'tệm', 'thệm', 'trệm', 'vệm', 'xệm', 'ên', 'bên', 'cên', 'chên', 'dên', 'đên', 'hên', 'lên', 'mên', 'nên', 'ngên', 'nhên', 'quên', 'rên', 'sên', 'tên', 'thên', 'trên', 'vên', 'xên', 'ền', 'bền', 'cền', 'chền', 'dền', 'đền', 'kền', 'mền', 'nền', 'ngền', 'nghền', 'nhền', 'quền', 'rền', 'sền', 'tền', 'thền', 'trền', 'vền', 'xền', 'ển', 'bển', 'cển', 'chển', 'dển', 'đển', 'hển', 'mển', 'nển', 'ngển', 'nghển', 'nhển', 'quển', 'rển', 'sển', 'tển', 'thển', 'trển', 'vển', 'xển', 'ễn', 'cễn', 'chễn', 'dễn', 'đễn', 'mễn', 'nễn', 'ngễn', 'nhễn', 'quễn', 'rễn', 'sễn', 'tễn', 'thễn', 'trễn', 'vễn', 'xễn', 'ến', 'bến', 'cến', 'chến', 'dến', 'đến', 'hến', 'mến', 'nến', 'ngến', 'nhến', 'quến', 'rến', 'sến', 'tến', 'thến', 'trến', 'vến', 'xến', 'ện', 'bện', 'cện', 'chện', 'dện', 'đện', 'mện', 'nện', 'ngện', 'nghện', 'nhện', 'quện', 'rện', 'sện', 'tện', 'thện', 'trện', 'vện', 'xện', 'êp', 'cêp', 'chêp', 'dêp', 'đêp', 'mêp', 'nêp', 'ngêp', 'nhêp', 'quêp', 'rêp', 'sêp', 'têp', 'thêp', 'trêp', 'vêp', 'xêp', 'ềp', 'cềp', 'chềp', 'dềp', 'đềp', 'mềp', 'nềp', 'ngềp', 'nhềp', 'quềp', 'rềp', 'sềp', 'tềp', 'thềp', 'trềp', 'vềp', 'xềp', 'ểp', 'cểp', 'chểp', 'dểp', 'đểp', 'mểp', 'nểp', 'ngểp', 'nhểp', 'quểp', 'rểp', 'sểp', 'tểp', 'thểp', 'trểp', 'vểp', 'xểp', 'ễp', 'cễp', 'chễp', 'dễp', 'đễp', 'mễp', 'nễp', 'ngễp', 'nhễp', 'quễp', 'rễp', 'sễp', 'tễp', 'thễp', 'trễp', 'vễp', 'xễp', 'ếp', 'bếp', 'cếp', 'chếp', 'dếp', 'đếp', 'mếp', 'nếp', 'ngếp', 'nhếp', 'quếp', 'rếp', 'sếp', 'tếp', 'thếp', 'trếp', 'vếp', 'xếp', 'ệp', 'cệp', 'chệp', 'dệp', 'đệp', 'mệp', 'nệp', 'ngệp', 'nhệp', 'quệp', 'rệp', 'sệp', 'tệp', 'thệp', 'trệp', 'vệp', 'xệp', 'êt', 'cêt', 'chêt', 'dêt', 'đêt', 'mêt', 'nêt', 'ngêt', 'nhêt', 'quêt', 'rêt', 'sêt', 'têt', 'thêt', 'trêt', 'vêt', 'xêt', 'ềt', 'cềt', 'chềt', 'dềt', 'đềt', 'mềt', 'nềt', 'ngềt', 'nhềt', 'quềt', 'rềt', 'sềt', 'tềt', 'thềt', 'trềt', 'vềt', 'xềt', 'ểt', 'cểt', 'chểt', 'dểt', 'đểt', 'mểt', 'nểt', 'ngểt', 'nhểt', 'quểt', 'rểt', 'sểt', 'tểt', 'thểt', 'trểt', 'vểt', 'xểt', 'ễt', 'cễt', 'chễt', 'dễt', 'đễt', 'mễt', 'nễt', 'ngễt', 'nhễt', 'quễt', 'rễt', 'sễt', 'tễt', 'thễt', 'trễt', 'vễt', 'xễt', 'ết', 'bết', 'cết', 'chết', 'dết', 'đết', 'giết', 'hết', 'kết', 'lết', 'mết', 'nết', 'ngết', 'nhết', 'phết', 'quết', 'rết', 'sết', 'tết', 'thết', 'trết', 'vết', 'xết', 'ệt', 'bệt', 'cệt', 'chệt', 'dệt', 'đệt', 'hệt', 'lệt', 'mệt', 'nệt', 'ngệt', 'nghệt', 'nhệt', 'quệt', 'rệt', 'sệt', 'tệt', 'thệt', 'trệt', 'vệt', 'xệt', 'êu', 'bêu', 'cêu', 'chêu', 'dêu', 'đêu', 'kêu', 'khêu', 'lêu', 'mêu', 'nêu', 'ngêu', 'nghêu', 'nhêu', 'quêu', 'rêu', 'sêu', 'têu', 'thêu', 'trêu', 'vêu', 'xêu', 'ều', 'bều', 'cều', 'chều', 'dều', 'đều', 'khều', 'lều', 'mều', 'nều', 'ngều', 'nhều', 'phều', 'quều', 'rều', 'sều', 'tều', 'thều', 'trều', 'vều', 'xều', 'ểu', 'cểu', 'chểu', 'dểu', 'đểu', 'mểu', 'nểu', 'ngểu', 'nhểu', 'quểu', 'rểu', 'sểu', 'tểu', 'thểu', 'trểu', 'vểu', 'xểu', 'ễu', 'cễu', 'chễu', 'dễu', 'đễu', 'giễu', 'mễu', 'nễu', 'ngễu', 'nghễu', 'nhễu', 'phễu', 'quễu', 'rễu', 'sễu', 'tễu', 'thễu', 'trễu', 'vễu', 'xễu', 'ếu', 'cếu', 'chếu', 'dếu', 'đếu', 'lếu', 'mếu', 'nếu', 'ngếu', 'nhếu', 'quếu', 'rếu', 'sếu', 'tếu', 'thếu', 'trếu', 'vếu', 'xếu', 'ệu', 'bệu', 'cệu', 'chệu', 'dệu', 'đệu', 'mệu', 'nệu', 'ngệu', 'nhệu', 'quệu', 'rệu', 'sệu', 'tệu', 'thệu', 'trệu', 'vệu', 'xệu', 'ia', 'bia', 'cia', 'chia', 'dia', 'đia', 'hia', 'kia', 'lia', 'mia', 'nia', 'ngia', 'nhia', 'quia', 'ria', 'sia', 'tia', 'thia', 'tria', 'via', 'xia', 'ìa', 'bìa', 'cìa', 'chìa', 'dìa', 'đìa', 'kìa', 'lìa', 'mìa', 'nìa', 'ngìa', 'nhìa', 'quìa', 'rìa', 'sìa', 'tìa', 'thìa', 'trìa', 'vìa', 'xìa', 'ỉa', 'cỉa', 'chỉa', 'dỉa', 'đỉa', 'mỉa', 'nỉa', 'ngỉa', 'nhỉa', 'quỉa', 'rỉa', 'sỉa', 'tỉa', 'thỉa', 'trỉa', 'vỉa', 'xỉa', 'ĩa', 'cĩa', 'chĩa', 'dĩa', 'đĩa', 'mĩa', 'nĩa', 'ngĩa', 'nghĩa', 'nhĩa', 'quĩa', 'rĩa', 'sĩa', 'tĩa', 'thĩa', 'trĩa', 'vĩa', 'xĩa', 'ía', 'bía', 'cía', 'chía', 'día', 'đía', 'khía', 'mía', 'nía', 'ngía', 'nghía', 'nhía', 'pía', 'quía', 'ría', 'sía', 'tía', 'thía', 'tría', 'vía', 'xía', 'ịa', 'bịa', 'cịa', 'chịa', 'dịa', 'địa', 'khịa', 'lịa', 'mịa', 'nịa', 'ngịa', 'nhịa', 'quịa', 'rịa', 'sịa', 'tịa', 'thịa', 'trịa', 'vịa', 'xịa', 'ic', 'cic', 'chic', 'dic', 'đic', 'mic', 'nic', 'ngic', 'nhic', 'quic', 'ric', 'sic', 'tic', 'thic', 'tric', 'vic', 'xic', 'ìc', 'cìc', 'chìc', 'dìc', 'đìc', 'mìc', 'nìc', 'ngìc', 'nhìc', 'quìc', 'rìc', 'sìc', 'tìc', 'thìc', 'trìc', 'vìc', 'xìc', 'ỉc', 'cỉc', 'chỉc', 'dỉc', 'đỉc', 'mỉc', 'nỉc', 'ngỉc', 'nhỉc', 'quỉc', 'rỉc', 'sỉc', 'tỉc', 'thỉc', 'trỉc', 'vỉc', 'xỉc', 'ĩc', 'cĩc', 'chĩc', 'dĩc', 'đĩc', 'mĩc', 'nĩc', 'ngĩc', 'nhĩc', 'quĩc', 'rĩc', 'sĩc', 'tĩc', 'thĩc', 'trĩc', 'vĩc', 'xĩc', 'íc', 'cíc', 'chíc', 'díc', 'đíc', 'míc', 'níc', 'ngíc', 'nhíc', 'quíc', 'ríc', 'síc', 'tíc', 'thíc', 'tríc', 'víc', 'xíc', 'ịc', 'cịc', 'chịc', 'dịc', 'địc', 'mịc', 'nịc', 'ngịc', 'nhịc', 'quịc', 'rịc', 'sịc', 'tịc', 'thịc', 'trịc', 'vịc', 'xịc', 'im', 'bim', 'cim', 'chim', 'dim', 'đim', 'ghim', 'him', 'kim', 'lim', 'mim', 'nim', 'ngim', 'nhim', 'phim', 'quim', 'rim', 'sim', 'tim', 'thim', 'trim', 'vim', 'xim', 'ìm', 'bìm', 'cìm', 'chìm', 'dìm', 'đìm', 'ghìm', 'kìm', 'lìm', 'mìm', 'nìm', 'ngìm', 'nhìm', 'quìm', 'rìm', 'sìm', 'tìm', 'thìm', 'trìm', 'vìm', 'xìm', 'ỉm', 'bỉm', 'cỉm', 'chỉm', 'dỉm', 'đỉm', 'mỉm', 'nỉm', 'ngỉm', 'nghỉm', 'nhỉm', 'quỉm', 'rỉm', 'sỉm', 'tỉm', 'thỉm', 'trỉm', 'vỉm', 'xỉm', 'ĩm', 'cĩm', 'chĩm', 'dĩm', 'đĩm', 'mĩm', 'nĩm', 'ngĩm', 'nhĩm', 'quĩm', 'rĩm', 'sĩm', 'tĩm', 'thĩm', 'trĩm', 'vĩm', 'xĩm', 'ím', 'bím', 'cím', 'chím', 'dím', 'đím', 'mím', 'ním', 'ngím', 'nhím', 'phím', 'quím', 'rím', 'sím', 'tím', 'thím', 'trím', 'vím', 'xím', 'ịm', 'cịm', 'chịm', 'dịm', 'địm', 'lịm', 'mịm', 'nịm', 'ngịm', 'nhịm', 'quịm', 'rịm', 'sịm', 'tịm', 'thịm', 'trịm', 'vịm', 'xịm', 'in', 'cin', 'chin', 'din', 'đin', 'hin', 'min', 'nin', 'ngin', 'nhin', 'pin', 'phin', 'quin', 'rin', 'sin', 'tin', 'thin', 'trin', 'vin', 'xin', 'ìn', 'cìn', 'chìn', 'dìn', 'đìn', 'gìn', 'kìn', 'khìn', 'mìn', 'nìn', 'ngìn', 'nghìn', 'nhìn', 'quìn', 'rìn', 'sìn', 'tìn', 'thìn', 'trìn', 'vìn', 'xìn', 'ỉn', 'cỉn', 'chỉn', 'dỉn', 'đỉn', 'mỉn', 'nỉn', 'ngỉn', 'nhỉn', 'quỉn', 'rỉn', 'sỉn', 'tỉn', 'thỉn', 'trỉn', 'vỉn', 'xỉn', 'ĩn', 'cĩn', 'chĩn', 'dĩn', 'đĩn', 'mĩn', 'nĩn', 'ngĩn', 'nhĩn', 'quĩn', 'rĩn', 'sĩn', 'tĩn', 'thĩn', 'trĩn', 'vĩn', 'xĩn', 'ín', 'cín', 'chín', 'dín', 'đín', 'kín', 'mín', 'nín', 'ngín', 'nhín', 'quín', 'rín', 'sín', 'tín', 'thín', 'trín', 'vín', 'xín', 'ịn', 'bịn', 'cịn', 'chịn', 'dịn', 'địn', 'mịn', 'nịn', 'ngịn', 'nhịn', 'quịn', 'rịn', 'sịn', 'tịn', 'thịn', 'trịn', 'vịn', 'xịn', 'ip', 'cip', 'chip', 'dip', 'đip', 'mip', 'nip', 'ngip', 'nhip', 'quip', 'rip', 'sip', 'tip', 'thip', 'trip', 'vip', 'xip', 'ìp', 'cìp', 'chìp', 'dìp', 'đìp', 'mìp', 'nìp', 'ngìp', 'nhìp', 'quìp', 'rìp', 'sìp', 'tìp', 'thìp', 'trìp', 'vìp', 'xìp', 'ỉp', 'cỉp', 'chỉp', 'dỉp', 'đỉp', 'mỉp', 'nỉp', 'ngỉp', 'nhỉp', 'quỉp', 'rỉp', 'sỉp', 'tỉp', 'thỉp', 'trỉp', 'vỉp', 'xỉp', 'ĩp', 'cĩp', 'chĩp', 'dĩp', 'đĩp', 'mĩp', 'nĩp', 'ngĩp', 'nhĩp', 'quĩp', 'rĩp', 'sĩp', 'tĩp', 'thĩp', 'trĩp', 'vĩp', 'xĩp', 'íp', 'bíp', 'cíp', 'chíp', 'díp', 'đíp', 'híp', 'kíp', 'líp', 'míp', 'níp', 'ngíp', 'nhíp', 'quíp', 'ríp', 'síp', 'típ', 'thíp', 'tríp', 'víp', 'xíp', 'ịp', 'bịp', 'cịp', 'chịp', 'dịp', 'địp', 'kịp', 'mịp', 'nịp', 'ngịp', 'nhịp', 'quịp', 'rịp', 'sịp', 'tịp', 'thịp', 'trịp', 'vịp', 'xịp', 'it', 'cit', 'chit', 'dit', 'đit', 'mit', 'nit', 'ngit', 'nhit', 'quit', 'rit', 'sit', 'tit', 'thit', 'trit', 'vit', 'xit', 'ìt', 'cìt', 'chìt', 'dìt', 'đìt', 'mìt', 'nìt', 'ngìt', 'nhìt', 'quìt', 'rìt', 'sìt', 'tìt', 'thìt', 'trìt', 'vìt', 'xìt', 'ỉt', 'cỉt', 'chỉt', 'dỉt', 'đỉt', 'mỉt', 'nỉt', 'ngỉt', 'nhỉt', 'quỉt', 'rỉt', 'sỉt', 'tỉt', 'thỉt', 'trỉt', 'vỉt', 'xỉt', 'ĩt', 'cĩt', 'chĩt', 'dĩt', 'đĩt', 'mĩt', 'nĩt', 'ngĩt', 'nhĩt', 'quĩt', 'rĩt', 'sĩt', 'tĩt', 'thĩt', 'trĩt', 'vĩt', 'xĩt', 'ít', 'bít', 'cít', 'chít', 'dít', 'đít', 'hít', 'khít', 'lít', 'mít', 'nít', 'ngít', 'nhít', 'quít', 'rít', 'sít', 'tít', 'thít', 'trít', 'vít', 'xít', 'ịt', 'bịt', 'cịt', 'chịt', 'dịt', 'địt', 'ghịt', 'kịt', 'khịt', 'mịt', 'nịt', 'ngịt', 'nghịt', 'nhịt', 'quịt', 'rịt', 'sịt', 'tịt', 'thịt', 'trịt', 'vịt', 'xịt', 'iu', 'ciu', 'chiu', 'diu', 'điu', 'hiu', 'khiu', 'liu', 'miu', 'niu', 'ngiu', 'nhiu', 'quiu', 'riu', 'siu', 'tiu', 'thiu', 'triu', 'viu', 'xiu', 'ìu', 'bìu', 'cìu', 'chìu', 'dìu', 'đìu', 'mìu', 'nìu', 'ngìu', 'nhìu', 'quìu', 'rìu', 'sìu', 'tìu', 'thìu', 'trìu', 'vìu', 'xìu', 'ỉu', 'cỉu', 'chỉu', 'dỉu', 'đỉu', 'mỉu', 'nỉu', 'ngỉu', 'nhỉu', 'quỉu', 'rỉu', 'sỉu', 'tỉu', 'thỉu', 'trỉu', 'vỉu', 'xỉu', 'ĩu', 'bĩu', 'cĩu', 'chĩu', 'dĩu', 'đĩu', 'kĩu', 'mĩu', 'nĩu', 'ngĩu', 'nhĩu', 'quĩu', 'rĩu', 'sĩu', 'tĩu', 'thĩu', 'trĩu', 'vĩu', 'xĩu', 'íu', 'bíu', 'cíu', 'chíu', 'díu', 'đíu', 'líu', 'míu', 'níu', 'ngíu', 'nhíu', 'quíu', 'ríu', 'síu', 'tíu', 'thíu', 'tríu', 'víu', 'xíu', 'ịu', 'cịu', 'chịu', 'dịu', 'địu', 'lịu', 'mịu', 'nịu', 'ngịu', 'nghịu', 'nhịu', 'quịu', 'rịu', 'sịu', 'tịu', 'thịu', 'trịu', 'vịu', 'xịu', 'oa', 'boa', 'coa', 'choa', 'doa', 'đoa', 'hoa', 'khoa', 'loa', 'moa', 'noa', 'ngoa', 'nhoa', 'quoa', 'roa', 'soa', 'toa', 'thoa', 'troa', 'voa', 'xoa', 'òa', 'còa', 'chòa', 'dòa', 'đòa', 'hòa', 'lòa', 'mòa', 'nòa', 'ngòa', 'nhòa', 'quòa', 'ròa', 'sòa', 'tòa', 'thòa', 'tròa', 'vòa', 'xòa', 'ỏa', 'cỏa', 'chỏa', 'dỏa', 'đỏa', 'hỏa', 'khỏa', 'lỏa', 'mỏa', 'nỏa', 'ngỏa', 'nhỏa', 'quỏa', 'rỏa', 'sỏa', 'tỏa', 'thỏa', 'trỏa', 'vỏa', 'xỏa', 'õa', 'cõa', 'chõa', 'dõa', 'đõa', 'lõa', 'mõa', 'nõa', 'ngõa', 'nhõa', 'quõa', 'rõa', 'sõa', 'tõa', 'thõa', 'trõa', 'võa', 'xõa', 'óa', 'cóa', 'chóa', 'dóa', 'đóa', 'góa', 'hóa', 'khóa', 'lóa', 'móa', 'nóa', 'ngóa', 'nhóa', 'quóa', 'róa', 'sóa', 'tóa', 'thóa', 'tróa', 'vóa', 'xóa', 'ọa', 'cọa', 'chọa', 'dọa', 'đọa', 'họa', 'mọa', 'nọa', 'ngọa', 'nhọa', 'quọa', 'rọa', 'sọa', 'tọa', 'thọa', 'trọa', 'vọa', 'xọa', 'oc', 'coc', 'choc', 'doc', 'đoc', 'moc', 'noc', 'ngoc', 'nhoc', 'quoc', 'roc', 'soc', 'toc', 'thoc', 'troc', 'voc', 'xoc', 'òc', 'còc', 'chòc', 'dòc', 'đòc', 'mòc', 'nòc', 'ngòc', 'nhòc', 'quòc', 'ròc', 'sòc', 'tòc', 'thòc', 'tròc', 'vòc', 'xòc', 'ỏc', 'cỏc', 'chỏc', 'dỏc', 'đỏc', 'mỏc', 'nỏc', 'ngỏc', 'nhỏc', 'quỏc', 'rỏc', 'sỏc', 'tỏc', 'thỏc', 'trỏc', 'vỏc', 'xỏc', 'õc', 'cõc', 'chõc', 'dõc', 'đõc', 'mõc', 'nõc', 'ngõc', 'nhõc', 'quõc', 'rõc', 'sõc', 'tõc', 'thõc', 'trõc', 'võc', 'xõc', 'óc', 'bóc', 'cóc', 'chóc', 'dóc', 'đóc', 'góc', 'hóc', 'khóc', 'lóc', 'móc', 'nóc', 'ngóc', 'nhóc', 'phóc', 'quóc', 'róc', 'sóc', 'tóc', 'thóc', 'tróc', 'vóc', 'xóc', 'ọc', 'bọc', 'cọc', 'chọc', 'dọc', 'đọc', 'học', 'lọc', 'mọc', 'nọc', 'ngọc', 'nhọc', 'quọc', 'rọc', 'sọc', 'tọc', 'thọc', 'trọc', 'vọc', 'xọc', 'oe', 'coe', 'choe', 'doe', 'đoe', 'hoe', 'khoe', 'loe', 'moe', 'noe', 'ngoe', 'nhoe', 'quoe', 'roe', 'soe', 'toe', 'thoe', 'troe', 'voe', 'xoe', 'òe', 'còe', 'chòe', 'dòe', 'đòe', 'hòe', 'lòe', 'mòe', 'nòe', 'ngòe', 'nhòe', 'quòe', 'ròe', 'sòe', 'tòe', 'thòe', 'tròe', 'vòe', 'xòe', 'ỏe', 'cỏe', 'chỏe', 'dỏe', 'đỏe', 'khỏe', 'mỏe', 'nỏe', 'ngỏe', 'nhỏe', 'quỏe', 'rỏe', 'sỏe', 'tỏe', 'thỏe', 'trỏe', 'vỏe', 'xỏe', 'õe', 'cõe', 'chõe', 'dõe', 'đõe', 'mõe', 'nõe', 'ngõe', 'nhõe', 'quõe', 'rõe', 'sõe', 'tõe', 'thõe', 'trõe', 'võe', 'xõe', 'óe', 'cóe', 'chóe', 'dóe', 'đóe', 'khóe', 'lóe', 'móe', 'nóe', 'ngóe', 'nhóe', 'quóe', 'róe', 'sóe', 'tóe', 'thóe', 'tróe', 'vóe', 'xóe', 'ọe', 'cọe', 'chọe', 'dọe', 'đọe', 'họe', 'mọe', 'nọe', 'ngọe', 'nhọe', 'quọe', 'rọe', 'sọe', 'tọe', 'thọe', 'trọe', 'vọe', 'xọe', 'oi', 'coi', 'choi', 'doi', 'đoi', 'hoi', 'loi', 'moi', 'noi', 'ngoi', 'nhoi', 'quoi', 'roi', 'soi', 'toi', 'thoi', 'troi', 'voi', 'xoi', 'òi', 'còi', 'chòi', 'dòi', 'đòi', 'hòi', 'lòi', 'mòi', 'nòi', 'ngòi', 'nhòi', 'quòi', 'ròi', 'sòi', 'tòi', 'thòi', 'tròi', 'vòi', 'xòi', 'ỏi', 'cỏi', 'chỏi', 'dỏi', 'đỏi', 'gỏi', 'giỏi', 'hỏi', 'khỏi', 'lỏi', 'mỏi', 'nỏi', 'ngỏi', 'nhỏi', 'quỏi', 'rỏi', 'sỏi', 'tỏi', 'thỏi', 'trỏi', 'vỏi', 'xỏi', 'õi', 'cõi', 'chõi', 'dõi', 'đõi', 'lõi', 'mõi', 'nõi', 'ngõi', 'nhõi', 'quõi', 'rõi', 'sõi', 'tõi', 'thõi', 'trõi', 'või', 'xõi', 'ói', 'bói', 'cói', 'chói', 'dói', 'đói', 'gói', 'hói', 'khói', 'lói', 'mói', 'nói', 'ngói', 'nhói', 'quói', 'rói', 'sói', 'tói', 'thói', 'trói', 'vói', 'xói', 'ọi', 'cọi', 'chọi', 'dọi', 'đọi', 'gọi', 'lọi', 'mọi', 'nọi', 'ngọi', 'nhọi', 'quọi', 'rọi', 'sọi', 'tọi', 'thọi', 'trọi', 'vọi', 'xọi', 'om', 'bom', 'com', 'chom', 'dom', 'đom', 'gom', 'hom', 'khom', 'lom', 'mom', 'nom', 'ngom', 'nhom', 'phom', 'quom', 'rom', 'som', 'tom', 'thom', 'trom', 'vom', 'xom', 'òm', 'còm', 'chòm', 'dòm', 'đòm', 'hòm', 'khòm', 'lòm', 'mòm', 'nòm', 'ngòm', 'nhòm', 'quòm', 'ròm', 'sòm', 'tòm', 'thòm', 'tròm', 'vòm', 'xòm', 'ỏm', 'bỏm', 'cỏm', 'chỏm', 'dỏm', 'đỏm', 'hỏm', 'mỏm', 'nỏm', 'ngỏm', 'nhỏm', 'phỏm', 'quỏm', 'rỏm', 'sỏm', 'tỏm', 'thỏm', 'trỏm', 'vỏm', 'xỏm', 'õm', 'bõm', 'cõm', 'chõm', 'dõm', 'đõm', 'hõm', 'lõm', 'mõm', 'nõm', 'ngõm', 'nhõm', 'quõm', 'rõm', 'sõm', 'tõm', 'thõm', 'trõm', 'võm', 'xõm', 'óm', 'cóm', 'chóm', 'dóm', 'đóm', 'hóm', 'khóm', 'lóm', 'móm', 'nóm', 'ngóm', 'nhóm', 'quóm', 'róm', 'sóm', 'tóm', 'thóm', 'tróm', 'vóm', 'xóm', 'ọm', 'cọm', 'chọm', 'dọm', 'đọm', 'khọm', 'lọm', 'mọm', 'nọm', 'ngọm', 'nhọm', 'quọm', 'rọm', 'sọm', 'tọm', 'thọm', 'trọm', 'vọm', 'xọm', 'on', 'bon', 'con', 'chon', 'don', 'đon', 'gon', 'gion', 'hon', 'lon', 'mon', 'non', 'ngon', 'nhon', 'quon', 'ron', 'son', 'ton', 'thon', 'tron', 'von', 'xon', 'òn', 'bòn', 'còn', 'chòn', 'dòn', 'đòn', 'gòn', 'giòn', 'hòn', 'mòn', 'nòn', 'ngòn', 'nhòn', 'quòn', 'ròn', 'sòn', 'tòn', 'thòn', 'tròn', 'vòn', 'xòn', 'ỏn', 'cỏn', 'chỏn', 'dỏn', 'đỏn', 'giỏn', 'hỏn', 'lỏn', 'mỏn', 'nỏn', 'ngỏn', 'nhỏn', 'quỏn', 'rỏn', 'sỏn', 'tỏn', 'thỏn', 'trỏn', 'vỏn', 'xỏn', 'õn', 'cõn', 'chõn', 'dõn', 'đõn', 'mõn', 'nõn', 'ngõn', 'nhõn', 'quõn', 'rõn', 'sõn', 'tõn', 'thõn', 'trõn', 'võn', 'xõn', 'ón', 'bón', 'cón', 'chón', 'dón', 'đón', 'món', 'nón', 'ngón', 'nhón', 'quón', 'rón', 'són', 'tón', 'thón', 'trón', 'vón', 'xón', 'ọn', 'bọn', 'cọn', 'chọn', 'dọn', 'đọn', 'gọn', 'lọn', 'mọn', 'nọn', 'ngọn', 'nhọn', 'quọn', 'rọn', 'sọn', 'tọn', 'thọn', 'trọn', 'vọn', 'xọn', 'op', 'cop', 'chop', 'dop', 'đop', 'mop', 'nop', 'ngop', 'nhop', 'quop', 'rop', 'sop', 'top', 'thop', 'trop', 'vop', 'xop', 'òp', 'còp', 'chòp', 'dòp', 'đòp', 'mòp', 'nòp', 'ngòp', 'nhòp', 'quòp', 'ròp', 'sòp', 'tòp', 'thòp', 'tròp', 'vòp', 'xòp', 'ỏp', 'cỏp', 'chỏp', 'dỏp', 'đỏp', 'mỏp', 'nỏp', 'ngỏp', 'nhỏp', 'quỏp', 'rỏp', 'sỏp', 'tỏp', 'thỏp', 'trỏp', 'vỏp', 'xỏp', 'õp', 'cõp', 'chõp', 'dõp', 'đõp', 'mõp', 'nõp', 'ngõp', 'nhõp', 'quõp', 'rõp', 'sõp', 'tõp', 'thõp', 'trõp', 'võp', 'xõp', 'óp', 'bóp', 'cóp', 'chóp', 'dóp', 'đóp', 'góp', 'hóp', 'lóp', 'móp', 'nóp', 'ngóp', 'nhóp', 'quóp', 'róp', 'sóp', 'tóp', 'thóp', 'tróp', 'vóp', 'xóp', 'ọp', 'cọp', 'chọp', 'dọp', 'đọp', 'họp', 'lọp', 'mọp', 'nọp', 'ngọp', 'nhọp', 'quọp', 'rọp', 'sọp', 'tọp', 'thọp', 'trọp', 'vọp', 'xọp', 'ot', 'cot', 'chot', 'dot', 'đot', 'mot', 'not', 'ngot', 'nhot', 'quot', 'rot', 'sot', 'tot', 'thot', 'trot', 'vot', 'xot', 'òt', 'còt', 'chòt', 'dòt', 'đòt', 'mòt', 'nòt', 'ngòt', 'nhòt', 'quòt', 'ròt', 'sòt', 'tòt', 'thòt', 'tròt', 'vòt', 'xòt', 'ỏt', 'cỏt', 'chỏt', 'dỏt', 'đỏt', 'mỏt', 'nỏt', 'ngỏt', 'nhỏt', 'quỏt', 'rỏt', 'sỏt', 'tỏt', 'thỏt', 'trỏt', 'vỏt', 'xỏt', 'õt', 'cõt', 'chõt', 'dõt', 'đõt', 'mõt', 'nõt', 'ngõt', 'nhõt', 'quõt', 'rõt', 'sõt', 'tõt', 'thõt', 'trõt', 'võt', 'xõt', 'ót', 'bót', 'cót', 'chót', 'dót', 'đót', 'gót', 'hót', 'lót', 'mót', 'nót', 'ngót', 'nhót', 'quót', 'rót', 'sót', 'tót', 'thót', 'trót', 'vót', 'xót', 'ọt', 'bọt', 'cọt', 'chọt', 'dọt', 'đọt', 'gọt', 'giọt', 'khọt', 'lọt', 'mọt', 'nọt', 'ngọt', 'nhọt', 'phọt', 'quọt', 'rọt', 'sọt', 'tọt', 'thọt', 'trọt', 'vọt', 'xọt', 'ôc', 'côc', 'chôc', 'dôc', 'đôc', 'môc', 'nôc', 'ngôc', 'nhôc', 'quôc', 'rôc', 'sôc', 'tôc', 'thôc', 'trôc', 'vôc', 'xôc', 'ồc', 'cồc', 'chồc', 'dồc', 'đồc', 'mồc', 'nồc', 'ngồc', 'nhồc', 'quồc', 'rồc', 'sồc', 'tồc', 'thồc', 'trồc', 'vồc', 'xồc', 'ổc', 'cổc', 'chổc', 'dổc', 'đổc', 'mổc', 'nổc', 'ngổc', 'nhổc', 'quổc', 'rổc', 'sổc', 'tổc', 'thổc', 'trổc', 'vổc', 'xổc', 'ỗc', 'cỗc', 'chỗc', 'dỗc', 'đỗc', 'mỗc', 'nỗc', 'ngỗc', 'nhỗc', 'quỗc', 'rỗc', 'sỗc', 'tỗc', 'thỗc', 'trỗc', 'vỗc', 'xỗc', 'ốc', 'bốc', 'cốc', 'chốc', 'dốc', 'đốc', 'gốc', 'hốc', 'khốc', 'lốc', 'mốc', 'nốc', 'ngốc', 'nhốc', 'phốc', 'quốc', 'rốc', 'sốc', 'tốc', 'thốc', 'trốc', 'vốc', 'xốc', 'ộc', 'bộc', 'cộc', 'chộc', 'dộc', 'độc', 'gộc', 'hộc', 'lộc', 'mộc', 'nộc', 'ngộc', 'nhộc', 'quộc', 'rộc', 'sộc', 'tộc', 'thộc', 'trộc', 'vộc', 'xộc', 'ôi', 'bôi', 'côi', 'chôi', 'dôi', 'đôi', 'hôi', 'khôi', 'lôi', 'môi', 'nôi', 'ngôi', 'nhôi', 'phôi', 'quôi', 'rôi', 'sôi', 'tôi', 'thôi', 'trôi', 'vôi', 'xôi', 'ồi', 'bồi', 'cồi', 'chồi', 'dồi', 'đồi', 'gồi', 'hồi', 'lồi', 'mồi', 'nồi', 'ngồi', 'nhồi', 'quồi', 'rồi', 'sồi', 'tồi', 'thồi', 'trồi', 'vồi', 'xồi', 'ổi', 'bổi', 'cổi', 'chổi', 'dổi', 'đổi', 'hổi', 'mổi', 'nổi', 'ngổi', 'nhổi', 'phổi', 'quổi', 'rổi', 'sổi', 'tổi', 'thổi', 'trổi', 'vổi', 'xổi', 'ỗi', 'cỗi', 'chỗi', 'dỗi', 'đỗi', 'lỗi', 'mỗi', 'nỗi', 'ngỗi', 'nhỗi', 'quỗi', 'rỗi', 'sỗi', 'tỗi', 'thỗi', 'trỗi', 'vỗi', 'xỗi', 'ối', 'bối', 'cối', 'chối', 'dối', 'đối', 'gối', 'hối', 'khối', 'lối', 'mối', 'nối', 'ngối', 'nhối', 'phối', 'quối', 'rối', 'sối', 'tối', 'thối', 'trối', 'vối', 'xối', 'ội', 'bội', 'cội', 'chội', 'dội', 'đội', 'gội', 'hội', 'lội', 'mội', 'nội', 'ngội', 'nhội', 'quội', 'rội', 'sội', 'tội', 'thội', 'trội', 'vội', 'xội', 'ôm', 'côm', 'chôm', 'dôm', 'đôm', 'gôm', 'hôm', 'lôm', 'môm', 'nôm', 'ngôm', 'nhôm', 'phôm', 'quôm', 'rôm', 'sôm', 'tôm', 'thôm', 'trôm', 'vôm', 'xôm', 'ồm', 'cồm', 'chồm', 'dồm', 'đồm', 'gồm', 'lồm', 'mồm', 'nồm', 'ngồm', 'nhồm', 'quồm', 'rồm', 'sồm', 'tồm', 'thồm', 'trồm', 'vồm', 'xồm', 'ổm', 'cổm', 'chổm', 'dổm', 'đổm', 'hổm', 'lổm', 'mổm', 'nổm', 'ngổm', 'nhổm', 'quổm', 'rổm', 'sổm', 'tổm', 'thổm', 'trổm', 'vổm', 'xổm', 'ỗm', 'cỗm', 'chỗm', 'dỗm', 'đỗm', 'mỗm', 'nỗm', 'ngỗm', 'nhỗm', 'quỗm', 'rỗm', 'sỗm', 'tỗm', 'thỗm', 'trỗm', 'vỗm', 'xỗm', 'ốm', 'cốm', 'chốm', 'dốm', 'đốm', 'gốm', 'lốm', 'mốm', 'nốm', 'ngốm', 'nhốm', 'quốm', 'rốm', 'sốm', 'tốm', 'thốm', 'trốm', 'vốm', 'xốm', 'ộm', 'cộm', 'chộm', 'dộm', 'độm', 'lộm', 'mộm', 'nộm', 'ngộm', 'nhộm', 'quộm', 'rộm', 'sộm', 'tộm', 'thộm', 'trộm', 'vộm', 'xộm', 'ôn', 'bôn', 'côn', 'chôn', 'dôn', 'đôn', 'gôn', 'hôn', 'khôn', 'môn', 'nôn', 'ngôn', 'nhôn', 'quôn', 'rôn', 'sôn', 'tôn', 'thôn', 'trôn', 'vôn', 'xôn', 'ồn', 'bồn', 'cồn', 'chồn', 'dồn', 'đồn', 'hồn', 'lồn', 'mồn', 'nồn', 'ngồn', 'nhồn', 'phồn', 'quồn', 'rồn', 'sồn', 'tồn', 'thồn', 'trồn', 'vồn', 'xồn', 'ổn', 'bổn', 'cổn', 'chổn', 'dổn', 'đổn', 'hổn', 'lổn', 'mổn', 'nổn', 'ngổn', 'nhổn', 'quổn', 'rổn', 'sổn', 'tổn', 'thổn', 'trổn', 'vổn', 'xổn', 'ỗn', 'cỗn', 'chỗn', 'dỗn', 'đỗn', 'hỗn', 'mỗn', 'nỗn', 'ngỗn', 'nhỗn', 'quỗn', 'rỗn', 'sỗn', 'tỗn', 'thỗn', 'trỗn', 'vỗn', 'xỗn', 'ốn', 'bốn', 'cốn', 'chốn', 'dốn', 'đốn', 'khốn', 'lốn', 'mốn', 'nốn', 'ngốn', 'nhốn', 'quốn', 'rốn', 'sốn', 'tốn', 'thốn', 'trốn', 'vốn', 'xốn', 'ộn', 'bộn', 'cộn', 'chộn', 'dộn', 'độn', 'lộn', 'mộn', 'nộn', 'ngộn', 'nhộn', 'quộn', 'rộn', 'sộn', 'tộn', 'thộn', 'trộn', 'vộn', 'xộn', 'ôp', 'côp', 'chôp', 'dôp', 'đôp', 'môp', 'nôp', 'ngôp', 'nhôp', 'quôp', 'rôp', 'sôp', 'tôp', 'thôp', 'trôp', 'vôp', 'xôp', 'ồp', 'cồp', 'chồp', 'dồp', 'đồp', 'mồp', 'nồp', 'ngồp', 'nhồp', 'quồp', 'rồp', 'sồp', 'tồp', 'thồp', 'trồp', 'vồp', 'xồp', 'ổp', 'cổp', 'chổp', 'dổp', 'đổp', 'mổp', 'nổp', 'ngổp', 'nhổp', 'quổp', 'rổp', 'sổp', 'tổp', 'thổp', 'trổp', 'vổp', 'xổp', 'ỗp', 'cỗp', 'chỗp', 'dỗp', 'đỗp', 'mỗp', 'nỗp', 'ngỗp', 'nhỗp', 'quỗp', 'rỗp', 'sỗp', 'tỗp', 'thỗp', 'trỗp', 'vỗp', 'xỗp', 'ốp', 'bốp', 'cốp', 'chốp', 'dốp', 'đốp', 'lốp', 'mốp', 'nốp', 'ngốp', 'nhốp', 'phốp', 'quốp', 'rốp', 'sốp', 'tốp', 'thốp', 'trốp', 'vốp', 'xốp', 'ộp', 'bộp', 'cộp', 'chộp', 'dộp', 'độp', 'gộp', 'hộp', 'lộp', 'mộp', 'nộp', 'ngộp', 'nhộp', 'quộp', 'rộp', 'sộp', 'tộp', 'thộp', 'trộp', 'vộp', 'xộp', 'ôt', 'côt', 'chôt', 'dôt', 'đôt', 'môt', 'nôt', 'ngôt', 'nhôt', 'quôt', 'rôt', 'sôt', 'tôt', 'thôt', 'trôt', 'vôt', 'xôt', 'ồt', 'cồt', 'chồt', 'dồt', 'đồt', 'mồt', 'nồt', 'ngồt', 'nhồt', 'quồt', 'rồt', 'sồt', 'tồt', 'thồt', 'trồt', 'vồt', 'xồt', 'ổt', 'cổt', 'chổt', 'dổt', 'đổt', 'mổt', 'nổt', 'ngổt', 'nhổt', 'quổt', 'rổt', 'sổt', 'tổt', 'thổt', 'trổt', 'vổt', 'xổt', 'ỗt', 'cỗt', 'chỗt', 'dỗt', 'đỗt', 'mỗt', 'nỗt', 'ngỗt', 'nhỗt', 'quỗt', 'rỗt', 'sỗt', 'tỗt', 'thỗt', 'trỗt', 'vỗt', 'xỗt', 'ốt', 'bốt', 'cốt', 'chốt', 'dốt', 'đốt', 'hốt', 'lốt', 'mốt', 'nốt', 'ngốt', 'nhốt', 'phốt', 'quốt', 'rốt', 'sốt', 'tốt', 'thốt', 'trốt', 'vốt', 'xốt', 'ột', 'bột', 'cột', 'chột', 'dột', 'đột', 'gột', 'hột', 'lột', 'một', 'nột', 'ngột', 'nhột', 'quột', 'rột', 'sột', 'tột', 'thột', 'trột', 'vột', 'xột', 'ơi', 'bơi', 'cơi', 'chơi', 'dơi', 'đơi', 'hơi', 'khơi', 'lơi', 'mơi', 'nơi', 'ngơi', 'nhơi', 'phơi', 'quơi', 'rơi', 'sơi', 'tơi', 'thơi', 'trơi', 'vơi', 'xơi', 'ời', 'bời', 'cời', 'chời', 'dời', 'đời', 'giời', 'hời', 'lời', 'mời', 'nời', 'ngời', 'nhời', 'quời', 'rời', 'sời', 'tời', 'thời', 'trời', 'vời', 'xời', 'ởi', 'bởi', 'cởi', 'chởi', 'dởi', 'đởi', 'hởi', 'khởi', 'mởi', 'nởi', 'ngởi', 'nhởi', 'quởi', 'rởi', 'sởi', 'tởi', 'thởi', 'trởi', 'vởi', 'xởi', 'ỡi', 'cỡi', 'chỡi', 'dỡi', 'đỡi', 'hỡi', 'mỡi', 'nỡi', 'ngỡi', 'nhỡi', 'quỡi', 'rỡi', 'sỡi', 'tỡi', 'thỡi', 'trỡi', 'vỡi', 'xỡi', 'ới', 'bới', 'cới', 'chới', 'dới', 'đới', 'giới', 'hới', 'lới', 'mới', 'nới', 'ngới', 'nhới', 'phới', 'quới', 'rới', 'sới', 'tới', 'thới', 'trới', 'với', 'xới', 'ợi', 'cợi', 'chợi', 'dợi', 'đợi', 'gợi', 'hợi', 'lợi', 'mợi', 'nợi', 'ngợi', 'nhợi', 'quợi', 'rợi', 'sợi', 'tợi', 'thợi', 'trợi', 'vợi', 'xợi', 'ơm', 'bơm', 'cơm', 'chơm', 'dơm', 'đơm', 'mơm', 'nơm', 'ngơm', 'nhơm', 'quơm', 'rơm', 'sơm', 'tơm', 'thơm', 'trơm', 'vơm', 'xơm', 'ờm', 'bờm', 'cờm', 'chờm', 'dờm', 'đờm', 'gờm', 'lờm', 'mờm', 'nờm', 'ngờm', 'nhờm', 'quờm', 'rờm', 'sờm', 'tờm', 'thờm', 'trờm', 'vờm', 'xờm', 'ởm', 'cởm', 'chởm', 'dởm', 'đởm', 'lởm', 'mởm', 'nởm', 'ngởm', 'nhởm', 'quởm', 'rởm', 'sởm', 'tởm', 'thởm', 'trởm', 'vởm', 'xởm', 'ỡm', 'cỡm', 'chỡm', 'dỡm', 'đỡm', 'lỡm', 'mỡm', 'nỡm', 'ngỡm', 'nhỡm', 'quỡm', 'rỡm', 'sỡm', 'tỡm', 'thỡm', 'trỡm', 'vỡm', 'xỡm', 'ớm', 'cớm', 'chớm', 'dớm', 'đớm', 'gớm', 'mớm', 'nớm', 'ngớm', 'nhớm', 'quớm', 'rớm', 'sớm', 'tớm', 'thớm', 'trớm', 'vớm', 'xớm', 'ợm', 'bợm', 'cợm', 'chợm', 'dợm', 'đợm', 'hợm', 'lợm', 'mợm', 'nợm', 'ngợm', 'nhợm', 'quợm', 'rợm', 'sợm', 'tợm', 'thợm', 'trợm', 'vợm', 'xợm', 'ơn', 'bơn', 'cơn', 'chơn', 'dơn', 'đơn', 'hơn', 'mơn', 'nơn', 'ngơn', 'nhơn', 'phơn', 'quơn', 'rơn', 'sơn', 'tơn', 'thơn', 'trơn', 'vơn', 'xơn', 'ờn', 'cờn', 'chờn', 'dờn', 'đờn', 'gờn', 'giờn', 'hờn', 'mờn', 'nờn', 'ngờn', 'nhờn', 'quờn', 'rờn', 'sờn', 'tờn', 'thờn', 'trờn', 'vờn', 'xờn', 'ởn', 'cởn', 'chởn', 'dởn', 'đởn', 'lởn', 'mởn', 'nởn', 'ngởn', 'nhởn', 'phởn', 'quởn', 'rởn', 'sởn', 'tởn', 'thởn', 'trởn', 'vởn', 'xởn', 'ỡn', 'bỡn', 'cỡn', 'chỡn', 'dỡn', 'đỡn', 'giỡn', 'mỡn', 'nỡn', 'ngỡn', 'nhỡn', 'phỡn', 'quỡn', 'rỡn', 'sỡn', 'tỡn', 'thỡn', 'trỡn', 'vỡn', 'xỡn', 'ớn', 'cớn', 'chớn', 'dớn', 'đớn', 'hớn', 'lớn', 'mớn', 'nớn', 'ngớn', 'nhớn', 'phớn', 'quớn', 'rớn', 'sớn', 'tớn', 'thớn', 'trớn', 'vớn', 'xớn', 'ợn', 'bợn', 'cợn', 'chợn', 'dợn', 'đợn', 'gợn', 'lợn', 'mợn', 'nợn', 'ngợn', 'nhợn', 'quợn', 'rợn', 'sợn', 'tợn', 'thợn', 'trợn', 'vợn', 'xợn', 'ơp', 'cơp', 'chơp', 'dơp', 'đơp', 'mơp', 'nơp', 'ngơp', 'nhơp', 'quơp', 'rơp', 'sơp', 'tơp', 'thơp', 'trơp', 'vơp', 'xơp', 'ờp', 'cờp', 'chờp', 'dờp', 'đờp', 'mờp', 'nờp', 'ngờp', 'nhờp', 'quờp', 'rờp', 'sờp', 'tờp', 'thờp', 'trờp', 'vờp', 'xờp', 'ởp', 'cởp', 'chởp', 'dởp', 'đởp', 'mởp', 'nởp', 'ngởp', 'nhởp', 'quởp', 'rởp', 'sởp', 'tởp', 'thởp', 'trởp', 'vởp', 'xởp', 'ỡp', 'cỡp', 'chỡp', 'dỡp', 'đỡp', 'mỡp', 'nỡp', 'ngỡp', 'nhỡp', 'quỡp', 'rỡp', 'sỡp', 'tỡp', 'thỡp', 'trỡp', 'vỡp', 'xỡp', 'ớp', 'bớp', 'cớp', 'chớp', 'dớp', 'đớp', 'hớp', 'khớp', 'lớp', 'mớp', 'nớp', 'ngớp', 'nhớp', 'quớp', 'rớp', 'sớp', 'tớp', 'thớp', 'trớp', 'vớp', 'xớp', 'ợp', 'bợp', 'cợp', 'chợp', 'dợp', 'đợp', 'hợp', 'lợp', 'mợp', 'nợp', 'ngợp', 'nhợp', 'quợp', 'rợp', 'sợp', 'tợp', 'thợp', 'trợp', 'vợp', 'xợp', 'ơt', 'cơt', 'chơt', 'dơt', 'đơt', 'mơt', 'nơt', 'ngơt', 'nhơt', 'quơt', 'rơt', 'sơt', 'tơt', 'thơt', 'trơt', 'vơt', 'xơt', 'ờt', 'cờt', 'chờt', 'dờt', 'đờt', 'mờt', 'nờt', 'ngờt', 'nhờt', 'quờt', 'rờt', 'sờt', 'tờt', 'thờt', 'trờt', 'vờt', 'xờt', 'ởt', 'cởt', 'chởt', 'dởt', 'đởt', 'mởt', 'nởt', 'ngởt', 'nhởt', 'quởt', 'rởt', 'sởt', 'tởt', 'thởt', 'trởt', 'vởt', 'xởt', 'ỡt', 'cỡt', 'chỡt', 'dỡt', 'đỡt', 'mỡt', 'nỡt', 'ngỡt', 'nhỡt', 'quỡt', 'rỡt', 'sỡt', 'tỡt', 'thỡt', 'trỡt', 'vỡt', 'xỡt', 'ớt', 'bớt', 'cớt', 'chớt', 'dớt', 'đớt', 'hớt', 'lớt', 'mớt', 'nớt', 'ngớt', 'nhớt', 'phớt', 'quớt', 'rớt', 'sớt', 'tớt', 'thớt', 'trớt', 'vớt', 'xớt', 'ợt', 'bợt', 'cợt', 'chợt', 'dợt', 'đợt', 'gợt', 'hợt', 'lợt', 'mợt', 'nợt', 'ngợt', 'nhợt', 'quợt', 'rợt', 'sợt', 'tợt', 'thợt', 'trợt', 'vợt', 'xợt', 'ua', 'bua', 'cua', 'chua', 'dua', 'đua', 'khua', 'lua', 'mua', 'nua', 'ngua', 'nhua', 'quua', 'rua', 'sua', 'tua', 'thua', 'trua', 'vua', 'xua', 'ùa', 'bùa', 'cùa', 'chùa', 'dùa', 'đùa', 'hùa', 'lùa', 'mùa', 'nùa', 'ngùa', 'nhùa', 'quùa', 'rùa', 'sùa', 'tùa', 'thùa', 'trùa', 'vùa', 'xùa', 'ủa', 'bủa', 'của', 'chủa', 'dủa', 'đủa', 'mủa', 'nủa', 'ngủa', 'nhủa', 'quủa', 'rủa', 'sủa', 'tủa', 'thủa', 'trủa', 'vủa', 'xủa', 'ũa', 'cũa', 'chũa', 'dũa', 'đũa', 'giũa', 'lũa', 'mũa', 'nũa', 'ngũa', 'nhũa', 'quũa', 'rũa', 'sũa', 'tũa', 'thũa', 'trũa', 'vũa', 'xũa', 'úa', 'búa', 'cúa', 'chúa', 'dúa', 'đúa', 'lúa', 'múa', 'núa', 'ngúa', 'nhúa', 'quúa', 'rúa', 'súa', 'túa', 'thúa', 'trúa', 'vúa', 'xúa', 'ụa', 'cụa', 'chụa', 'dụa', 'đụa', 'giụa', 'lụa', 'mụa', 'nụa', 'ngụa', 'nhụa', 'quụa', 'rụa', 'sụa', 'tụa', 'thụa', 'trụa', 'vụa', 'xụa', 'uc', 'cuc', 'chuc', 'duc', 'đuc', 'muc', 'nuc', 'nguc', 'nhuc', 'quuc', 'ruc', 'suc', 'tuc', 'thuc', 'truc', 'vuc', 'xuc', 'ùc', 'cùc', 'chùc', 'dùc', 'đùc', 'mùc', 'nùc', 'ngùc', 'nhùc', 'quùc', 'rùc', 'sùc', 'tùc', 'thùc', 'trùc', 'vùc', 'xùc', 'ủc', 'củc', 'chủc', 'dủc', 'đủc', 'mủc', 'nủc', 'ngủc', 'nhủc', 'quủc', 'rủc', 'sủc', 'tủc', 'thủc', 'trủc', 'vủc', 'xủc', 'ũc', 'cũc', 'chũc', 'dũc', 'đũc', 'mũc', 'nũc', 'ngũc', 'nhũc', 'quũc', 'rũc', 'sũc', 'tũc', 'thũc', 'trũc', 'vũc', 'xũc', 'úc', 'cúc', 'chúc', 'dúc', 'đúc', 'húc', 'khúc', 'lúc', 'múc', 'núc', 'ngúc', 'nhúc', 'phúc', 'quúc', 'rúc', 'súc', 'túc', 'thúc', 'trúc', 'vúc', 'xúc', 'ục', 'bục', 'cục', 'chục', 'dục', 'đục', 'gục', 'giục', 'hục', 'lục', 'mục', 'nục', 'ngục', 'nhục', 'phục', 'quục', 'rục', 'sục', 'tục', 'thục', 'trục', 'vục', 'xục', 'uê', 'cuê', 'chuê', 'duê', 'đuê', 'khuê', 'muê', 'nuê', 'nguê', 'nhuê', 'quuê', 'ruê', 'suê', 'tuê', 'thuê', 'truê', 'vuê', 'xuê', 'uề', 'cuề', 'chuề', 'duề', 'đuề', 'huề', 'muề', 'nuề', 'nguề', 'nhuề', 'quuề', 'ruề', 'suề', 'tuề', 'thuề', 'truề', 'vuề', 'xuề', 'uể', 'cuể', 'chuể', 'duể', 'đuể', 'muể', 'nuể', 'nguể', 'nhuể', 'quuể', 'ruể', 'suể', 'tuể', 'thuể', 'truể', 'vuể', 'xuể', 'uễ', 'cuễ', 'chuễ', 'duễ', 'đuễ', 'muễ', 'nuễ', 'nguễ', 'nhuễ', 'quuễ', 'ruễ', 'suễ', 'tuễ', 'thuễ', 'truễ', 'vuễ', 'xuễ', 'uế', 'cuế', 'chuế', 'duế', 'đuế', 'muế', 'nuế', 'nguế', 'nhuế', 'quuế', 'ruế', 'suế', 'tuế', 'thuế', 'truế', 'vuế', 'xuế', 'uệ', 'cuệ', 'chuệ', 'duệ', 'đuệ', 'huệ', 'muệ', 'nuệ', 'nguệ', 'nhuệ', 'quuệ', 'ruệ', 'suệ', 'tuệ', 'thuệ', 'truệ', 'vuệ', 'xuệ', 'ui', 'cui', 'chui', 'dui', 'đui', 'khui', 'lui', 'mui', 'nui', 'ngui', 'nhui', 'phui', 'quui', 'rui', 'sui', 'tui', 'thui', 'trui', 'vui', 'xui', 'ùi', 'bùi', 'cùi', 'chùi', 'dùi', 'đùi', 'gùi', 'hùi', 'lùi', 'mùi', 'nùi', 'ngùi', 'nhùi', 'quùi', 'rùi', 'sùi', 'tùi', 'thùi', 'trùi', 'vùi', 'xùi', 'ủi', 'củi', 'chủi', 'dủi', 'đủi', 'hủi', 'lủi', 'mủi', 'nủi', 'ngủi', 'nhủi', 'phủi', 'quủi', 'rủi', 'sủi', 'tủi', 'thủi', 'trủi', 'vủi', 'xủi', 'ũi', 'cũi', 'chũi', 'dũi', 'đũi', 'gũi', 'lũi', 'mũi', 'nũi', 'ngũi', 'nhũi', 'quũi', 'rũi', 'sũi', 'tũi', 'thũi', 'trũi', 'vũi', 'xũi', 'úi', 'búi', 'cúi', 'chúi', 'dúi', 'đúi', 'giúi', 'húi', 'lúi', 'múi', 'núi', 'ngúi', 'nhúi', 'quúi', 'rúi', 'súi', 'túi', 'thúi', 'trúi', 'vúi', 'xúi', 'ụi', 'bụi', 'cụi', 'chụi', 'dụi', 'đụi', 'hụi', 'lụi', 'mụi', 'nụi', 'ngụi', 'nhụi', 'quụi', 'rụi', 'sụi', 'tụi', 'thụi', 'trụi', 'vụi', 'xụi', 'um', 'cum', 'chum', 'dum', 'đum', 'hum', 'khum', 'lum', 'mum', 'num', 'ngum', 'nhum', 'quum', 'rum', 'sum', 'tum', 'thum', 'trum', 'vum', 'xum', 'ùm', 'bùm', 'cùm', 'chùm', 'dùm', 'đùm', 'giùm', 'hùm', 'lùm', 'mùm', 'nùm', 'ngùm', 'nhùm', 'quùm', 'rùm', 'sùm', 'tùm', 'thùm', 'trùm', 'vùm', 'xùm', 'ủm', 'bủm', 'củm', 'chủm', 'dủm', 'đủm', 'lủm', 'mủm', 'nủm', 'ngủm', 'nhủm', 'quủm', 'rủm', 'sủm', 'tủm', 'thủm', 'trủm', 'vủm', 'xủm', 'ũm', 'cũm', 'chũm', 'dũm', 'đũm', 'lũm', 'mũm', 'nũm', 'ngũm', 'nhũm', 'quũm', 'rũm', 'sũm', 'tũm', 'thũm', 'trũm', 'vũm', 'xũm', 'úm', 'cúm', 'chúm', 'dúm', 'đúm', 'húm', 'khúm', 'lúm', 'múm', 'núm', 'ngúm', 'nhúm', 'quúm', 'rúm', 'súm', 'túm', 'thúm', 'trúm', 'vúm', 'xúm', 'ụm', 'bụm', 'cụm', 'chụm', 'dụm', 'đụm', 'lụm', 'mụm', 'nụm', 'ngụm', 'nhụm', 'quụm', 'rụm', 'sụm', 'tụm', 'thụm', 'trụm', 'vụm', 'xụm', 'un', 'cun', 'chun', 'dun', 'đun', 'giun', 'hun', 'mun', 'nun', 'ngun', 'nhun', 'phun', 'quun', 'run', 'sun', 'tun', 'thun', 'trun', 'vun', 'xun', 'ùn', 'bùn', 'cùn', 'chùn', 'dùn', 'đùn', 'hùn', 'lùn', 'mùn', 'nùn', 'ngùn', 'nhùn', 'phùn', 'quùn', 'rùn', 'sùn', 'tùn', 'thùn', 'trùn', 'vùn', 'xùn', 'ủn', 'bủn', 'củn', 'chủn', 'dủn', 'đủn', 'hủn', 'lủn', 'mủn', 'nủn', 'ngủn', 'nhủn', 'quủn', 'rủn', 'sủn', 'tủn', 'thủn', 'trủn', 'vủn', 'xủn', 'ũn', 'cũn', 'chũn', 'dũn', 'đũn', 'lũn', 'mũn', 'nũn', 'ngũn', 'nhũn', 'quũn', 'rũn', 'sũn', 'tũn', 'thũn', 'trũn', 'vũn', 'xũn', 'ún', 'bún', 'cún', 'chún', 'dún', 'đún', 'lún', 'mún', 'nún', 'ngún', 'nhún', 'phún', 'quún', 'rún', 'sún', 'tún', 'thún', 'trún', 'vún', 'xún', 'ụn', 'cụn', 'chụn', 'dụn', 'đụn', 'lụn', 'mụn', 'nụn', 'ngụn', 'nhụn', 'quụn', 'rụn', 'sụn', 'tụn', 'thụn', 'trụn', 'vụn', 'xụn', 'up', 'cup', 'chup', 'dup', 'đup', 'mup', 'nup', 'ngup', 'nhup', 'quup', 'rup', 'sup', 'tup', 'thup', 'trup', 'vup', 'xup', 'ùp', 'cùp', 'chùp', 'dùp', 'đùp', 'mùp', 'nùp', 'ngùp', 'nhùp', 'quùp', 'rùp', 'sùp', 'tùp', 'thùp', 'trùp', 'vùp', 'xùp', 'ủp', 'củp', 'chủp', 'dủp', 'đủp', 'mủp', 'nủp', 'ngủp', 'nhủp', 'quủp', 'rủp', 'sủp', 'tủp', 'thủp', 'trủp', 'vủp', 'xủp', 'ũp', 'cũp', 'chũp', 'dũp', 'đũp', 'mũp', 'nũp', 'ngũp', 'nhũp', 'quũp', 'rũp', 'sũp', 'tũp', 'thũp', 'trũp', 'vũp', 'xũp', 'úp', 'búp', 'cúp', 'chúp', 'dúp', 'đúp', 'giúp', 'húp', 'lúp', 'múp', 'núp', 'ngúp', 'nhúp', 'quúp', 'rúp', 'súp', 'túp', 'thúp', 'trúp', 'vúp', 'xúp', 'ụp', 'bụp', 'cụp', 'chụp', 'dụp', 'đụp', 'hụp', 'lụp', 'mụp', 'nụp', 'ngụp', 'nhụp', 'quụp', 'rụp', 'sụp', 'tụp', 'thụp', 'trụp', 'vụp', 'xụp', 'uơ', 'cuơ', 'chuơ', 'duơ', 'đuơ', 'huơ', 'muơ', 'nuơ', 'nguơ', 'nhuơ', 'quuơ', 'ruơ', 'suơ', 'tuơ', 'thuơ', 'truơ', 'vuơ', 'xuơ', 'uờ', 'cuờ', 'chuờ', 'duờ', 'đuờ', 'muờ', 'nuờ', 'nguờ', 'nhuờ', 'quuờ', 'ruờ', 'suờ', 'tuờ', 'thuờ', 'truờ', 'vuờ', 'xuờ', 'uở', 'cuở', 'chuở', 'duở', 'đuở', 'muở', 'nuở', 'nguở', 'nhuở', 'quuở', 'ruở', 'suở', 'tuở', 'thuở', 'truở', 'vuở', 'xuở', 'uỡ', 'cuỡ', 'chuỡ', 'duỡ', 'đuỡ', 'muỡ', 'nuỡ', 'nguỡ', 'nhuỡ', 'quuỡ', 'ruỡ', 'suỡ', 'tuỡ', 'thuỡ', 'truỡ', 'vuỡ', 'xuỡ', 'uớ', 'cuớ', 'chuớ', 'duớ', 'đuớ', 'muớ', 'nuớ', 'nguớ', 'nhuớ', 'quuớ', 'ruớ', 'suớ', 'tuớ', 'thuớ', 'truớ', 'vuớ', 'xuớ', 'uợ', 'cuợ', 'chuợ', 'duợ', 'đuợ', 'muợ', 'nuợ', 'nguợ', 'nhuợ', 'quuợ', 'ruợ', 'suợ', 'tuợ', 'thuợ', 'truợ', 'vuợ', 'xuợ', 'ut', 'cut', 'chut', 'dut', 'đut', 'mut', 'nut', 'ngut', 'nhut', 'quut', 'rut', 'sut', 'tut', 'thut', 'trut', 'vut', 'xut', 'ùt', 'cùt', 'chùt', 'dùt', 'đùt', 'mùt', 'nùt', 'ngùt', 'nhùt', 'quùt', 'rùt', 'sùt', 'tùt', 'thùt', 'trùt', 'vùt', 'xùt', 'ủt', 'củt', 'chủt', 'dủt', 'đủt', 'mủt', 'nủt', 'ngủt', 'nhủt', 'quủt', 'rủt', 'sủt', 'tủt', 'thủt', 'trủt', 'vủt', 'xủt', 'ũt', 'cũt', 'chũt', 'dũt', 'đũt', 'mũt', 'nũt', 'ngũt', 'nhũt', 'quũt', 'rũt', 'sũt', 'tũt', 'thũt', 'trũt', 'vũt', 'xũt', 'út', 'bút', 'cút', 'chút', 'dút', 'đút', 'gút', 'hút', 'lút', 'mút', 'nút', 'ngút', 'nhút', 'phút', 'quút', 'rút', 'sút', 'tút', 'thút', 'trút', 'vút', 'xút', 'ụt', 'bụt', 'cụt', 'chụt', 'dụt', 'đụt', 'hụt', 'lụt', 'mụt', 'nụt', 'ngụt', 'nhụt', 'phụt', 'quụt', 'rụt', 'sụt', 'tụt', 'thụt', 'trụt', 'vụt', 'xụt', 'uy', 'cuy', 'chuy', 'duy', 'đuy', 'huy', 'muy', 'nuy', 'nguy', 'nhuy', 'quuy', 'ruy', 'suy', 'tuy', 'thuy', 'truy', 'vuy', 'xuy', 'ùy', 'cùy', 'chùy', 'dùy', 'đùy', 'mùy', 'nùy', 'ngùy', 'nhùy', 'quùy', 'rùy', 'sùy', 'tùy', 'thùy', 'trùy', 'vùy', 'xùy', 'ủy', 'củy', 'chủy', 'dủy', 'đủy', 'hủy', 'mủy', 'nủy', 'ngủy', 'nhủy', 'quủy', 'rủy', 'sủy', 'tủy', 'thủy', 'trủy', 'vủy', 'xủy', 'ũy', 'cũy', 'chũy', 'dũy', 'đũy', 'lũy', 'mũy', 'nũy', 'ngũy', 'nhũy', 'quũy', 'rũy', 'sũy', 'tũy', 'thũy', 'trũy', 'vũy', 'xũy', 'úy', 'cúy', 'chúy', 'dúy', 'đúy', 'húy', 'múy', 'núy', 'ngúy', 'nhúy', 'quúy', 'rúy', 'súy', 'túy', 'thúy', 'trúy', 'vúy', 'xúy', 'ụy', 'cụy', 'chụy', 'dụy', 'đụy', 'lụy', 'mụy', 'nụy', 'ngụy', 'nhụy', 'quụy', 'rụy', 'sụy', 'tụy', 'thụy', 'trụy', 'vụy', 'xụy', 'ưa', 'cưa', 'chưa', 'dưa', 'đưa', 'lưa', 'mưa', 'nưa', 'ngưa', 'nhưa', 'quưa', 'rưa', 'sưa', 'tưa', 'thưa', 'trưa', 'vưa', 'xưa', 'ừa', 'bừa', 'cừa', 'chừa', 'dừa', 'đừa', 'lừa', 'mừa', 'nừa', 'ngừa', 'nhừa', 'quừa', 'rừa', 'sừa', 'từa', 'thừa', 'trừa', 'vừa', 'xừa', 'ửa', 'bửa', 'cửa', 'chửa', 'dửa', 'đửa', 'lửa', 'mửa', 'nửa', 'ngửa', 'nhửa', 'quửa', 'rửa', 'sửa', 'tửa', 'thửa', 'trửa', 'vửa', 'xửa', 'ữa', 'bữa', 'cữa', 'chữa', 'dữa', 'đữa', 'giữa', 'lữa', 'mữa', 'nữa', 'ngữa', 'nhữa', 'quữa', 'rữa', 'sữa', 'tữa', 'thữa', 'trữa', 'vữa', 'xữa', 'ứa', 'bứa', 'cứa', 'chứa', 'dứa', 'đứa', 'hứa', 'khứa', 'lứa', 'mứa', 'nứa', 'ngứa', 'nhứa', 'quứa', 'rứa', 'sứa', 'tứa', 'thứa', 'trứa', 'vứa', 'xứa', 'ựa', 'bựa', 'cựa', 'chựa', 'dựa', 'đựa', 'khựa', 'lựa', 'mựa', 'nựa', 'ngựa', 'nhựa', 'quựa', 'rựa', 'sựa', 'tựa', 'thựa', 'trựa', 'vựa', 'xựa', 'ưc', 'cưc', 'chưc', 'dưc', 'đưc', 'mưc', 'nưc', 'ngưc', 'nhưc', 'quưc', 'rưc', 'sưc', 'tưc', 'thưc', 'trưc', 'vưc', 'xưc', 'ừc', 'cừc', 'chừc', 'dừc', 'đừc', 'mừc', 'nừc', 'ngừc', 'nhừc', 'quừc', 'rừc', 'sừc', 'từc', 'thừc', 'trừc', 'vừc', 'xừc', 'ửc', 'cửc', 'chửc', 'dửc', 'đửc', 'mửc', 'nửc', 'ngửc', 'nhửc', 'quửc', 'rửc', 'sửc', 'tửc', 'thửc', 'trửc', 'vửc', 'xửc', 'ữc', 'cữc', 'chữc', 'dữc', 'đữc', 'mữc', 'nữc', 'ngữc', 'nhữc', 'quữc', 'rữc', 'sữc', 'tữc', 'thữc', 'trữc', 'vữc', 'xữc', 'ức', 'bức', 'cức', 'chức', 'dức', 'đức', 'hức', 'mức', 'nức', 'ngức', 'nhức', 'phức', 'quức', 'rức', 'sức', 'tức', 'thức', 'trức', 'vức', 'xức', 'ực', 'bực', 'cực', 'chực', 'dực', 'đực', 'hực', 'lực', 'mực', 'nực', 'ngực', 'nhực', 'quực', 'rực', 'sực', 'tực', 'thực', 'trực', 'vực', 'xực', 'ưi', 'cưi', 'chưi', 'dưi', 'đưi', 'mưi', 'nưi', 'ngưi', 'nhưi', 'quưi', 'rưi', 'sưi', 'tưi', 'thưi', 'trưi', 'vưi', 'xưi', 'ừi', 'cừi', 'chừi', 'dừi', 'đừi', 'mừi', 'nừi', 'ngừi', 'nhừi', 'quừi', 'rừi', 'sừi', 'từi', 'thừi', 'trừi', 'vừi', 'xừi', 'ửi', 'cửi', 'chửi', 'dửi', 'đửi', 'gửi', 'mửi', 'nửi', 'ngửi', 'nhửi', 'quửi', 'rửi', 'sửi', 'tửi', 'thửi', 'trửi', 'vửi', 'xửi', 'ữi', 'cữi', 'chữi', 'dữi', 'đữi', 'mữi', 'nữi', 'ngữi', 'nhữi', 'quữi', 'rữi', 'sữi', 'tữi', 'thữi', 'trữi', 'vữi', 'xữi', 'ứi', 'cứi', 'chứi', 'dứi', 'đứi', 'mứi', 'nứi', 'ngứi', 'nhứi', 'quứi', 'rứi', 'sứi', 'tứi', 'thứi', 'trứi', 'vứi', 'xứi', 'ựi', 'cựi', 'chựi', 'dựi', 'đựi', 'khựi', 'mựi', 'nựi', 'ngựi', 'nhựi', 'quựi', 'rựi', 'sựi', 'tựi', 'thựi', 'trựi', 'vựi', 'xựi', 'ưm', 'cưm', 'chưm', 'dưm', 'đưm', 'mưm', 'nưm', 'ngưm', 'nhưm', 'quưm', 'rưm', 'sưm', 'tưm', 'thưm', 'trưm', 'vưm', 'xưm', 'ừm', 'cừm', 'chừm', 'dừm', 'đừm', 'hừm', 'mừm', 'nừm', 'ngừm', 'nhừm', 'quừm', 'rừm', 'sừm', 'từm', 'thừm', 'trừm', 'vừm', 'xừm', 'ửm', 'cửm', 'chửm', 'dửm', 'đửm', 'mửm', 'nửm', 'ngửm', 'nhửm', 'quửm', 'rửm', 'sửm', 'tửm', 'thửm', 'trửm', 'vửm', 'xửm', 'ữm', 'cữm', 'chữm', 'dữm', 'đữm', 'mữm', 'nữm', 'ngữm', 'nhữm', 'quữm', 'rữm', 'sữm', 'tữm', 'thữm', 'trữm', 'vữm', 'xữm', 'ứm', 'cứm', 'chứm', 'dứm', 'đứm', 'mứm', 'nứm', 'ngứm', 'nhứm', 'quứm', 'rứm', 'sứm', 'tứm', 'thứm', 'trứm', 'vứm', 'xứm', 'ựm', 'cựm', 'chựm', 'dựm', 'đựm', 'mựm', 'nựm', 'ngựm', 'nhựm', 'quựm', 'rựm', 'sựm', 'tựm', 'thựm', 'trựm', 'vựm', 'xựm', 'ưt', 'cưt', 'chưt', 'dưt', 'đưt', 'mưt', 'nưt', 'ngưt', 'nhưt', 'quưt', 'rưt', 'sưt', 'tưt', 'thưt', 'trưt', 'vưt', 'xưt', 'ừt', 'cừt', 'chừt', 'dừt', 'đừt', 'mừt', 'nừt', 'ngừt', 'nhừt', 'quừt', 'rừt', 'sừt', 'từt', 'thừt', 'trừt', 'vừt', 'xừt', 'ửt', 'cửt', 'chửt', 'dửt', 'đửt', 'mửt', 'nửt', 'ngửt', 'nhửt', 'quửt', 'rửt', 'sửt', 'tửt', 'thửt', 'trửt', 'vửt', 'xửt', 'ữt', 'cữt', 'chữt', 'dữt', 'đữt', 'mữt', 'nữt', 'ngữt', 'nhữt', 'quữt', 'rữt', 'sữt', 'tữt', 'thữt', 'trữt', 'vữt', 'xữt', 'ứt', 'bứt', 'cứt', 'chứt', 'dứt', 'đứt', 'lứt', 'mứt', 'nứt', 'ngứt', 'nhứt', 'quứt', 'rứt', 'sứt', 'tứt', 'thứt', 'trứt', 'vứt', 'xứt', 'ựt', 'cựt', 'chựt', 'dựt', 'đựt', 'mựt', 'nựt', 'ngựt', 'nhựt', 'quựt', 'rựt', 'sựt', 'tựt', 'thựt', 'trựt', 'vựt', 'xựt', 'ưu', 'bưu', 'cưu', 'chưu', 'dưu', 'đưu', 'hưu', 'lưu', 'mưu', 'nưu', 'ngưu', 'nhưu', 'quưu', 'rưu', 'sưu', 'tưu', 'thưu', 'trưu', 'vưu', 'xưu', 'ừu', 'cừu', 'chừu', 'dừu', 'đừu', 'mừu', 'nừu', 'ngừu', 'nhừu', 'quừu', 'rừu', 'sừu', 'từu', 'thừu', 'trừu', 'vừu', 'xừu', 'ửu', 'bửu', 'cửu', 'chửu', 'dửu', 'đửu', 'mửu', 'nửu', 'ngửu', 'nhửu', 'quửu', 'rửu', 'sửu', 'tửu', 'thửu', 'trửu', 'vửu', 'xửu', 'ữu', 'cữu', 'chữu', 'dữu', 'đữu', 'hữu', 'mữu', 'nữu', 'ngữu', 'nhữu', 'quữu', 'rữu', 'sữu', 'tữu', 'thữu', 'trữu', 'vữu', 'xữu', 'ứu', 'cứu', 'chứu', 'dứu', 'đứu', 'mứu', 'nứu', 'ngứu', 'nhứu', 'quứu', 'rứu', 'sứu', 'tứu', 'thứu', 'trứu', 'vứu', 'xứu', 'ựu', 'cựu', 'chựu', 'dựu', 'đựu', 'lựu', 'mựu', 'nựu', 'ngựu', 'nhựu', 'quựu', 'rựu', 'sựu', 'tựu', 'thựu', 'trựu', 'vựu', 'xựu', 'yt', 'cyt', 'chyt', 'dyt', 'đyt', 'myt', 'nyt', 'ngyt', 'nhyt', 'quyt', 'ryt', 'syt', 'tyt', 'thyt', 'tryt', 'vyt', 'xyt', 'ỳt', 'cỳt', 'chỳt', 'dỳt', 'đỳt', 'mỳt', 'nỳt', 'ngỳt', 'nhỳt', 'quỳt', 'rỳt', 'sỳt', 'tỳt', 'thỳt', 'trỳt', 'vỳt', 'xỳt', 'ỷt', 'cỷt', 'chỷt', 'dỷt', 'đỷt', 'mỷt', 'nỷt', 'ngỷt', 'nhỷt', 'quỷt', 'rỷt', 'sỷt', 'tỷt', 'thỷt', 'trỷt', 'vỷt', 'xỷt', 'ỹt', 'cỹt', 'chỹt', 'dỹt', 'đỹt', 'mỹt', 'nỹt', 'ngỹt', 'nhỹt', 'quỹt', 'rỹt', 'sỹt', 'tỹt', 'thỹt', 'trỹt', 'vỹt', 'xỹt', 'ýt', 'cýt', 'chýt', 'dýt', 'đýt', 'mýt', 'nýt', 'ngýt', 'nhýt', 'quýt', 'rýt', 'sýt', 'týt', 'thýt', 'trýt', 'výt', 'xýt', 'ỵt', 'cỵt', 'chỵt', 'dỵt', 'đỵt', 'mỵt', 'nỵt', 'ngỵt', 'nhỵt', 'quỵt', 'rỵt', 'sỵt', 'tỵt', 'thỵt', 'trỵt', 'vỵt', 'xỵt', 'ach', 'cach', 'chach', 'dach', 'đach', 'mach', 'nach', 'ngach', 'nhach', 'quach', 'rach', 'sach', 'tach', 'thach', 'trach', 'vach', 'xach', 'àch', 'càch', 'chàch', 'dàch', 'đàch', 'màch', 'nàch', 'ngàch', 'nhàch', 'quàch', 'ràch', 'sàch', 'tàch', 'thàch', 'tràch', 'vàch', 'xàch', 'ảch', 'cảch', 'chảch', 'dảch', 'đảch', 'mảch', 'nảch', 'ngảch', 'nhảch', 'quảch', 'rảch', 'sảch', 'tảch', 'thảch', 'trảch', 'vảch', 'xảch', 'ãch', 'cãch', 'chãch', 'dãch', 'đãch', 'mãch', 'nãch', 'ngãch', 'nhãch', 'quãch', 'rãch', 'sãch', 'tãch', 'thãch', 'trãch', 'vãch', 'xãch', 'ách', 'bách', 'cách', 'chách', 'dách', 'đách', 'hách', 'khách', 'lách', 'mách', 'nách', 'ngách', 'nhách', 'phách', 'quách', 'rách', 'sách', 'tách', 'thách', 'trách', 'vách', 'xách', 'ạch', 'bạch', 'cạch', 'chạch', 'dạch', 'đạch', 'gạch', 'hạch', 'lạch', 'mạch', 'nạch', 'ngạch', 'nhạch', 'phạch', 'quạch', 'rạch', 'sạch', 'tạch', 'thạch', 'trạch', 'vạch', 'xạch', 'ang', 'bang', 'cang', 'chang', 'dang', 'đang', 'gang', 'giang', 'hang', 'khang', 'lang', 'mang', 'nang', 'ngang', 'nhang', 'phang', 'quang', 'rang', 'sang', 'tang', 'thang', 'trang', 'vang', 'xang', 'àng', 'bàng', 'càng', 'chàng', 'dàng', 'đàng', 'gàng', 'giàng', 'hàng', 'khàng', 'làng', 'màng', 'nàng', 'ngàng', 'nhàng', 'quàng', 'ràng', 'sàng', 'tàng', 'thàng', 'tràng', 'vàng', 'xàng', 'ảng', 'bảng', 'cảng', 'chảng', 'dảng', 'đảng', 'giảng', 'khảng', 'lảng', 'mảng', 'nảng', 'ngảng', 'nhảng', 'phảng', 'quảng', 'rảng', 'sảng', 'tảng', 'thảng', 'trảng', 'vảng', 'xảng', 'ãng', 'cãng', 'chãng', 'dãng', 'đãng', 'hãng', 'lãng', 'mãng', 'nãng', 'ngãng', 'nhãng', 'quãng', 'rãng', 'sãng', 'tãng', 'thãng', 'trãng', 'vãng', 'xãng', 'áng', 'báng', 'cáng', 'cháng', 'dáng', 'đáng', 'giáng', 'háng', 'kháng', 'láng', 'máng', 'náng', 'ngáng', 'nháng', 'quáng', 'ráng', 'sáng', 'táng', 'tháng', 'tráng', 'váng', 'xáng', 'ạng', 'bạng', 'cạng', 'chạng', 'dạng', 'đạng', 'giạng', 'hạng', 'khạng', 'lạng', 'mạng', 'nạng', 'ngạng', 'nhạng', 'quạng', 'rạng', 'sạng', 'tạng', 'thạng', 'trạng', 'vạng', 'xạng', 'anh', 'banh', 'canh', 'chanh', 'danh', 'đanh', 'ganh', 'hanh', 'khanh', 'lanh', 'manh', 'nanh', 'nganh', 'nhanh', 'phanh', 'quanh', 'ranh', 'sanh', 'tanh', 'thanh', 'tranh', 'vanh', 'xanh', 'ành', 'bành', 'cành', 'chành', 'dành', 'đành', 'giành', 'hành', 'lành', 'mành', 'nành', 'ngành', 'nhành', 'phành', 'quành', 'rành', 'sành', 'tành', 'thành', 'trành', 'vành', 'xành', 'ảnh', 'bảnh', 'cảnh', 'chảnh', 'dảnh', 'đảnh', 'giảnh', 'hảnh', 'khảnh', 'lảnh', 'mảnh', 'nảnh', 'ngảnh', 'nhảnh', 'quảnh', 'rảnh', 'sảnh', 'tảnh', 'thảnh', 'trảnh', 'vảnh', 'xảnh', 'ãnh', 'cãnh', 'chãnh', 'dãnh', 'đãnh', 'hãnh', 'lãnh', 'mãnh', 'nãnh', 'ngãnh', 'nhãnh', 'quãnh', 'rãnh', 'sãnh', 'tãnh', 'thãnh', 'trãnh', 'vãnh', 'xãnh', 'ánh', 'bánh', 'cánh', 'chánh', 'dánh', 'đánh', 'gánh', 'hánh', 'khánh', 'lánh', 'mánh', 'nánh', 'ngánh', 'nhánh', 'quánh', 'ránh', 'sánh', 'tánh', 'thánh', 'tránh', 'vánh', 'xánh', 'ạnh', 'bạnh', 'cạnh', 'chạnh', 'dạnh', 'đạnh', 'hạnh', 'lạnh', 'mạnh', 'nạnh', 'ngạnh', 'nhạnh', 'quạnh', 'rạnh', 'sạnh', 'tạnh', 'thạnh', 'trạnh', 'vạnh', 'xạnh', 'ăng', 'băng', 'căng', 'chăng', 'dăng', 'đăng', 'găng', 'giăng', 'hăng', 'khăng', 'lăng', 'măng', 'năng', 'ngăng', 'nhăng', 'phăng', 'quăng', 'răng', 'săng', 'tăng', 'thăng', 'trăng', 'văng', 'xăng', 'ằng', 'bằng', 'cằng', 'chằng', 'dằng', 'đằng', 'giằng', 'hằng', 'khằng', 'lằng', 'mằng', 'nằng', 'ngằng', 'nhằng', 'quằng', 'rằng', 'sằng', 'tằng', 'thằng', 'trằng', 'vằng', 'xằng', 'ẳng', 'cẳng', 'chẳng', 'dẳng', 'đẳng', 'khẳng', 'lẳng', 'mẳng', 'nẳng', 'ngẳng', 'nhẳng', 'phẳng', 'quẳng', 'rẳng', 'sẳng', 'tẳng', 'thẳng', 'trẳng', 'vẳng', 'xẳng', 'ẵng', 'bẵng', 'cẵng', 'chẵng', 'dẵng', 'đẵng', 'gẵng', 'hẵng', 'lẵng', 'mẵng', 'nẵng', 'ngẵng', 'nhẵng', 'quẵng', 'rẵng', 'sẵng', 'tẵng', 'thẵng', 'trẵng', 'vẵng', 'xẵng', 'ắng', 'bắng', 'cắng', 'chắng', 'dắng', 'đắng', 'gắng', 'hắng', 'khắng', 'lắng', 'mắng', 'nắng', 'ngắng', 'nhắng', 'quắng', 'rắng', 'sắng', 'tắng', 'thắng', 'trắng', 'vắng', 'xắng', 'ặng', 'bặng', 'cặng', 'chặng', 'dặng', 'đặng', 'gặng', 'lặng', 'mặng', 'nặng', 'ngặng', 'nhặng', 'quặng', 'rặng', 'sặng', 'tặng', 'thặng', 'trặng', 'vặng', 'xặng', 'âng', 'bâng', 'câng', 'châng', 'dâng', 'đâng', 'lâng', 'mâng', 'nâng', 'ngâng', 'nhâng', 'quâng', 'râng', 'sâng', 'tâng', 'thâng', 'trâng', 'vâng', 'xâng', 'ầng', 'cầng', 'chầng', 'dầng', 'đầng', 'mầng', 'nầng', 'ngầng', 'nhầng', 'quầng', 'rầng', 'sầng', 'tầng', 'thầng', 'trầng', 'vầng', 'xầng', 'ẩng', 'cẩng', 'chẩng', 'dẩng', 'đẩng', 'mẩng', 'nẩng', 'ngẩng', 'nhẩng', 'quẩng', 'rẩng', 'sẩng', 'tẩng', 'thẩng', 'trẩng', 'vẩng', 'xẩng', 'ẫng', 'cẫng', 'chẫng', 'dẫng', 'đẫng', 'hẫng', 'mẫng', 'nẫng', 'ngẫng', 'nhẫng', 'quẫng', 'rẫng', 'sẫng', 'tẫng', 'thẫng', 'trẫng', 'vẫng', 'xẫng', 'ấng', 'cấng', 'chấng', 'dấng', 'đấng', 'mấng', 'nấng', 'ngấng', 'nhấng', 'quấng', 'rấng', 'sấng', 'tấng', 'thấng', 'trấng', 'vấng', 'xấng', 'ậng', 'cậng', 'chậng', 'dậng', 'đậng', 'mậng', 'nậng', 'ngậng', 'nhậng', 'quậng', 'rậng', 'sậng', 'tậng', 'thậng', 'trậng', 'vậng', 'xậng', 'eng', 'beng', 'ceng', 'cheng', 'deng', 'đeng', 'keng', 'leng', 'meng', 'neng', 'ngeng', 'nheng', 'queng', 'reng', 'seng', 'teng', 'theng', 'treng', 'veng', 'xeng', 'èng', 'cèng', 'chèng', 'dèng', 'đèng', 'mèng', 'nèng', 'ngèng', 'nhèng', 'quèng', 'rèng', 'sèng', 'tèng', 'thèng', 'trèng', 'vèng', 'xèng', 'ẻng', 'cẻng', 'chẻng', 'dẻng', 'đẻng', 'kẻng', 'lẻng', 'mẻng', 'nẻng', 'ngẻng', 'nhẻng', 'quẻng', 'rẻng', 'sẻng', 'tẻng', 'thẻng', 'trẻng', 'vẻng', 'xẻng', 'ẽng', 'cẽng', 'chẽng', 'dẽng', 'đẽng', 'mẽng', 'nẽng', 'ngẽng', 'nhẽng', 'quẽng', 'rẽng', 'sẽng', 'tẽng', 'thẽng', 'trẽng', 'vẽng', 'xẽng', 'éng', 'béng', 'céng', 'chéng', 'déng', 'đéng', 'léng', 'méng', 'néng', 'ngéng', 'nhéng', 'phéng', 'quéng', 'réng', 'séng', 'téng', 'théng', 'tréng', 'véng', 'xéng', 'ẹng', 'cẹng', 'chẹng', 'dẹng', 'đẹng', 'mẹng', 'nẹng', 'ngẹng', 'nhẹng', 'quẹng', 'rẹng', 'sẹng', 'tẹng', 'thẹng', 'trẹng', 'vẹng', 'xẹng', 'êch', 'cêch', 'chêch', 'dêch', 'đêch', 'mêch', 'nêch', 'ngêch', 'nhêch', 'quêch', 'rêch', 'sêch', 'têch', 'thêch', 'trêch', 'vêch', 'xêch', 'ềch', 'cềch', 'chềch', 'dềch', 'đềch', 'mềch', 'nềch', 'ngềch', 'nhềch', 'quềch', 'rềch', 'sềch', 'tềch', 'thềch', 'trềch', 'vềch', 'xềch', 'ểch', 'cểch', 'chểch', 'dểch', 'đểch', 'mểch', 'nểch', 'ngểch', 'nhểch', 'quểch', 'rểch', 'sểch', 'tểch', 'thểch', 'trểch', 'vểch', 'xểch', 'ễch', 'cễch', 'chễch', 'dễch', 'đễch', 'mễch', 'nễch', 'ngễch', 'nhễch', 'quễch', 'rễch', 'sễch', 'tễch', 'thễch', 'trễch', 'vễch', 'xễch', 'ếch', 'cếch', 'chếch', 'dếch', 'đếch', 'ghếch', 'hếch', 'lếch', 'mếch', 'nếch', 'ngếch', 'nghếch', 'nhếch', 'phếch', 'quếch', 'rếch', 'sếch', 'tếch', 'thếch', 'trếch', 'vếch', 'xếch', 'ệch', 'bệch', 'cệch', 'chệch', 'dệch', 'đệch', 'ghệch', 'hệch', 'lệch', 'mệch', 'nệch', 'ngệch', 'nghệch', 'nhệch', 'quệch', 'rệch', 'sệch', 'tệch', 'thệch', 'trệch', 'vệch', 'xệch', 'ênh', 'bênh', 'cênh', 'chênh', 'dênh', 'đênh', 'hênh', 'kênh', 'khênh', 'lênh', 'mênh', 'nênh', 'ngênh', 'nghênh', 'nhênh', 'quênh', 'rênh', 'sênh', 'tênh', 'thênh', 'trênh', 'vênh', 'xênh', 'ềnh', 'bềnh', 'cềnh', 'chềnh', 'dềnh', 'đềnh', 'ghềnh', 'kềnh', 'khềnh', 'lềnh', 'mềnh', 'nềnh', 'ngềnh', 'nghềnh', 'nhềnh', 'phềnh', 'quềnh', 'rềnh', 'sềnh', 'tềnh', 'thềnh', 'trềnh', 'vềnh', 'xềnh', 'ểnh', 'cểnh', 'chểnh', 'dểnh', 'đểnh', 'ghểnh', 'hểnh', 'khểnh', 'mểnh', 'nểnh', 'ngểnh', 'nhểnh', 'quểnh', 'rểnh', 'sểnh', 'tểnh', 'thểnh', 'trểnh', 'vểnh', 'xểnh', 'ễnh', 'cễnh', 'chễnh', 'dễnh', 'đễnh', 'kễnh', 'mễnh', 'nễnh', 'ngễnh', 'nghễnh', 'nhễnh', 'quễnh', 'rễnh', 'sễnh', 'tễnh', 'thễnh', 'trễnh', 'vễnh', 'xễnh', 'ếnh', 'cếnh', 'chếnh', 'dếnh', 'đếnh', 'mếnh', 'nếnh', 'ngếnh', 'nhếnh', 'quếnh', 'rếnh', 'sếnh', 'tếnh', 'thếnh', 'trếnh', 'vếnh', 'xếnh', 'ệnh', 'bệnh', 'cệnh', 'chệnh', 'dệnh', 'đệnh', 'kệnh', 'khệnh', 'lệnh', 'mệnh', 'nệnh', 'ngệnh', 'nhệnh', 'phệnh', 'quệnh', 'rệnh', 'sệnh', 'tệnh', 'thệnh', 'trệnh', 'vệnh', 'xệnh', 'ich', 'cich', 'chich', 'dich', 'đich', 'mich', 'nich', 'ngich', 'nhich', 'quich', 'rich', 'sich', 'tich', 'thich', 'trich', 'vich', 'xich', 'ìch', 'cìch', 'chìch', 'dìch', 'đìch', 'mìch', 'nìch', 'ngìch', 'nhìch', 'quìch', 'rìch', 'sìch', 'tìch', 'thìch', 'trìch', 'vìch', 'xìch', 'ỉch', 'cỉch', 'chỉch', 'dỉch', 'đỉch', 'mỉch', 'nỉch', 'ngỉch', 'nhỉch', 'quỉch', 'rỉch', 'sỉch', 'tỉch', 'thỉch', 'trỉch', 'vỉch', 'xỉch', 'ĩch', 'cĩch', 'chĩch', 'dĩch', 'đĩch', 'mĩch', 'nĩch', 'ngĩch', 'nhĩch', 'quĩch', 'rĩch', 'sĩch', 'tĩch', 'thĩch', 'trĩch', 'vĩch', 'xĩch', 'ích', 'bích', 'cích', 'chích', 'dích', 'đích', 'kích', 'khích', 'lích', 'mích', 'ních', 'ngích', 'nhích', 'phích', 'quích', 'rích', 'sích', 'tích', 'thích', 'trích', 'vích', 'xích', 'ịch', 'bịch', 'cịch', 'chịch', 'dịch', 'địch', 'hịch', 'kịch', 'lịch', 'mịch', 'nịch', 'ngịch', 'nghịch', 'nhịch', 'phịch', 'quịch', 'rịch', 'sịch', 'tịch', 'thịch', 'trịch', 'vịch', 'xịch', 'iêc', 'ciêc', 'chiêc', 'diêc', 'điêc', 'miêc', 'niêc', 'ngiêc', 'nhiêc', 'quiêc', 'riêc', 'siêc', 'tiêc', 'thiêc', 'triêc', 'viêc', 'xiêc', 'iềc', 'ciềc', 'chiềc', 'diềc', 'điềc', 'miềc', 'niềc', 'ngiềc', 'nhiềc', 'quiềc', 'riềc', 'siềc', 'tiềc', 'thiềc', 'triềc', 'viềc', 'xiềc', 'iểc', 'ciểc', 'chiểc', 'diểc', 'điểc', 'miểc', 'niểc', 'ngiểc', 'nhiểc', 'quiểc', 'riểc', 'siểc', 'tiểc', 'thiểc', 'triểc', 'viểc', 'xiểc', 'iễc', 'ciễc', 'chiễc', 'diễc', 'điễc', 'miễc', 'niễc', 'ngiễc', 'nhiễc', 'quiễc', 'riễc', 'siễc', 'tiễc', 'thiễc', 'triễc', 'viễc', 'xiễc', 'iếc', 'biếc', 'ciếc', 'chiếc', 'diếc', 'điếc', 'ghiếc', 'liếc', 'miếc', 'niếc', 'ngiếc', 'nhiếc', 'quiếc', 'riếc', 'siếc', 'tiếc', 'thiếc', 'triếc', 'viếc', 'xiếc', 'iệc', 'ciệc', 'chiệc', 'diệc', 'điệc', 'miệc', 'niệc', 'ngiệc', 'nhiệc', 'quiệc', 'riệc', 'siệc', 'tiệc', 'thiệc', 'triệc', 'việc', 'xiệc', 'iêm', 'ciêm', 'chiêm', 'diêm', 'điêm', 'kiêm', 'khiêm', 'liêm', 'miêm', 'niêm', 'ngiêm', 'nghiêm', 'nhiêm', 'quiêm', 'riêm', 'siêm', 'tiêm', 'thiêm', 'triêm', 'viêm', 'xiêm', 'iềm', 'ciềm', 'chiềm', 'diềm', 'điềm', 'hiềm', 'kiềm', 'liềm', 'miềm', 'niềm', 'ngiềm', 'nhiềm', 'quiềm', 'riềm', 'siềm', 'tiềm', 'thiềm', 'triềm', 'viềm', 'xiềm', 'iểm', 'ciểm', 'chiểm', 'diểm', 'điểm', 'hiểm', 'kiểm', 'miểm', 'niểm', 'ngiểm', 'nhiểm', 'quiểm', 'riểm', 'siểm', 'tiểm', 'thiểm', 'triểm', 'viểm', 'xiểm', 'iễm', 'ciễm', 'chiễm', 'diễm', 'điễm', 'miễm', 'niễm', 'ngiễm', 'nghiễm', 'nhiễm', 'quiễm', 'riễm', 'siễm', 'tiễm', 'thiễm', 'triễm', 'viễm', 'xiễm', 'iếm', 'biếm', 'ciếm', 'chiếm', 'diếm', 'điếm', 'hiếm', 'kiếm', 'khiếm', 'liếm', 'miếm', 'niếm', 'ngiếm', 'nhiếm', 'phiếm', 'quiếm', 'riếm', 'siếm', 'tiếm', 'thiếm', 'triếm', 'viếm', 'xiếm', 'iệm', 'ciệm', 'chiệm', 'diệm', 'điệm', 'kiệm', 'liệm', 'miệm', 'niệm', 'ngiệm', 'nghiệm', 'nhiệm', 'quiệm', 'riệm', 'siệm', 'tiệm', 'thiệm', 'triệm', 'việm', 'xiệm', 'iên', 'biên', 'ciên', 'chiên', 'diên', 'điên', 'hiên', 'kiên', 'khiên', 'liên', 'miên', 'niên', 'ngiên', 'nghiên', 'nhiên', 'phiên', 'quiên', 'riên', 'siên', 'tiên', 'thiên', 'triên', 'viên', 'xiên', 'iền', 'biền', 'ciền', 'chiền', 'diền', 'điền', 'hiền', 'kiền', 'liền', 'miền', 'niền', 'ngiền', 'nghiền', 'nhiền', 'phiền', 'quiền', 'riền', 'siền', 'tiền', 'thiền', 'triền', 'viền', 'xiền', 'iển', 'biển', 'ciển', 'chiển', 'diển', 'điển', 'hiển', 'khiển', 'miển', 'niển', 'ngiển', 'nhiển', 'quiển', 'riển', 'siển', 'tiển', 'thiển', 'triển', 'viển', 'xiển', 'iễn', 'ciễn', 'chiễn', 'diễn', 'điễn', 'liễn', 'miễn', 'niễn', 'ngiễn', 'nhiễn', 'quiễn', 'riễn', 'siễn', 'tiễn', 'thiễn', 'triễn', 'viễn', 'xiễn', 'iến', 'biến', 'ciến', 'chiến', 'diến', 'điến', 'hiến', 'kiến', 'khiến', 'liến', 'miến', 'niến', 'ngiến', 'nghiến', 'nhiến', 'phiến', 'quiến', 'riến', 'siến', 'tiến', 'thiến', 'triến', 'viến', 'xiến', 'iện', 'biện', 'ciện', 'chiện', 'diện', 'điện', 'hiện', 'kiện', 'miện', 'niện', 'ngiện', 'nghiện', 'nhiện', 'phiện', 'quiện', 'riện', 'siện', 'tiện', 'thiện', 'triện', 'viện', 'xiện', 'iêp', 'ciêp', 'chiêp', 'diêp', 'điêp', 'miêp', 'niêp', 'ngiêp', 'nhiêp', 'quiêp', 'riêp', 'siêp', 'tiêp', 'thiêp', 'triêp', 'viêp', 'xiêp', 'iềp', 'ciềp', 'chiềp', 'diềp', 'điềp', 'miềp', 'niềp', 'ngiềp', 'nhiềp', 'quiềp', 'riềp', 'siềp', 'tiềp', 'thiềp', 'triềp', 'viềp', 'xiềp', 'iểp', 'ciểp', 'chiểp', 'diểp', 'điểp', 'miểp', 'niểp', 'ngiểp', 'nhiểp', 'quiểp', 'riểp', 'siểp', 'tiểp', 'thiểp', 'triểp', 'viểp', 'xiểp', 'iễp', 'ciễp', 'chiễp', 'diễp', 'điễp', 'miễp', 'niễp', 'ngiễp', 'nhiễp', 'quiễp', 'riễp', 'siễp', 'tiễp', 'thiễp', 'triễp', 'viễp', 'xiễp', 'iếp', 'ciếp', 'chiếp', 'diếp', 'điếp', 'hiếp', 'kiếp', 'khiếp', 'liếp', 'miếp', 'niếp', 'ngiếp', 'nhiếp', 'quiếp', 'riếp', 'siếp', 'tiếp', 'thiếp', 'triếp', 'viếp', 'xiếp', 'iệp', 'ciệp', 'chiệp', 'diệp', 'điệp', 'hiệp', 'miệp', 'niệp', 'ngiệp', 'nghiệp', 'nhiệp', 'quiệp', 'riệp', 'siệp', 'tiệp', 'thiệp', 'triệp', 'việp', 'xiệp', 'iêt', 'ciêt', 'chiêt', 'diêt', 'điêt', 'miêt', 'niêt', 'ngiêt', 'nhiêt', 'quiêt', 'riêt', 'siêt', 'tiêt', 'thiêt', 'triêt', 'viêt', 'xiêt', 'iềt', 'ciềt', 'chiềt', 'diềt', 'điềt', 'miềt', 'niềt', 'ngiềt', 'nhiềt', 'quiềt', 'riềt', 'siềt', 'tiềt', 'thiềt', 'triềt', 'viềt', 'xiềt', 'iểt', 'ciểt', 'chiểt', 'diểt', 'điểt', 'miểt', 'niểt', 'ngiểt', 'nhiểt', 'quiểt', 'riểt', 'siểt', 'tiểt', 'thiểt', 'triểt', 'viểt', 'xiểt', 'iễt', 'ciễt', 'chiễt', 'diễt', 'điễt', 'miễt', 'niễt', 'ngiễt', 'nhiễt', 'quiễt', 'riễt', 'siễt', 'tiễt', 'thiễt', 'triễt', 'viễt', 'xiễt', 'iết', 'biết', 'ciết', 'chiết', 'diết', 'điết', 'kiết', 'khiết', 'miết', 'niết', 'ngiết', 'nhiết', 'quiết', 'riết', 'siết', 'tiết', 'thiết', 'triết', 'viết', 'xiết', 'iệt', 'biệt', 'ciệt', 'chiệt', 'diệt', 'điệt', 'kiệt', 'liệt', 'miệt', 'niệt', 'ngiệt', 'nghiệt', 'nhiệt', 'phiệt', 'quiệt', 'riệt', 'siệt', 'tiệt', 'thiệt', 'triệt', 'việt', 'xiệt', 'iêu', 'ciêu', 'chiêu', 'diêu', 'điêu', 'kiêu', 'khiêu', 'liêu', 'miêu', 'niêu', 'ngiêu', 'nhiêu', 'phiêu', 'quiêu', 'riêu', 'siêu', 'tiêu', 'thiêu', 'triêu', 'viêu', 'xiêu', 'iều', 'ciều', 'chiều', 'diều', 'điều', 'kiều', 'liều', 'miều', 'niều', 'ngiều', 'nhiều', 'quiều', 'riều', 'siều', 'tiều', 'thiều', 'triều', 'viều', 'xiều', 'iểu', 'biểu', 'ciểu', 'chiểu', 'diểu', 'điểu', 'hiểu', 'kiểu', 'miểu', 'niểu', 'ngiểu', 'nhiểu', 'quiểu', 'riểu', 'siểu', 'tiểu', 'thiểu', 'triểu', 'viểu', 'xiểu', 'iễu', 'ciễu', 'chiễu', 'diễu', 'điễu', 'liễu', 'miễu', 'niễu', 'ngiễu', 'nhiễu', 'quiễu', 'riễu', 'siễu', 'tiễu', 'thiễu', 'triễu', 'viễu', 'xiễu', 'iếu', 'biếu', 'ciếu', 'chiếu', 'diếu', 'điếu', 'hiếu', 'kiếu', 'khiếu', 'miếu', 'niếu', 'ngiếu', 'nhiếu', 'phiếu', 'quiếu', 'riếu', 'siếu', 'tiếu', 'thiếu', 'triếu', 'viếu', 'xiếu', 'iệu', 'ciệu', 'chiệu', 'diệu', 'điệu', 'hiệu', 'kiệu', 'liệu', 'miệu', 'niệu', 'ngiệu', 'nhiệu', 'quiệu', 'riệu', 'siệu', 'tiệu', 'thiệu', 'triệu', 'việu', 'xiệu', 'inh', 'binh', 'cinh', 'chinh', 'dinh', 'đinh', 'kinh', 'khinh', 'linh', 'minh', 'ninh', 'nginh', 'nghinh', 'nhinh', 'quinh', 'rinh', 'sinh', 'tinh', 'thinh', 'trinh', 'vinh', 'xinh', 'ình', 'bình', 'cình', 'chình', 'dình', 'đình', 'hình', 'kình', 'mình', 'nình', 'ngình', 'nhình', 'quình', 'rình', 'sình', 'tình', 'thình', 'trình', 'vình', 'xình', 'ỉnh', 'bỉnh', 'cỉnh', 'chỉnh', 'dỉnh', 'đỉnh', 'hỉnh', 'kỉnh', 'khỉnh', 'lỉnh', 'mỉnh', 'nỉnh', 'ngỉnh', 'nhỉnh', 'quỉnh', 'rỉnh', 'sỉnh', 'tỉnh', 'thỉnh', 'trỉnh', 'vỉnh', 'xỉnh', 'ĩnh', 'bĩnh', 'cĩnh', 'chĩnh', 'dĩnh', 'đĩnh', 'hĩnh', 'lĩnh', 'mĩnh', 'nĩnh', 'ngĩnh', 'nhĩnh', 'quĩnh', 'rĩnh', 'sĩnh', 'tĩnh', 'thĩnh', 'trĩnh', 'vĩnh', 'xĩnh', 'ính', 'bính', 'cính', 'chính', 'dính', 'đính', 'kính', 'lính', 'mính', 'nính', 'ngính', 'nhính', 'quính', 'rính', 'sính', 'tính', 'thính', 'trính', 'vính', 'xính', 'ịnh', 'cịnh', 'chịnh', 'dịnh', 'định', 'mịnh', 'nịnh', 'ngịnh', 'nhịnh', 'quịnh', 'rịnh', 'sịnh', 'tịnh', 'thịnh', 'trịnh', 'vịnh', 'xịnh', 'oac', 'coac', 'choac', 'doac', 'đoac', 'moac', 'noac', 'ngoac', 'nhoac', 'quoac', 'roac', 'soac', 'toac', 'thoac', 'troac', 'voac', 'xoac', 'oàc', 'coàc', 'choàc', 'doàc', 'đoàc', 'moàc', 'noàc', 'ngoàc', 'nhoàc', 'quoàc', 'roàc', 'soàc', 'toàc', 'thoàc', 'troàc', 'voàc', 'xoàc', 'oảc', 'coảc', 'choảc', 'doảc', 'đoảc', 'moảc', 'noảc', 'ngoảc', 'nhoảc', 'quoảc', 'roảc', 'soảc', 'toảc', 'thoảc', 'troảc', 'voảc', 'xoảc', 'oãc', 'coãc', 'choãc', 'doãc', 'đoãc', 'moãc', 'noãc', 'ngoãc', 'nhoãc', 'quoãc', 'roãc', 'soãc', 'toãc', 'thoãc', 'troãc', 'voãc', 'xoãc', 'oác', 'coác', 'choác', 'doác', 'đoác', 'hoác', 'khoác', 'moác', 'noác', 'ngoác', 'nhoác', 'quoác', 'roác', 'soác', 'toác', 'thoác', 'troác', 'voác', 'xoác', 'oạc', 'coạc', 'choạc', 'doạc', 'đoạc', 'loạc', 'moạc', 'noạc', 'ngoạc', 'nhoạc', 'quoạc', 'roạc', 'soạc', 'toạc', 'thoạc', 'troạc', 'voạc', 'xoạc', 'oai', 'coai', 'choai', 'doai', 'đoai', 'hoai', 'khoai', 'moai', 'noai', 'ngoai', 'nhoai', 'quoai', 'roai', 'soai', 'toai', 'thoai', 'troai', 'voai', 'xoai', 'oài', 'coài', 'choài', 'doài', 'đoài', 'hoài', 'loài', 'moài', 'noài', 'ngoài', 'nhoài', 'quoài', 'roài', 'soài', 'toài', 'thoài', 'troài', 'voài', 'xoài', 'oải', 'coải', 'choải', 'doải', 'đoải', 'hoải', 'khoải', 'moải', 'noải', 'ngoải', 'nhoải', 'quoải', 'roải', 'soải', 'toải', 'thoải', 'troải', 'voải', 'xoải', 'oãi', 'coãi', 'choãi', 'doãi', 'đoãi', 'moãi', 'noãi', 'ngoãi', 'nhoãi', 'quoãi', 'roãi', 'soãi', 'toãi', 'thoãi', 'troãi', 'voãi', 'xoãi', 'oái', 'coái', 'choái', 'doái', 'đoái', 'khoái', 'moái', 'noái', 'ngoái', 'nhoái', 'quoái', 'roái', 'soái', 'toái', 'thoái', 'troái', 'voái', 'xoái', 'oại', 'coại', 'choại', 'doại', 'đoại', 'hoại', 'loại', 'moại', 'noại', 'ngoại', 'nhoại', 'quoại', 'roại', 'soại', 'toại', 'thoại', 'troại', 'voại', 'xoại', 'oam', 'coam', 'choam', 'doam', 'đoam', 'moam', 'noam', 'ngoam', 'nhoam', 'quoam', 'roam', 'soam', 'toam', 'thoam', 'troam', 'voam', 'xoam', 'oàm', 'coàm', 'choàm', 'doàm', 'đoàm', 'moàm', 'noàm', 'ngoàm', 'nhoàm', 'quoàm', 'roàm', 'soàm', 'toàm', 'thoàm', 'troàm', 'voàm', 'xoàm', 'oảm', 'coảm', 'choảm', 'doảm', 'đoảm', 'moảm', 'noảm', 'ngoảm', 'nhoảm', 'quoảm', 'roảm', 'soảm', 'toảm', 'thoảm', 'troảm', 'voảm', 'xoảm', 'oãm', 'coãm', 'choãm', 'doãm', 'đoãm', 'moãm', 'noãm', 'ngoãm', 'nhoãm', 'quoãm', 'roãm', 'soãm', 'toãm', 'thoãm', 'troãm', 'voãm', 'xoãm', 'oám', 'coám', 'choám', 'doám', 'đoám', 'moám', 'noám', 'ngoám', 'nhoám', 'quoám', 'roám', 'soám', 'toám', 'thoám', 'troám', 'voám', 'xoám', 'oạm', 'coạm', 'choạm', 'doạm', 'đoạm', 'moạm', 'noạm', 'ngoạm', 'nhoạm', 'quoạm', 'roạm', 'soạm', 'toạm', 'thoạm', 'troạm', 'voạm', 'xoạm', 'oan', 'coan', 'choan', 'doan', 'đoan', 'hoan', 'khoan', 'loan', 'moan', 'noan', 'ngoan', 'nhoan', 'quoan', 'roan', 'soan', 'toan', 'thoan', 'troan', 'voan', 'xoan', 'oàn', 'coàn', 'choàn', 'doàn', 'đoàn', 'hoàn', 'loàn', 'moàn', 'noàn', 'ngoàn', 'nhoàn', 'quoàn', 'roàn', 'soàn', 'toàn', 'thoàn', 'troàn', 'voàn', 'xoàn', 'oản', 'coản', 'choản', 'doản', 'đoản', 'khoản', 'moản', 'noản', 'ngoản', 'nhoản', 'quoản', 'roản', 'soản', 'toản', 'thoản', 'troản', 'voản', 'xoản', 'oãn', 'coãn', 'choãn', 'doãn', 'đoãn', 'hoãn', 'moãn', 'noãn', 'ngoãn', 'nhoãn', 'quoãn', 'roãn', 'soãn', 'toãn', 'thoãn', 'troãn', 'voãn', 'xoãn', 'oán', 'coán', 'choán', 'doán', 'đoán', 'hoán', 'khoán', 'moán', 'noán', 'ngoán', 'nhoán', 'quoán', 'roán', 'soán', 'toán', 'thoán', 'troán', 'voán', 'xoán', 'oạn', 'coạn', 'choạn', 'doạn', 'đoạn', 'hoạn', 'loạn', 'moạn', 'noạn', 'ngoạn', 'nhoạn', 'quoạn', 'roạn', 'soạn', 'toạn', 'thoạn', 'troạn', 'voạn', 'xoạn', 'oat', 'coat', 'choat', 'doat', 'đoat', 'moat', 'noat', 'ngoat', 'nhoat', 'quoat', 'roat', 'soat', 'toat', 'thoat', 'troat', 'voat', 'xoat', 'oàt', 'coàt', 'choàt', 'doàt', 'đoàt', 'moàt', 'noàt', 'ngoàt', 'nhoàt', 'quoàt', 'roàt', 'soàt', 'toàt', 'thoàt', 'troàt', 'voàt', 'xoàt', 'oảt', 'coảt', 'choảt', 'doảt', 'đoảt', 'moảt', 'noảt', 'ngoảt', 'nhoảt', 'quoảt', 'roảt', 'soảt', 'toảt', 'thoảt', 'troảt', 'voảt', 'xoảt', 'oãt', 'coãt', 'choãt', 'doãt', 'đoãt', 'moãt', 'noãt', 'ngoãt', 'nhoãt', 'quoãt', 'roãt', 'soãt', 'toãt', 'thoãt', 'troãt', 'voãt', 'xoãt', 'oát', 'coát', 'choát', 'doát', 'đoát', 'khoát', 'loát', 'moát', 'noát', 'ngoát', 'nhoát', 'quoát', 'roát', 'soát', 'toát', 'thoát', 'troát', 'voát', 'xoát', 'oạt', 'coạt', 'choạt', 'doạt', 'đoạt', 'hoạt', 'loạt', 'moạt', 'noạt', 'ngoạt', 'nhoạt', 'quoạt', 'roạt', 'soạt', 'toạt', 'thoạt', 'troạt', 'voạt', 'xoạt', 'oay', 'coay', 'choay', 'doay', 'đoay', 'hoay', 'loay', 'moay', 'noay', 'ngoay', 'nhoay', 'quoay', 'roay', 'soay', 'toay', 'thoay', 'troay', 'voay', 'xoay', 'oày', 'coày', 'choày', 'doày', 'đoày', 'moày', 'noày', 'ngoày', 'nhoày', 'quoày', 'roày', 'soày', 'toày', 'thoày', 'troày', 'voày', 'xoày', 'oảy', 'coảy', 'choảy', 'doảy', 'đoảy', 'moảy', 'noảy', 'ngoảy', 'nhoảy', 'quoảy', 'roảy', 'soảy', 'toảy', 'thoảy', 'troảy', 'voảy', 'xoảy', 'oãy', 'coãy', 'choãy', 'doãy', 'đoãy', 'moãy', 'noãy', 'ngoãy', 'nhoãy', 'quoãy', 'roãy', 'soãy', 'toãy', 'thoãy', 'troãy', 'voãy', 'xoãy', 'oáy', 'coáy', 'choáy', 'doáy', 'đoáy', 'hoáy', 'khoáy', 'moáy', 'noáy', 'ngoáy', 'nhoáy', 'quoáy', 'roáy', 'soáy', 'toáy', 'thoáy', 'troáy', 'voáy', 'xoáy', 'oạy', 'coạy', 'choạy', 'doạy', 'đoạy', 'moạy', 'noạy', 'ngoạy', 'nhoạy', 'quoạy', 'roạy', 'soạy', 'toạy', 'thoạy', 'troạy', 'voạy', 'xoạy', 'oăc', 'coăc', 'choăc', 'doăc', 'đoăc', 'moăc', 'noăc', 'ngoăc', 'nhoăc', 'quoăc', 'roăc', 'soăc', 'toăc', 'thoăc', 'troăc', 'voăc', 'xoăc', 'oằc', 'coằc', 'choằc', 'doằc', 'đoằc', 'moằc', 'noằc', 'ngoằc', 'nhoằc', 'quoằc', 'roằc', 'soằc', 'toằc', 'thoằc', 'troằc', 'voằc', 'xoằc', 'oẳc', 'coẳc', 'choẳc', 'doẳc', 'đoẳc', 'moẳc', 'noẳc', 'ngoẳc', 'nhoẳc', 'quoẳc', 'roẳc', 'soẳc', 'toẳc', 'thoẳc', 'troẳc', 'voẳc', 'xoẳc', 'oẵc', 'coẵc', 'choẵc', 'doẵc', 'đoẵc', 'moẵc', 'noẵc', 'ngoẵc', 'nhoẵc', 'quoẵc', 'roẵc', 'soẵc', 'toẵc', 'thoẵc', 'troẵc', 'voẵc', 'xoẵc', 'oắc', 'coắc', 'choắc', 'doắc', 'đoắc', 'hoắc', 'moắc', 'noắc', 'ngoắc', 'nhoắc', 'quoắc', 'roắc', 'soắc', 'toắc', 'thoắc', 'troắc', 'voắc', 'xoắc', 'oặc', 'coặc', 'choặc', 'doặc', 'đoặc', 'hoặc', 'moặc', 'noặc', 'ngoặc', 'nhoặc', 'quoặc', 'roặc', 'soặc', 'toặc', 'thoặc', 'troặc', 'voặc', 'xoặc', 'oăm', 'coăm', 'choăm', 'doăm', 'đoăm', 'moăm', 'noăm', 'ngoăm', 'nhoăm', 'quoăm', 'roăm', 'soăm', 'toăm', 'thoăm', 'troăm', 'voăm', 'xoăm', 'oằm', 'coằm', 'choằm', 'doằm', 'đoằm', 'moằm', 'noằm', 'ngoằm', 'nhoằm', 'quoằm', 'roằm', 'soằm', 'toằm', 'thoằm', 'troằm', 'voằm', 'xoằm', 'oẳm', 'coẳm', 'choẳm', 'doẳm', 'đoẳm', 'moẳm', 'noẳm', 'ngoẳm', 'nhoẳm', 'quoẳm', 'roẳm', 'soẳm', 'toẳm', 'thoẳm', 'troẳm', 'voẳm', 'xoẳm', 'oẵm', 'coẵm', 'choẵm', 'doẵm', 'đoẵm', 'moẵm', 'noẵm', 'ngoẵm', 'nhoẵm', 'quoẵm', 'roẵm', 'soẵm', 'toẵm', 'thoẵm', 'troẵm', 'voẵm', 'xoẵm', 'oắm', 'coắm', 'choắm', 'doắm', 'đoắm', 'moắm', 'noắm', 'ngoắm', 'nhoắm', 'quoắm', 'roắm', 'soắm', 'toắm', 'thoắm', 'troắm', 'voắm', 'xoắm', 'oặm', 'coặm', 'choặm', 'doặm', 'đoặm', 'moặm', 'noặm', 'ngoặm', 'nhoặm', 'quoặm', 'roặm', 'soặm', 'toặm', 'thoặm', 'troặm', 'voặm', 'xoặm', 'oăn', 'coăn', 'choăn', 'doăn', 'đoăn', 'khoăn', 'loăn', 'moăn', 'noăn', 'ngoăn', 'nhoăn', 'quoăn', 'roăn', 'soăn', 'toăn', 'thoăn', 'troăn', 'voăn', 'xoăn', 'oằn', 'coằn', 'choằn', 'doằn', 'đoằn', 'moằn', 'noằn', 'ngoằn', 'nhoằn', 'quoằn', 'roằn', 'soằn', 'toằn', 'thoằn', 'troằn', 'voằn', 'xoằn', 'oẳn', 'coẳn', 'choẳn', 'doẳn', 'đoẳn', 'hoẳn', 'moẳn', 'noẳn', 'ngoẳn', 'nhoẳn', 'quoẳn', 'roẳn', 'soẳn', 'toẳn', 'thoẳn', 'troẳn', 'voẳn', 'xoẳn', 'oẵn', 'coẵn', 'choẵn', 'doẵn', 'đoẵn', 'moẵn', 'noẵn', 'ngoẵn', 'nhoẵn', 'quoẵn', 'roẵn', 'soẵn', 'toẵn', 'thoẵn', 'troẵn', 'voẵn', 'xoẵn', 'oắn', 'coắn', 'choắn', 'doắn', 'đoắn', 'moắn', 'noắn', 'ngoắn', 'nhoắn', 'quoắn', 'roắn', 'soắn', 'toắn', 'thoắn', 'troắn', 'voắn', 'xoắn', 'oặn', 'coặn', 'choặn', 'doặn', 'đoặn', 'moặn', 'noặn', 'ngoặn', 'nhoặn', 'quoặn', 'roặn', 'soặn', 'toặn', 'thoặn', 'troặn', 'voặn', 'xoặn', 'oăt', 'coăt', 'choăt', 'doăt', 'đoăt', 'moăt', 'noăt', 'ngoăt', 'nhoăt', 'quoăt', 'roăt', 'soăt', 'toăt', 'thoăt', 'troăt', 'voăt', 'xoăt', 'oằt', 'coằt', 'choằt', 'doằt', 'đoằt', 'moằt', 'noằt', 'ngoằt', 'nhoằt', 'quoằt', 'roằt', 'soằt', 'toằt', 'thoằt', 'troằt', 'voằt', 'xoằt', 'oẳt', 'coẳt', 'choẳt', 'doẳt', 'đoẳt', 'moẳt', 'noẳt', 'ngoẳt', 'nhoẳt', 'quoẳt', 'roẳt', 'soẳt', 'toẳt', 'thoẳt', 'troẳt', 'voẳt', 'xoẳt', 'oẵt', 'coẵt', 'choẵt', 'doẵt', 'đoẵt', 'moẵt', 'noẵt', 'ngoẵt', 'nhoẵt', 'quoẵt', 'roẵt', 'soẵt', 'toẵt', 'thoẵt', 'troẵt', 'voẵt', 'xoẵt', 'oắt', 'coắt', 'choắt', 'doắt', 'đoắt', 'loắt', 'moắt', 'noắt', 'ngoắt', 'nhoắt', 'quoắt', 'roắt', 'soắt', 'toắt', 'thoắt', 'troắt', 'voắt', 'xoắt', 'oặt', 'coặt', 'choặt', 'doặt', 'đoặt', 'moặt', 'noặt', 'ngoặt', 'nhoặt', 'quoặt', 'roặt', 'soặt', 'toặt', 'thoặt', 'troặt', 'voặt', 'xoặt', 'oen', 'coen', 'choen', 'doen', 'đoen', 'khoen', 'moen', 'noen', 'ngoen', 'nhoen', 'quoen', 'roen', 'soen', 'toen', 'thoen', 'troen', 'voen', 'xoen', 'oèn', 'coèn', 'choèn', 'doèn', 'đoèn', 'moèn', 'noèn', 'ngoèn', 'nhoèn', 'quoèn', 'roèn', 'soèn', 'toèn', 'thoèn', 'troèn', 'voèn', 'xoèn', 'oẻn', 'coẻn', 'choẻn', 'doẻn', 'đoẻn', 'moẻn', 'noẻn', 'ngoẻn', 'nhoẻn', 'quoẻn', 'roẻn', 'soẻn', 'toẻn', 'thoẻn', 'troẻn', 'voẻn', 'xoẻn', 'oẽn', 'coẽn', 'choẽn', 'doẽn', 'đoẽn', 'moẽn', 'noẽn', 'ngoẽn', 'nhoẽn', 'quoẽn', 'roẽn', 'soẽn', 'toẽn', 'thoẽn', 'troẽn', 'voẽn', 'xoẽn', 'oén', 'coén', 'choén', 'doén', 'đoén', 'moén', 'noén', 'ngoén', 'nhoén', 'quoén', 'roén', 'soén', 'toén', 'thoén', 'troén', 'voén', 'xoén', 'oẹn', 'coẹn', 'choẹn', 'doẹn', 'đoẹn', 'moẹn', 'noẹn', 'ngoẹn', 'nhoẹn', 'quoẹn', 'roẹn', 'soẹn', 'toẹn', 'thoẹn', 'troẹn', 'voẹn', 'xoẹn', 'oeo', 'coeo', 'choeo', 'doeo', 'đoeo', 'khoeo', 'moeo', 'noeo', 'ngoeo', 'nhoeo', 'quoeo', 'roeo', 'soeo', 'toeo', 'thoeo', 'troeo', 'voeo', 'xoeo', 'oèo', 'coèo', 'choèo', 'doèo', 'đoèo', 'khoèo', 'moèo', 'noèo', 'ngoèo', 'nhoèo', 'quoèo', 'roèo', 'soèo', 'toèo', 'thoèo', 'troèo', 'voèo', 'xoèo', 'oẻo', 'coẻo', 'choẻo', 'doẻo', 'đoẻo', 'khoẻo', 'moẻo', 'noẻo', 'ngoẻo', 'nhoẻo', 'quoẻo', 'roẻo', 'soẻo', 'toẻo', 'thoẻo', 'troẻo', 'voẻo', 'xoẻo', 'oẽo', 'coẽo', 'choẽo', 'doẽo', 'đoẽo', 'moẽo', 'noẽo', 'ngoẽo', 'nhoẽo', 'quoẽo', 'roẽo', 'soẽo', 'toẽo', 'thoẽo', 'troẽo', 'voẽo', 'xoẽo', 'oéo', 'coéo', 'choéo', 'doéo', 'đoéo', 'moéo', 'noéo', 'ngoéo', 'nhoéo', 'quoéo', 'roéo', 'soéo', 'toéo', 'thoéo', 'troéo', 'voéo', 'xoéo', 'oẹo', 'coẹo', 'choẹo', 'doẹo', 'đoẹo', 'moẹo', 'noẹo', 'ngoẹo', 'nhoẹo', 'quoẹo', 'roẹo', 'soẹo', 'toẹo', 'thoẹo', 'troẹo', 'voẹo', 'xoẹo', 'oet', 'coet', 'choet', 'doet', 'đoet', 'moet', 'noet', 'ngoet', 'nhoet', 'quoet', 'roet', 'soet', 'toet', 'thoet', 'troet', 'voet', 'xoet', 'oèt', 'coèt', 'choèt', 'doèt', 'đoèt', 'moèt', 'noèt', 'ngoèt', 'nhoèt', 'quoèt', 'roèt', 'soèt', 'toèt', 'thoèt', 'troèt', 'voèt', 'xoèt', 'oẻt', 'coẻt', 'choẻt', 'doẻt', 'đoẻt', 'moẻt', 'noẻt', 'ngoẻt', 'nhoẻt', 'quoẻt', 'roẻt', 'soẻt', 'toẻt', 'thoẻt', 'troẻt', 'voẻt', 'xoẻt', 'oẽt', 'coẽt', 'choẽt', 'doẽt', 'đoẽt', 'moẽt', 'noẽt', 'ngoẽt', 'nhoẽt', 'quoẽt', 'roẽt', 'soẽt', 'toẽt', 'thoẽt', 'troẽt', 'voẽt', 'xoẽt', 'oét', 'coét', 'choét', 'doét', 'đoét', 'khoét', 'loét', 'moét', 'noét', 'ngoét', 'nhoét', 'quoét', 'roét', 'soét', 'toét', 'thoét', 'troét', 'voét', 'xoét', 'oẹt', 'coẹt', 'choẹt', 'doẹt', 'đoẹt', 'loẹt', 'moẹt', 'noẹt', 'ngoẹt', 'nhoẹt', 'quoẹt', 'roẹt', 'soẹt', 'toẹt', 'thoẹt', 'troẹt', 'voẹt', 'xoẹt', 'ong', 'bong', 'cong', 'chong', 'dong', 'đong', 'giong', 'hong', 'long', 'mong', 'nong', 'ngong', 'nhong', 'phong', 'quong', 'rong', 'song', 'tong', 'thong', 'trong', 'vong', 'xong', 'òng', 'bòng', 'còng', 'chòng', 'dòng', 'đòng', 'hòng', 'lòng', 'mòng', 'nòng', 'ngòng', 'nhòng', 'phòng', 'quòng', 'ròng', 'sòng', 'tòng', 'thòng', 'tròng', 'vòng', 'xòng', 'ỏng', 'bỏng', 'cỏng', 'chỏng', 'dỏng', 'đỏng', 'gỏng', 'hỏng', 'lỏng', 'mỏng', 'nỏng', 'ngỏng', 'nhỏng', 'phỏng', 'quỏng', 'rỏng', 'sỏng', 'tỏng', 'thỏng', 'trỏng', 'vỏng', 'xỏng', 'õng', 'bõng', 'cõng', 'chõng', 'dõng', 'đõng', 'lõng', 'mõng', 'nõng', 'ngõng', 'nhõng', 'quõng', 'rõng', 'sõng', 'tõng', 'thõng', 'trõng', 'võng', 'xõng', 'óng', 'bóng', 'cóng', 'chóng', 'dóng', 'đóng', 'gióng', 'hóng', 'lóng', 'móng', 'nóng', 'ngóng', 'nhóng', 'phóng', 'quóng', 'róng', 'sóng', 'tóng', 'thóng', 'tróng', 'vóng', 'xóng', 'ọng', 'bọng', 'cọng', 'chọng', 'dọng', 'đọng', 'gọng', 'giọng', 'họng', 'lọng', 'mọng', 'nọng', 'ngọng', 'nhọng', 'quọng', 'rọng', 'sọng', 'tọng', 'thọng', 'trọng', 'vọng', 'xọng', 'ông', 'bông', 'công', 'chông', 'dông', 'đông', 'gông', 'giông', 'hông', 'không', 'lông', 'mông', 'nông', 'ngông', 'nhông', 'phông', 'quông', 'rông', 'sông', 'tông', 'thông', 'trông', 'vông', 'xông', 'ồng', 'bồng', 'cồng', 'chồng', 'dồng', 'đồng', 'gồng', 'hồng', 'lồng', 'mồng', 'nồng', 'ngồng', 'nhồng', 'phồng', 'quồng', 'rồng', 'sồng', 'tồng', 'thồng', 'trồng', 'vồng', 'xồng', 'ổng', 'bổng', 'cổng', 'chổng', 'dổng', 'đổng', 'hổng', 'khổng', 'lổng', 'mổng', 'nổng', 'ngổng', 'nhổng', 'phổng', 'quổng', 'rổng', 'sổng', 'tổng', 'thổng', 'trổng', 'vổng', 'xổng', 'ỗng', 'bỗng', 'cỗng', 'chỗng', 'dỗng', 'đỗng', 'mỗng', 'nỗng', 'ngỗng', 'nhỗng', 'phỗng', 'quỗng', 'rỗng', 'sỗng', 'tỗng', 'thỗng', 'trỗng', 'vỗng', 'xỗng', 'ống', 'bống', 'cống', 'chống', 'dống', 'đống', 'giống', 'hống', 'khống', 'mống', 'nống', 'ngống', 'nhống', 'quống', 'rống', 'sống', 'tống', 'thống', 'trống', 'vống', 'xống', 'ộng', 'bộng', 'cộng', 'chộng', 'dộng', 'động', 'lộng', 'mộng', 'nộng', 'ngộng', 'nhộng', 'quộng', 'rộng', 'sộng', 'tộng', 'thộng', 'trộng', 'vộng', 'xộng', 'uân', 'cuân', 'chuân', 'duân', 'đuân', 'huân', 'khuân', 'luân', 'muân', 'nuân', 'nguân', 'nhuân', 'quuân', 'ruân', 'suân', 'tuân', 'thuân', 'truân', 'vuân', 'xuân', 'uần', 'cuần', 'chuần', 'duần', 'đuần', 'muần', 'nuần', 'nguần', 'nhuần', 'quuần', 'ruần', 'suần', 'tuần', 'thuần', 'truần', 'vuần', 'xuần', 'uẩn', 'cuẩn', 'chuẩn', 'duẩn', 'đuẩn', 'khuẩn', 'luẩn', 'muẩn', 'nuẩn', 'nguẩn', 'nhuẩn', 'quuẩn', 'ruẩn', 'suẩn', 'tuẩn', 'thuẩn', 'truẩn', 'vuẩn', 'xuẩn', 'uẫn', 'cuẫn', 'chuẫn', 'duẫn', 'đuẫn', 'muẫn', 'nuẫn', 'nguẫn', 'nhuẫn', 'quuẫn', 'ruẫn', 'suẫn', 'tuẫn', 'thuẫn', 'truẫn', 'vuẫn', 'xuẫn', 'uấn', 'cuấn', 'chuấn', 'duấn', 'đuấn', 'huấn', 'luấn', 'muấn', 'nuấn', 'nguấn', 'nhuấn', 'quuấn', 'ruấn', 'suấn', 'tuấn', 'thuấn', 'truấn', 'vuấn', 'xuấn', 'uận', 'cuận', 'chuận', 'duận', 'đuận', 'luận', 'muận', 'nuận', 'nguận', 'nhuận', 'quuận', 'ruận', 'suận', 'tuận', 'thuận', 'truận', 'vuận', 'xuận', 'uât', 'cuât', 'chuât', 'duât', 'đuât', 'muât', 'nuât', 'nguât', 'nhuât', 'quuât', 'ruât', 'suât', 'tuât', 'thuât', 'truât', 'vuât', 'xuât', 'uầt', 'cuầt', 'chuầt', 'duầt', 'đuầt', 'muầt', 'nuầt', 'nguầt', 'nhuầt', 'quuầt', 'ruầt', 'suầt', 'tuầt', 'thuầt', 'truầt', 'vuầt', 'xuầt', 'uẩt', 'cuẩt', 'chuẩt', 'duẩt', 'đuẩt', 'muẩt', 'nuẩt', 'nguẩt', 'nhuẩt', 'quuẩt', 'ruẩt', 'suẩt', 'tuẩt', 'thuẩt', 'truẩt', 'vuẩt', 'xuẩt', 'uẫt', 'cuẫt', 'chuẫt', 'duẫt', 'đuẫt', 'muẫt', 'nuẫt', 'nguẫt', 'nhuẫt', 'quuẫt', 'ruẫt', 'suẫt', 'tuẫt', 'thuẫt', 'truẫt', 'vuẫt', 'xuẫt', 'uất', 'cuất', 'chuất', 'duất', 'đuất', 'khuất', 'muất', 'nuất', 'nguất', 'nhuất', 'quuất', 'ruất', 'suất', 'tuất', 'thuất', 'truất', 'vuất', 'xuất', 'uật', 'cuật', 'chuật', 'duật', 'đuật', 'luật', 'muật', 'nuật', 'nguật', 'nhuật', 'quuật', 'ruật', 'suật', 'tuật', 'thuật', 'truật', 'vuật', 'xuật', 'uây', 'cuây', 'chuây', 'duây', 'đuây', 'khuây', 'muây', 'nuây', 'nguây', 'nhuây', 'quuây', 'ruây', 'suây', 'tuây', 'thuây', 'truây', 'vuây', 'xuây', 'uầy', 'cuầy', 'chuầy', 'duầy', 'đuầy', 'muầy', 'nuầy', 'nguầy', 'nhuầy', 'quuầy', 'ruầy', 'suầy', 'tuầy', 'thuầy', 'truầy', 'vuầy', 'xuầy', 'uẩy', 'cuẩy', 'chuẩy', 'duẩy', 'đuẩy', 'muẩy', 'nuẩy', 'nguẩy', 'nhuẩy', 'quuẩy', 'ruẩy', 'suẩy', 'tuẩy', 'thuẩy', 'truẩy', 'vuẩy', 'xuẩy', 'uẫy', 'cuẫy', 'chuẫy', 'duẫy', 'đuẫy', 'muẫy', 'nuẫy', 'nguẫy', 'nhuẫy', 'quuẫy', 'ruẫy', 'suẫy', 'tuẫy', 'thuẫy', 'truẫy', 'vuẫy', 'xuẫy', 'uấy', 'cuấy', 'chuấy', 'duấy', 'đuấy', 'khuấy', 'muấy', 'nuấy', 'nguấy', 'nhuấy', 'quuấy', 'ruấy', 'suấy', 'tuấy', 'thuấy', 'truấy', 'vuấy', 'xuấy', 'uậy', 'cuậy', 'chuậy', 'duậy', 'đuậy', 'muậy', 'nuậy', 'nguậy', 'nhuậy', 'quuậy', 'ruậy', 'suậy', 'tuậy', 'thuậy', 'truậy', 'vuậy', 'xuậy', 'ung', 'bung', 'cung', 'chung', 'dung', 'đung', 'hung', 'khung', 'lung', 'mung', 'nung', 'ngung', 'nhung', 'phung', 'quung', 'rung', 'sung', 'tung', 'thung', 'trung', 'vung', 'xung', 'ùng', 'bùng', 'cùng', 'chùng', 'dùng', 'đùng', 'hùng', 'khùng', 'lùng', 'mùng', 'nùng', 'ngùng', 'nhùng', 'phùng', 'quùng', 'rùng', 'sùng', 'tùng', 'thùng', 'trùng', 'vùng', 'xùng', 'ủng', 'bủng', 'củng', 'chủng', 'dủng', 'đủng', 'khủng', 'lủng', 'mủng', 'nủng', 'ngủng', 'nhủng', 'quủng', 'rủng', 'sủng', 'tủng', 'thủng', 'trủng', 'vủng', 'xủng', 'ũng', 'cũng', 'chũng', 'dũng', 'đũng', 'lũng', 'mũng', 'nũng', 'ngũng', 'nhũng', 'quũng', 'rũng', 'sũng', 'tũng', 'thũng', 'trũng', 'vũng', 'xũng', 'úng', 'búng', 'cúng', 'chúng', 'dúng', 'đúng', 'húng', 'khúng', 'lúng', 'múng', 'núng', 'ngúng', 'nhúng', 'phúng', 'quúng', 'rúng', 'súng', 'túng', 'thúng', 'trúng', 'vúng', 'xúng', 'ụng', 'bụng', 'cụng', 'chụng', 'dụng', 'đụng', 'khụng', 'lụng', 'mụng', 'nụng', 'ngụng', 'nhụng', 'phụng', 'quụng', 'rụng', 'sụng', 'tụng', 'thụng', 'trụng', 'vụng', 'xụng', 'uôc', 'cuôc', 'chuôc', 'duôc', 'đuôc', 'muôc', 'nuôc', 'nguôc', 'nhuôc', 'quuôc', 'ruôc', 'suôc', 'tuôc', 'thuôc', 'truôc', 'vuôc', 'xuôc', 'uồc', 'cuồc', 'chuồc', 'duồc', 'đuồc', 'muồc', 'nuồc', 'nguồc', 'nhuồc', 'quuồc', 'ruồc', 'suồc', 'tuồc', 'thuồc', 'truồc', 'vuồc', 'xuồc', 'uổc', 'cuổc', 'chuổc', 'duổc', 'đuổc', 'muổc', 'nuổc', 'nguổc', 'nhuổc', 'quuổc', 'ruổc', 'suổc', 'tuổc', 'thuổc', 'truổc', 'vuổc', 'xuổc', 'uỗc', 'cuỗc', 'chuỗc', 'duỗc', 'đuỗc', 'muỗc', 'nuỗc', 'nguỗc', 'nhuỗc', 'quuỗc', 'ruỗc', 'suỗc', 'tuỗc', 'thuỗc', 'truỗc', 'vuỗc', 'xuỗc', 'uốc', 'cuốc', 'chuốc', 'duốc', 'đuốc', 'guốc', 'luốc', 'muốc', 'nuốc', 'nguốc', 'nhuốc', 'quuốc', 'ruốc', 'suốc', 'tuốc', 'thuốc', 'truốc', 'vuốc', 'xuốc', 'uộc', 'buộc', 'cuộc', 'chuộc', 'duộc', 'đuộc', 'guộc', 'giuộc', 'luộc', 'muộc', 'nuộc', 'nguộc', 'nhuộc', 'phuộc', 'quuộc', 'ruộc', 'suộc', 'tuộc', 'thuộc', 'truộc', 'vuộc', 'xuộc', 'uôi', 'cuôi', 'chuôi', 'duôi', 'đuôi', 'muôi', 'nuôi', 'nguôi', 'nhuôi', 'quuôi', 'ruôi', 'suôi', 'tuôi', 'thuôi', 'truôi', 'vuôi', 'xuôi', 'uồi', 'cuồi', 'chuồi', 'duồi', 'đuồi', 'muồi', 'nuồi', 'nguồi', 'nhuồi', 'quuồi', 'ruồi', 'suồi', 'tuồi', 'thuồi', 'truồi', 'vuồi', 'xuồi', 'uổi', 'buổi', 'cuổi', 'chuổi', 'duổi', 'đuổi', 'muổi', 'nuổi', 'nguổi', 'nhuổi', 'quuổi', 'ruổi', 'suổi', 'tuổi', 'thuổi', 'truổi', 'vuổi', 'xuổi', 'uỗi', 'cuỗi', 'chuỗi', 'duỗi', 'đuỗi', 'muỗi', 'nuỗi', 'nguỗi', 'nhuỗi', 'quuỗi', 'ruỗi', 'suỗi', 'tuỗi', 'thuỗi', 'truỗi', 'vuỗi', 'xuỗi', 'uối', 'cuối', 'chuối', 'duối', 'đuối', 'muối', 'nuối', 'nguối', 'nhuối', 'quuối', 'ruối', 'suối', 'tuối', 'thuối', 'truối', 'vuối', 'xuối', 'uội', 'cuội', 'chuội', 'duội', 'đuội', 'muội', 'nuội', 'nguội', 'nhuội', 'quuội', 'ruội', 'suội', 'tuội', 'thuội', 'truội', 'vuội', 'xuội', 'uôm', 'cuôm', 'chuôm', 'duôm', 'đuôm', 'luôm', 'muôm', 'nuôm', 'nguôm', 'nhuôm', 'quuôm', 'ruôm', 'suôm', 'tuôm', 'thuôm', 'truôm', 'vuôm', 'xuôm', 'uồm', 'buồm', 'cuồm', 'chuồm', 'duồm', 'đuồm', 'muồm', 'nuồm', 'nguồm', 'nhuồm', 'quuồm', 'ruồm', 'suồm', 'tuồm', 'thuồm', 'truồm', 'vuồm', 'xuồm', 'uổm', 'cuổm', 'chuổm', 'duổm', 'đuổm', 'muổm', 'nuổm', 'nguổm', 'nhuổm', 'quuổm', 'ruổm', 'suổm', 'tuổm', 'thuổm', 'truổm', 'vuổm', 'xuổm', 'uỗm', 'cuỗm', 'chuỗm', 'duỗm', 'đuỗm', 'muỗm', 'nuỗm', 'nguỗm', 'nhuỗm', 'quuỗm', 'ruỗm', 'suỗm', 'tuỗm', 'thuỗm', 'truỗm', 'vuỗm', 'xuỗm', 'uốm', 'cuốm', 'chuốm', 'duốm', 'đuốm', 'muốm', 'nuốm', 'nguốm', 'nhuốm', 'quuốm', 'ruốm', 'suốm', 'tuốm', 'thuốm', 'truốm', 'vuốm', 'xuốm', 'uộm', 'cuộm', 'chuộm', 'duộm', 'đuộm', 'luộm', 'muộm', 'nuộm', 'nguộm', 'nhuộm', 'quuộm', 'ruộm', 'suộm', 'tuộm', 'thuộm', 'truộm', 'vuộm', 'xuộm', 'uôn', 'buôn', 'cuôn', 'chuôn', 'duôn', 'đuôn', 'khuôn', 'luôn', 'muôn', 'nuôn', 'nguôn', 'nhuôn', 'quuôn', 'ruôn', 'suôn', 'tuôn', 'thuôn', 'truôn', 'vuôn', 'xuôn', 'uồn', 'buồn', 'cuồn', 'chuồn', 'duồn', 'đuồn', 'luồn', 'muồn', 'nuồn', 'nguồn', 'nhuồn', 'quuồn', 'ruồn', 'suồn', 'tuồn', 'thuồn', 'truồn', 'vuồn', 'xuồn', 'uổn', 'cuổn', 'chuổn', 'duổn', 'đuổn', 'muổn', 'nuổn', 'nguổn', 'nhuổn', 'quuổn', 'ruổn', 'suổn', 'tuổn', 'thuổn', 'truổn', 'vuổn', 'xuổn', 'uỗn', 'cuỗn', 'chuỗn', 'duỗn', 'đuỗn', 'muỗn', 'nuỗn', 'nguỗn', 'nhuỗn', 'quuỗn', 'ruỗn', 'suỗn', 'tuỗn', 'thuỗn', 'truỗn', 'vuỗn', 'xuỗn', 'uốn', 'cuốn', 'chuốn', 'duốn', 'đuốn', 'muốn', 'nuốn', 'nguốn', 'nhuốn', 'quuốn', 'ruốn', 'suốn', 'tuốn', 'thuốn', 'truốn', 'vuốn', 'xuốn', 'uộn', 'cuộn', 'chuộn', 'duộn', 'đuộn', 'muộn', 'nuộn', 'nguộn', 'nhuộn', 'quuộn', 'ruộn', 'suộn', 'tuộn', 'thuộn', 'truộn', 'vuộn', 'xuộn', 'uôt', 'cuôt', 'chuôt', 'duôt', 'đuôt', 'muôt', 'nuôt', 'nguôt', 'nhuôt', 'quuôt', 'ruôt', 'suôt', 'tuôt', 'thuôt', 'truôt', 'vuôt', 'xuôt', 'uồt', 'cuồt', 'chuồt', 'duồt', 'đuồt', 'muồt', 'nuồt', 'nguồt', 'nhuồt', 'quuồt', 'ruồt', 'suồt', 'tuồt', 'thuồt', 'truồt', 'vuồt', 'xuồt', 'uổt', 'cuổt', 'chuổt', 'duổt', 'đuổt', 'muổt', 'nuổt', 'nguổt', 'nhuổt', 'quuổt', 'ruổt', 'suổt', 'tuổt', 'thuổt', 'truổt', 'vuổt', 'xuổt', 'uỗt', 'cuỗt', 'chuỗt', 'duỗt', 'đuỗt', 'muỗt', 'nuỗt', 'nguỗt', 'nhuỗt', 'quuỗt', 'ruỗt', 'suỗt', 'tuỗt', 'thuỗt', 'truỗt', 'vuỗt', 'xuỗt', 'uốt', 'buốt', 'cuốt', 'chuốt', 'duốt', 'đuốt', 'luốt', 'muốt', 'nuốt', 'nguốt', 'nhuốt', 'quuốt', 'ruốt', 'suốt', 'tuốt', 'thuốt', 'truốt', 'vuốt', 'xuốt', 'uột', 'buột', 'cuột', 'chuột', 'duột', 'đuột', 'muột', 'nuột', 'nguột', 'nhuột', 'quuột', 'ruột', 'suột', 'tuột', 'thuột', 'truột', 'vuột', 'xuột', 'uya', 'cuya', 'chuya', 'duya', 'đuya', 'khuya', 'muya', 'nuya', 'nguya', 'nhuya', 'quuya', 'ruya', 'suya', 'tuya', 'thuya', 'truya', 'vuya', 'xuya', 'uỳa', 'cuỳa', 'chuỳa', 'duỳa', 'đuỳa', 'muỳa', 'nuỳa', 'nguỳa', 'nhuỳa', 'quuỳa', 'ruỳa', 'suỳa', 'tuỳa', 'thuỳa', 'truỳa', 'vuỳa', 'xuỳa', 'uỷa', 'cuỷa', 'chuỷa', 'duỷa', 'đuỷa', 'muỷa', 'nuỷa', 'nguỷa', 'nhuỷa', 'quuỷa', 'ruỷa', 'suỷa', 'tuỷa', 'thuỷa', 'truỷa', 'vuỷa', 'xuỷa', 'uỹa', 'cuỹa', 'chuỹa', 'duỹa', 'đuỹa', 'muỹa', 'nuỹa', 'nguỹa', 'nhuỹa', 'quuỹa', 'ruỹa', 'suỹa', 'tuỹa', 'thuỹa', 'truỹa', 'vuỹa', 'xuỹa', 'uýa', 'cuýa', 'chuýa', 'duýa', 'đuýa', 'muýa', 'nuýa', 'nguýa', 'nhuýa', 'quuýa', 'ruýa', 'suýa', 'tuýa', 'thuýa', 'truýa', 'vuýa', 'xuýa', 'uỵa', 'cuỵa', 'chuỵa', 'duỵa', 'đuỵa', 'muỵa', 'nuỵa', 'nguỵa', 'nhuỵa', 'quuỵa', 'ruỵa', 'suỵa', 'tuỵa', 'thuỵa', 'truỵa', 'vuỵa', 'xuỵa', 'uyt', 'cuyt', 'chuyt', 'duyt', 'đuyt', 'muyt', 'nuyt', 'nguyt', 'nhuyt', 'quuyt', 'ruyt', 'suyt', 'tuyt', 'thuyt', 'truyt', 'vuyt', 'xuyt', 'uỳt', 'cuỳt', 'chuỳt', 'duỳt', 'đuỳt', 'muỳt', 'nuỳt', 'nguỳt', 'nhuỳt', 'quuỳt', 'ruỳt', 'suỳt', 'tuỳt', 'thuỳt', 'truỳt', 'vuỳt', 'xuỳt', 'uỷt', 'cuỷt', 'chuỷt', 'duỷt', 'đuỷt', 'muỷt', 'nuỷt', 'nguỷt', 'nhuỷt', 'quuỷt', 'ruỷt', 'suỷt', 'tuỷt', 'thuỷt', 'truỷt', 'vuỷt', 'xuỷt', 'uỹt', 'cuỹt', 'chuỹt', 'duỹt', 'đuỹt', 'muỹt', 'nuỹt', 'nguỹt', 'nhuỹt', 'quuỹt', 'ruỹt', 'suỹt', 'tuỹt', 'thuỹt', 'truỹt', 'vuỹt', 'xuỹt', 'uýt', 'buýt', 'cuýt', 'chuýt', 'duýt', 'đuýt', 'huýt', 'muýt', 'nuýt', 'nguýt', 'nhuýt', 'quuýt', 'ruýt', 'suýt', 'tuýt', 'thuýt', 'truýt', 'vuýt', 'xuýt', 'uỵt', 'cuỵt', 'chuỵt', 'duỵt', 'đuỵt', 'muỵt', 'nuỵt', 'nguỵt', 'nhuỵt', 'quuỵt', 'ruỵt', 'suỵt', 'tuỵt', 'thuỵt', 'truỵt', 'vuỵt', 'xuỵt', 'uyu', 'cuyu', 'chuyu', 'duyu', 'đuyu', 'muyu', 'nuyu', 'nguyu', 'nhuyu', 'quuyu', 'ruyu', 'suyu', 'tuyu', 'thuyu', 'truyu', 'vuyu', 'xuyu', 'uỳu', 'cuỳu', 'chuỳu', 'duỳu', 'đuỳu', 'muỳu', 'nuỳu', 'nguỳu', 'nhuỳu', 'quuỳu', 'ruỳu', 'suỳu', 'tuỳu', 'thuỳu', 'truỳu', 'vuỳu', 'xuỳu', 'uỷu', 'cuỷu', 'chuỷu', 'duỷu', 'đuỷu', 'khuỷu', 'muỷu', 'nuỷu', 'nguỷu', 'nhuỷu', 'quuỷu', 'ruỷu', 'suỷu', 'tuỷu', 'thuỷu', 'truỷu', 'vuỷu', 'xuỷu', 'uỹu', 'cuỹu', 'chuỹu', 'duỹu', 'đuỹu', 'muỹu', 'nuỹu', 'nguỹu', 'nhuỹu', 'quuỹu', 'ruỹu', 'suỹu', 'tuỹu', 'thuỹu', 'truỹu', 'vuỹu', 'xuỹu', 'uýu', 'cuýu', 'chuýu', 'duýu', 'đuýu', 'muýu', 'nuýu', 'nguýu', 'nhuýu', 'quuýu', 'ruýu', 'suýu', 'tuýu', 'thuýu', 'truýu', 'vuýu', 'xuýu', 'uỵu', 'cuỵu', 'chuỵu', 'duỵu', 'đuỵu', 'khuỵu', 'muỵu', 'nuỵu', 'nguỵu', 'nhuỵu', 'quuỵu', 'ruỵu', 'suỵu', 'tuỵu', 'thuỵu', 'truỵu', 'vuỵu', 'xuỵu', 'ưng', 'bưng', 'cưng', 'chưng', 'dưng', 'đưng', 'hưng', 'lưng', 'mưng', 'nưng', 'ngưng', 'nhưng', 'quưng', 'rưng', 'sưng', 'tưng', 'thưng', 'trưng', 'vưng', 'xưng', 'ừng', 'bừng', 'cừng', 'chừng', 'dừng', 'đừng', 'gừng', 'hừng', 'khừng', 'lừng', 'mừng', 'nừng', 'ngừng', 'nhừng', 'quừng', 'rừng', 'sừng', 'từng', 'thừng', 'trừng', 'vừng', 'xừng', 'ửng', 'bửng', 'cửng', 'chửng', 'dửng', 'đửng', 'hửng', 'lửng', 'mửng', 'nửng', 'ngửng', 'nhửng', 'quửng', 'rửng', 'sửng', 'tửng', 'thửng', 'trửng', 'vửng', 'xửng', 'ững', 'cững', 'chững', 'dững', 'đững', 'hững', 'lững', 'mững', 'nững', 'ngững', 'những', 'quững', 'rững', 'sững', 'tững', 'thững', 'trững', 'vững', 'xững', 'ứng', 'bứng', 'cứng', 'chứng', 'dứng', 'đứng', 'hứng', 'mứng', 'nứng', 'ngứng', 'nhứng', 'quứng', 'rứng', 'sứng', 'tứng', 'thứng', 'trứng', 'vứng', 'xứng', 'ựng', 'bựng', 'cựng', 'chựng', 'dựng', 'đựng', 'khựng', 'lựng', 'mựng', 'nựng', 'ngựng', 'nhựng', 'quựng', 'rựng', 'sựng', 'tựng', 'thựng', 'trựng', 'vựng', 'xựng', 'ươc', 'cươc', 'chươc', 'dươc', 'đươc', 'mươc', 'nươc', 'ngươc', 'nhươc', 'quươc', 'rươc', 'sươc', 'tươc', 'thươc', 'trươc', 'vươc', 'xươc', 'ườc', 'cườc', 'chườc', 'dườc', 'đườc', 'mườc', 'nườc', 'ngườc', 'nhườc', 'quườc', 'rườc', 'sườc', 'tườc', 'thườc', 'trườc', 'vườc', 'xườc', 'ưởc', 'cưởc', 'chưởc', 'dưởc', 'đưởc', 'mưởc', 'nưởc', 'ngưởc', 'nhưởc', 'quưởc', 'rưởc', 'sưởc', 'tưởc', 'thưởc', 'trưởc', 'vưởc', 'xưởc', 'ưỡc', 'cưỡc', 'chưỡc', 'dưỡc', 'đưỡc', 'mưỡc', 'nưỡc', 'ngưỡc', 'nhưỡc', 'quưỡc', 'rưỡc', 'sưỡc', 'tưỡc', 'thưỡc', 'trưỡc', 'vưỡc', 'xưỡc', 'ước', 'bước', 'cước', 'chước', 'dước', 'đước', 'hước', 'khước', 'mước', 'nước', 'ngước', 'nhước', 'quước', 'rước', 'sước', 'tước', 'thước', 'trước', 'vước', 'xước', 'ược', 'cược', 'chược', 'dược', 'được', 'lược', 'mược', 'nược', 'ngược', 'nhược', 'quược', 'rược', 'sược', 'tược', 'thược', 'trược', 'vược', 'xược', 'ươi', 'cươi', 'chươi', 'dươi', 'đươi', 'mươi', 'nươi', 'ngươi', 'nhươi', 'quươi', 'rươi', 'sươi', 'tươi', 'thươi', 'trươi', 'vươi', 'xươi', 'ười', 'cười', 'chười', 'dười', 'đười', 'lười', 'mười', 'nười', 'người', 'nhười', 'quười', 'rười', 'sười', 'tười', 'thười', 'trười', 'vười', 'xười', 'ưởi', 'bưởi', 'cưởi', 'chưởi', 'dưởi', 'đưởi', 'mưởi', 'nưởi', 'ngưởi', 'nhưởi', 'quưởi', 'rưởi', 'sưởi', 'tưởi', 'thưởi', 'trưởi', 'vưởi', 'xưởi', 'ưỡi', 'cưỡi', 'chưỡi', 'dưỡi', 'đưỡi', 'lưỡi', 'mưỡi', 'nưỡi', 'ngưỡi', 'nhưỡi', 'quưỡi', 'rưỡi', 'sưỡi', 'tưỡi', 'thưỡi', 'trưỡi', 'vưỡi', 'xưỡi', 'ưới', 'cưới', 'chưới', 'dưới', 'đưới', 'lưới', 'mưới', 'nưới', 'ngưới', 'nhưới', 'quưới', 'rưới', 'sưới', 'tưới', 'thưới', 'trưới', 'vưới', 'xưới', 'ượi', 'cượi', 'chượi', 'dượi', 'đượi', 'mượi', 'nượi', 'ngượi', 'nhượi', 'quượi', 'rượi', 'sượi', 'tượi', 'thượi', 'trượi', 'vượi', 'xượi', 'ươm', 'bươm', 'cươm', 'chươm', 'dươm', 'đươm', 'gươm', 'lươm', 'mươm', 'nươm', 'ngươm', 'nhươm', 'quươm', 'rươm', 'sươm', 'tươm', 'thươm', 'trươm', 'vươm', 'xươm', 'ườm', 'cườm', 'chườm', 'dườm', 'đườm', 'gườm', 'lườm', 'mườm', 'nườm', 'ngườm', 'nhườm', 'quườm', 'rườm', 'sườm', 'tườm', 'thườm', 'trườm', 'vườm', 'xườm', 'ưởm', 'cưởm', 'chưởm', 'dưởm', 'đưởm', 'mưởm', 'nưởm', 'ngưởm', 'nhưởm', 'quưởm', 'rưởm', 'sưởm', 'tưởm', 'thưởm', 'trưởm', 'vưởm', 'xưởm', 'ưỡm', 'cưỡm', 'chưỡm', 'dưỡm', 'đưỡm', 'mưỡm', 'nưỡm', 'ngưỡm', 'nhưỡm', 'quưỡm', 'rưỡm', 'sưỡm', 'tưỡm', 'thưỡm', 'trưỡm', 'vưỡm', 'xưỡm', 'ướm', 'bướm', 'cướm', 'chướm', 'dướm', 'đướm', 'mướm', 'nướm', 'ngướm', 'nhướm', 'quướm', 'rướm', 'sướm', 'tướm', 'thướm', 'trướm', 'vướm', 'xướm', 'ượm', 'cượm', 'chượm', 'dượm', 'đượm', 'gượm', 'lượm', 'mượm', 'nượm', 'ngượm', 'nhượm', 'quượm', 'rượm', 'sượm', 'tượm', 'thượm', 'trượm', 'vượm', 'xượm', 'ươn', 'bươn', 'cươn', 'chươn', 'dươn', 'đươn', 'khươn', 'lươn', 'mươn', 'nươn', 'ngươn', 'nhươn', 'quươn', 'rươn', 'sươn', 'tươn', 'thươn', 'trươn', 'vươn', 'xươn', 'ườn', 'cườn', 'chườn', 'dườn', 'đườn', 'lườn', 'mườn', 'nườn', 'ngườn', 'nhườn', 'quườn', 'rườn', 'sườn', 'tườn', 'thườn', 'trườn', 'vườn', 'xườn', 'ưởn', 'cưởn', 'chưởn', 'dưởn', 'đưởn', 'mưởn', 'nưởn', 'ngưởn', 'nhưởn', 'quưởn', 'rưởn', 'sưởn', 'tưởn', 'thưởn', 'trưởn', 'vưởn', 'xưởn', 'ưỡn', 'cưỡn', 'chưỡn', 'dưỡn', 'đưỡn', 'mưỡn', 'nưỡn', 'ngưỡn', 'nhưỡn', 'quưỡn', 'rưỡn', 'sưỡn', 'tưỡn', 'thưỡn', 'trưỡn', 'vưỡn', 'xưỡn', 'ướn', 'cướn', 'chướn', 'dướn', 'đướn', 'mướn', 'nướn', 'ngướn', 'nhướn', 'phướn', 'quướn', 'rướn', 'sướn', 'tướn', 'thướn', 'trướn', 'vướn', 'xướn', 'ượn', 'cượn', 'chượn', 'dượn', 'đượn', 'lượn', 'mượn', 'nượn', 'ngượn', 'nhượn', 'quượn', 'rượn', 'sượn', 'tượn', 'thượn', 'trượn', 'vượn', 'xượn', 'ươp', 'cươp', 'chươp', 'dươp', 'đươp', 'mươp', 'nươp', 'ngươp', 'nhươp', 'quươp', 'rươp', 'sươp', 'tươp', 'thươp', 'trươp', 'vươp', 'xươp', 'ườp', 'cườp', 'chườp', 'dườp', 'đườp', 'mườp', 'nườp', 'ngườp', 'nhườp', 'quườp', 'rườp', 'sườp', 'tườp', 'thườp', 'trườp', 'vườp', 'xườp', 'ưởp', 'cưởp', 'chưởp', 'dưởp', 'đưởp', 'mưởp', 'nưởp', 'ngưởp', 'nhưởp', 'quưởp', 'rưởp', 'sưởp', 'tưởp', 'thưởp', 'trưởp', 'vưởp', 'xưởp', 'ưỡp', 'cưỡp', 'chưỡp', 'dưỡp', 'đưỡp', 'mưỡp', 'nưỡp', 'ngưỡp', 'nhưỡp', 'quưỡp', 'rưỡp', 'sưỡp', 'tưỡp', 'thưỡp', 'trưỡp', 'vưỡp', 'xưỡp', 'ướp', 'cướp', 'chướp', 'dướp', 'đướp', 'mướp', 'nướp', 'ngướp', 'nhướp', 'quướp', 'rướp', 'sướp', 'tướp', 'thướp', 'trướp', 'vướp', 'xướp', 'ượp', 'cượp', 'chượp', 'dượp', 'đượp', 'mượp', 'nượp', 'ngượp', 'nhượp', 'quượp', 'rượp', 'sượp', 'tượp', 'thượp', 'trượp', 'vượp', 'xượp', 'ươt', 'cươt', 'chươt', 'dươt', 'đươt', 'mươt', 'nươt', 'ngươt', 'nhươt', 'quươt', 'rươt', 'sươt', 'tươt', 'thươt', 'trươt', 'vươt', 'xươt', 'ườt', 'cườt', 'chườt', 'dườt', 'đườt', 'mườt', 'nườt', 'ngườt', 'nhườt', 'quườt', 'rườt', 'sườt', 'tườt', 'thườt', 'trườt', 'vườt', 'xườt', 'ưởt', 'cưởt', 'chưởt', 'dưởt', 'đưởt', 'mưởt', 'nưởt', 'ngưởt', 'nhưởt', 'quưởt', 'rưởt', 'sưởt', 'tưởt', 'thưởt', 'trưởt', 'vưởt', 'xưởt', 'ưỡt', 'cưỡt', 'chưỡt', 'dưỡt', 'đưỡt', 'mưỡt', 'nưỡt', 'ngưỡt', 'nhưỡt', 'quưỡt', 'rưỡt', 'sưỡt', 'tưỡt', 'thưỡt', 'trưỡt', 'vưỡt', 'xưỡt', 'ướt', 'cướt', 'chướt', 'dướt', 'đướt', 'khướt', 'lướt', 'mướt', 'nướt', 'ngướt', 'nhướt', 'quướt', 'rướt', 'sướt', 'tướt', 'thướt', 'trướt', 'vướt', 'xướt', 'ượt', 'cượt', 'chượt', 'dượt', 'đượt', 'lượt', 'mượt', 'nượt', 'ngượt', 'nhượt', 'phượt', 'quượt', 'rượt', 'sượt', 'tượt', 'thượt', 'trượt', 'vượt', 'xượt', 'ươu', 'bươu', 'cươu', 'chươu', 'dươu', 'đươu', 'hươu', 'mươu', 'nươu', 'ngươu', 'nhươu', 'quươu', 'rươu', 'sươu', 'tươu', 'thươu', 'trươu', 'vươu', 'xươu', 'ườu', 'cườu', 'chườu', 'dườu', 'đườu', 'mườu', 'nườu', 'ngườu', 'nhườu', 'quườu', 'rườu', 'sườu', 'tườu', 'thườu', 'trườu', 'vườu', 'xườu', 'ưởu', 'cưởu', 'chưởu', 'dưởu', 'đưởu', 'mưởu', 'nưởu', 'ngưởu', 'nhưởu', 'quưởu', 'rưởu', 'sưởu', 'tưởu', 'thưởu', 'trưởu', 'vưởu', 'xưởu', 'ưỡu', 'cưỡu', 'chưỡu', 'dưỡu', 'đưỡu', 'mưỡu', 'nưỡu', 'ngưỡu', 'nhưỡu', 'quưỡu', 'rưỡu', 'sưỡu', 'tưỡu', 'thưỡu', 'trưỡu', 'vưỡu', 'xưỡu', 'ướu', 'bướu', 'cướu', 'chướu', 'dướu', 'đướu', 'khướu', 'mướu', 'nướu', 'ngướu', 'nhướu', 'quướu', 'rướu', 'sướu', 'tướu', 'thướu', 'trướu', 'vướu', 'xướu', 'ượu', 'cượu', 'chượu', 'dượu', 'đượu', 'mượu', 'nượu', 'ngượu', 'nhượu', 'quượu', 'rượu', 'sượu', 'tượu', 'thượu', 'trượu', 'vượu', 'xượu', 'yên', 'cyên', 'chyên', 'dyên', 'đyên', 'myên', 'nyên', 'ngyên', 'nhyên', 'quyên', 'ryên', 'syên', 'tyên', 'thyên', 'tryên', 'vyên', 'xyên', 'yền', 'cyền', 'chyền', 'dyền', 'đyền', 'myền', 'nyền', 'ngyền', 'nhyền', 'quyền', 'ryền', 'syền', 'tyền', 'thyền', 'tryền', 'vyền', 'xyền', 'yển', 'cyển', 'chyển', 'dyển', 'đyển', 'myển', 'nyển', 'ngyển', 'nhyển', 'quyển', 'ryển', 'syển', 'tyển', 'thyển', 'tryển', 'vyển', 'xyển', 'yễn', 'cyễn', 'chyễn', 'dyễn', 'đyễn', 'myễn', 'nyễn', 'ngyễn', 'nhyễn', 'quyễn', 'ryễn', 'syễn', 'tyễn', 'thyễn', 'tryễn', 'vyễn', 'xyễn', 'yến', 'cyến', 'chyến', 'dyến', 'đyến', 'myến', 'nyến', 'ngyến', 'nhyến', 'quyến', 'ryến', 'syến', 'tyến', 'thyến', 'tryến', 'vyến', 'xyến', 'yện', 'cyện', 'chyện', 'dyện', 'đyện', 'myện', 'nyện', 'ngyện', 'nhyện', 'quyện', 'ryện', 'syện', 'tyện', 'thyện', 'tryện', 'vyện', 'xyện', 'yêt', 'cyêt', 'chyêt', 'dyêt', 'đyêt', 'myêt', 'nyêt', 'ngyêt', 'nhyêt', 'quyêt', 'ryêt', 'syêt', 'tyêt', 'thyêt', 'tryêt', 'vyêt', 'xyêt', 'yềt', 'cyềt', 'chyềt', 'dyềt', 'đyềt', 'myềt', 'nyềt', 'ngyềt', 'nhyềt', 'quyềt', 'ryềt', 'syềt', 'tyềt', 'thyềt', 'tryềt', 'vyềt', 'xyềt', 'yểt', 'cyểt', 'chyểt', 'dyểt', 'đyểt', 'myểt', 'nyểt', 'ngyểt', 'nhyểt', 'quyểt', 'ryểt', 'syểt', 'tyểt', 'thyểt', 'tryểt', 'vyểt', 'xyểt', 'yễt', 'cyễt', 'chyễt', 'dyễt', 'đyễt', 'myễt', 'nyễt', 'ngyễt', 'nhyễt', 'quyễt', 'ryễt', 'syễt', 'tyễt', 'thyễt', 'tryễt', 'vyễt', 'xyễt', 'yết', 'cyết', 'chyết', 'dyết', 'đyết', 'myết', 'nyết', 'ngyết', 'nhyết', 'quyết', 'ryết', 'syết', 'tyết', 'thyết', 'tryết', 'vyết', 'xyết', 'yệt', 'cyệt', 'chyệt', 'dyệt', 'đyệt', 'myệt', 'nyệt', 'ngyệt', 'nhyệt', 'quyệt', 'ryệt', 'syệt', 'tyệt', 'thyệt', 'tryệt', 'vyệt', 'xyệt', 'yêu', 'cyêu', 'chyêu', 'dyêu', 'đyêu', 'myêu', 'nyêu', 'ngyêu', 'nhyêu', 'quyêu', 'ryêu', 'syêu', 'tyêu', 'thyêu', 'tryêu', 'vyêu', 'xyêu', 'yều', 'cyều', 'chyều', 'dyều', 'đyều', 'myều', 'nyều', 'ngyều', 'nhyều', 'quyều', 'ryều', 'syều', 'tyều', 'thyều', 'tryều', 'vyều', 'xyều', 'yểu', 'cyểu', 'chyểu', 'dyểu', 'đyểu', 'myểu', 'nyểu', 'ngyểu', 'nhyểu', 'quyểu', 'ryểu', 'syểu', 'tyểu', 'thyểu', 'tryểu', 'vyểu', 'xyểu', 'yễu', 'cyễu', 'chyễu', 'dyễu', 'đyễu', 'myễu', 'nyễu', 'ngyễu', 'nhyễu', 'quyễu', 'ryễu', 'syễu', 'tyễu', 'thyễu', 'tryễu', 'vyễu', 'xyễu', 'yếu', 'cyếu', 'chyếu', 'dyếu', 'đyếu', 'myếu', 'nyếu', 'ngyếu', 'nhyếu', 'quyếu', 'ryếu', 'syếu', 'tyếu', 'thyếu', 'tryếu', 'vyếu', 'xyếu', 'yệu', 'cyệu', 'chyệu', 'dyệu', 'đyệu', 'myệu', 'nyệu', 'ngyệu', 'nhyệu', 'quyệu', 'ryệu', 'syệu', 'tyệu', 'thyệu', 'tryệu', 'vyệu', 'xyệu', 'ynh', 'cynh', 'chynh', 'dynh', 'đynh', 'mynh', 'nynh', 'ngynh', 'nhynh', 'quynh', 'rynh', 'synh', 'tynh', 'thynh', 'trynh', 'vynh', 'xynh', 'ỳnh', 'cỳnh', 'chỳnh', 'dỳnh', 'đỳnh', 'mỳnh', 'nỳnh', 'ngỳnh', 'nhỳnh', 'quỳnh', 'rỳnh', 'sỳnh', 'tỳnh', 'thỳnh', 'trỳnh', 'vỳnh', 'xỳnh', 'ỷnh', 'cỷnh', 'chỷnh', 'dỷnh', 'đỷnh', 'mỷnh', 'nỷnh', 'ngỷnh', 'nhỷnh', 'quỷnh', 'rỷnh', 'sỷnh', 'tỷnh', 'thỷnh', 'trỷnh', 'vỷnh', 'xỷnh', 'ỹnh', 'cỹnh', 'chỹnh', 'dỹnh', 'đỹnh', 'mỹnh', 'nỹnh', 'ngỹnh', 'nhỹnh', 'quỹnh', 'rỹnh', 'sỹnh', 'tỹnh', 'thỹnh', 'trỹnh', 'vỹnh', 'xỹnh', 'ýnh', 'cýnh', 'chýnh', 'dýnh', 'đýnh', 'mýnh', 'nýnh', 'ngýnh', 'nhýnh', 'quýnh', 'rýnh', 'sýnh', 'týnh', 'thýnh', 'trýnh', 'výnh', 'xýnh', 'ỵnh', 'cỵnh', 'chỵnh', 'dỵnh', 'đỵnh', 'mỵnh', 'nỵnh', 'ngỵnh', 'nhỵnh', 'quỵnh', 'rỵnh', 'sỵnh', 'tỵnh', 'thỵnh', 'trỵnh', 'vỵnh', 'xỵnh', 'iêng', 'ciêng', 'chiêng', 'diêng', 'điêng', 'hiêng', 'kiêng', 'khiêng', 'liêng', 'miêng', 'niêng', 'ngiêng', 'nghiêng', 'nhiêng', 'quiêng', 'riêng', 'siêng', 'tiêng', 'thiêng', 'triêng', 'viêng', 'xiêng', 'iềng', 'ciềng', 'chiềng', 'diềng', 'điềng', 'kiềng', 'miềng', 'niềng', 'ngiềng', 'nhiềng', 'quiềng', 'riềng', 'siềng', 'tiềng', 'thiềng', 'triềng', 'viềng', 'xiềng', 'iểng', 'ciểng', 'chiểng', 'diểng', 'điểng', 'kiểng', 'liểng', 'miểng', 'niểng', 'ngiểng', 'nhiểng', 'quiểng', 'riểng', 'siểng', 'tiểng', 'thiểng', 'triểng', 'viểng', 'xiểng', 'iễng', 'ciễng', 'chiễng', 'diễng', 'điễng', 'kiễng', 'khiễng', 'miễng', 'niễng', 'ngiễng', 'nhiễng', 'quiễng', 'riễng', 'siễng', 'tiễng', 'thiễng', 'triễng', 'viễng', 'xiễng', 'iếng', 'biếng', 'ciếng', 'chiếng', 'diếng', 'điếng', 'hiếng', 'kiếng', 'miếng', 'niếng', 'ngiếng', 'nhiếng', 'quiếng', 'riếng', 'siếng', 'tiếng', 'thiếng', 'triếng', 'viếng', 'xiếng', 'iệng', 'ciệng', 'chiệng', 'diệng', 'điệng', 'khiệng', 'liệng', 'miệng', 'niệng', 'ngiệng', 'nhiệng', 'quiệng', 'riệng', 'siệng', 'tiệng', 'thiệng', 'triệng', 'việng', 'xiệng', 'oach', 'coach', 'choach', 'doach', 'đoach', 'moach', 'noach', 'ngoach', 'nhoach', 'quoach', 'roach', 'soach', 'toach', 'thoach', 'troach', 'voach', 'xoach', 'oàch', 'coàch', 'choàch', 'doàch', 'đoàch', 'moàch', 'noàch', 'ngoàch', 'nhoàch', 'quoàch', 'roàch', 'soàch', 'toàch', 'thoàch', 'troàch', 'voàch', 'xoàch', 'oảch', 'coảch', 'choảch', 'doảch', 'đoảch', 'moảch', 'noảch', 'ngoảch', 'nhoảch', 'quoảch', 'roảch', 'soảch', 'toảch', 'thoảch', 'troảch', 'voảch', 'xoảch', 'oãch', 'coãch', 'choãch', 'doãch', 'đoãch', 'moãch', 'noãch', 'ngoãch', 'nhoãch', 'quoãch', 'roãch', 'soãch', 'toãch', 'thoãch', 'troãch', 'voãch', 'xoãch', 'oách', 'coách', 'choách', 'doách', 'đoách', 'moách', 'noách', 'ngoách', 'nhoách', 'quoách', 'roách', 'soách', 'toách', 'thoách', 'troách', 'voách', 'xoách', 'oạch', 'coạch', 'choạch', 'doạch', 'đoạch', 'hoạch', 'moạch', 'noạch', 'ngoạch', 'nhoạch', 'quoạch', 'roạch', 'soạch', 'toạch', 'thoạch', 'troạch', 'voạch', 'xoạch', 'oang', 'coang', 'choang', 'doang', 'đoang', 'hoang', 'khoang', 'loang', 'moang', 'noang', 'ngoang', 'nhoang', 'quoang', 'roang', 'soang', 'toang', 'thoang', 'troang', 'voang', 'xoang', 'oàng', 'coàng', 'choàng', 'doàng', 'đoàng', 'hoàng', 'loàng', 'moàng', 'noàng', 'ngoàng', 'nhoàng', 'quoàng', 'roàng', 'soàng', 'toàng', 'thoàng', 'troàng', 'voàng', 'xoàng', 'oảng', 'coảng', 'choảng', 'doảng', 'đoảng', 'hoảng', 'khoảng', 'loảng', 'moảng', 'noảng', 'ngoảng', 'nhoảng', 'quoảng', 'roảng', 'soảng', 'toảng', 'thoảng', 'troảng', 'voảng', 'xoảng', 'oãng', 'coãng', 'choãng', 'doãng', 'đoãng', 'loãng', 'moãng', 'noãng', 'ngoãng', 'nhoãng', 'quoãng', 'roãng', 'soãng', 'toãng', 'thoãng', 'troãng', 'voãng', 'xoãng', 'oáng', 'coáng', 'choáng', 'doáng', 'đoáng', 'khoáng', 'loáng', 'moáng', 'noáng', 'ngoáng', 'nhoáng', 'quoáng', 'roáng', 'soáng', 'toáng', 'thoáng', 'troáng', 'voáng', 'xoáng', 'oạng', 'coạng', 'choạng', 'doạng', 'đoạng', 'loạng', 'moạng', 'noạng', 'ngoạng', 'nhoạng', 'quoạng', 'roạng', 'soạng', 'toạng', 'thoạng', 'troạng', 'voạng', 'xoạng', 'oanh', 'coanh', 'choanh', 'doanh', 'đoanh', 'khoanh', 'loanh', 'moanh', 'noanh', 'ngoanh', 'nhoanh', 'quoanh', 'roanh', 'soanh', 'toanh', 'thoanh', 'troanh', 'voanh', 'xoanh', 'oành', 'coành', 'choành', 'doành', 'đoành', 'hoành', 'moành', 'noành', 'ngoành', 'nhoành', 'quoành', 'roành', 'soành', 'toành', 'thoành', 'troành', 'voành', 'xoành', 'oảnh', 'coảnh', 'choảnh', 'doảnh', 'đoảnh', 'moảnh', 'noảnh', 'ngoảnh', 'nhoảnh', 'quoảnh', 'roảnh', 'soảnh', 'toảnh', 'thoảnh', 'troảnh', 'voảnh', 'xoảnh', 'oãnh', 'coãnh', 'choãnh', 'doãnh', 'đoãnh', 'moãnh', 'noãnh', 'ngoãnh', 'nhoãnh', 'quoãnh', 'roãnh', 'soãnh', 'toãnh', 'thoãnh', 'troãnh', 'voãnh', 'xoãnh', 'oánh', 'coánh', 'choánh', 'doánh', 'đoánh', 'moánh', 'noánh', 'ngoánh', 'nhoánh', 'quoánh', 'roánh', 'soánh', 'toánh', 'thoánh', 'troánh', 'voánh', 'xoánh', 'oạnh', 'coạnh', 'choạnh', 'doạnh', 'đoạnh', 'hoạnh', 'moạnh', 'noạnh', 'ngoạnh', 'nhoạnh', 'quoạnh', 'roạnh', 'soạnh', 'toạnh', 'thoạnh', 'troạnh', 'voạnh', 'xoạnh', 'oăng', 'coăng', 'choăng', 'doăng', 'đoăng', 'moăng', 'noăng', 'ngoăng', 'nhoăng', 'quoăng', 'roăng', 'soăng', 'toăng', 'thoăng', 'troăng', 'voăng', 'xoăng', 'oằng', 'coằng', 'choằng', 'doằng', 'đoằng', 'moằng', 'noằng', 'ngoằng', 'nhoằng', 'quoằng', 'roằng', 'soằng', 'toằng', 'thoằng', 'troằng', 'voằng', 'xoằng', 'oẳng', 'coẳng', 'choẳng', 'doẳng', 'đoẳng', 'moẳng', 'noẳng', 'ngoẳng', 'nhoẳng', 'quoẳng', 'roẳng', 'soẳng', 'toẳng', 'thoẳng', 'troẳng', 'voẳng', 'xoẳng', 'oẵng', 'coẵng', 'choẵng', 'doẵng', 'đoẵng', 'hoẵng', 'moẵng', 'noẵng', 'ngoẵng', 'nhoẵng', 'quoẵng', 'roẵng', 'soẵng', 'toẵng', 'thoẵng', 'troẵng', 'voẵng', 'xoẵng', 'oắng', 'coắng', 'choắng', 'doắng', 'đoắng', 'khoắng', 'moắng', 'noắng', 'ngoắng', 'nhoắng', 'quoắng', 'roắng', 'soắng', 'toắng', 'thoắng', 'troắng', 'voắng', 'xoắng', 'oặng', 'coặng', 'choặng', 'doặng', 'đoặng', 'moặng', 'noặng', 'ngoặng', 'nhoặng', 'quoặng', 'roặng', 'soặng', 'toặng', 'thoặng', 'troặng', 'voặng', 'xoặng', 'oong', 'boong', 'coong', 'choong', 'doong', 'đoong', 'loong', 'moong', 'noong', 'ngoong', 'nhoong', 'quoong', 'roong', 'soong', 'toong', 'thoong', 'troong', 'voong', 'xoong', 'oòng', 'coòng', 'choòng', 'doòng', 'đoòng', 'goòng', 'moòng', 'noòng', 'ngoòng', 'nhoòng', 'quoòng', 'roòng', 'soòng', 'toòng', 'thoòng', 'troòng', 'voòng', 'xoòng', 'oỏng', 'coỏng', 'choỏng', 'doỏng', 'đoỏng', 'moỏng', 'noỏng', 'ngoỏng', 'nhoỏng', 'quoỏng', 'roỏng', 'soỏng', 'toỏng', 'thoỏng', 'troỏng', 'voỏng', 'xoỏng', 'oõng', 'coõng', 'choõng', 'doõng', 'đoõng', 'moõng', 'noõng', 'ngoõng', 'nhoõng', 'quoõng', 'roõng', 'soõng', 'toõng', 'thoõng', 'troõng', 'voõng', 'xoõng', 'oóng', 'coóng', 'choóng', 'doóng', 'đoóng', 'moóng', 'noóng', 'ngoóng', 'nhoóng', 'quoóng', 'roóng', 'soóng', 'toóng', 'thoóng', 'troóng', 'voóng', 'xoóng', 'oọng', 'coọng', 'choọng', 'doọng', 'đoọng', 'moọng', 'noọng', 'ngoọng', 'nhoọng', 'quoọng', 'roọng', 'soọng', 'toọng', 'thoọng', 'troọng', 'voọng', 'xoọng', 'uâng', 'cuâng', 'chuâng', 'duâng', 'đuâng', 'khuâng', 'muâng', 'nuâng', 'nguâng', 'nhuâng', 'quuâng', 'ruâng', 'suâng', 'tuâng', 'thuâng', 'truâng', 'vuâng', 'xuâng', 'uầng', 'cuầng', 'chuầng', 'duầng', 'đuầng', 'muầng', 'nuầng', 'nguầng', 'nhuầng', 'quuầng', 'ruầng', 'suầng', 'tuầng', 'thuầng', 'truầng', 'vuầng', 'xuầng', 'uẩng', 'cuẩng', 'chuẩng', 'duẩng', 'đuẩng', 'muẩng', 'nuẩng', 'nguẩng', 'nhuẩng', 'quuẩng', 'ruẩng', 'suẩng', 'tuẩng', 'thuẩng', 'truẩng', 'vuẩng', 'xuẩng', 'uẫng', 'cuẫng', 'chuẫng', 'duẫng', 'đuẫng', 'muẫng', 'nuẫng', 'nguẫng', 'nhuẫng', 'quuẫng', 'ruẫng', 'suẫng', 'tuẫng', 'thuẫng', 'truẫng', 'vuẫng', 'xuẫng', 'uấng', 'cuấng', 'chuấng', 'duấng', 'đuấng', 'muấng', 'nuấng', 'nguấng', 'nhuấng', 'quuấng', 'ruấng', 'suấng', 'tuấng', 'thuấng', 'truấng', 'vuấng', 'xuấng', 'uậng', 'cuậng', 'chuậng', 'duậng', 'đuậng', 'muậng', 'nuậng', 'nguậng', 'nhuậng', 'quuậng', 'ruậng', 'suậng', 'tuậng', 'thuậng', 'truậng', 'vuậng', 'xuậng', 'uêch', 'cuêch', 'chuêch', 'duêch', 'đuêch', 'muêch', 'nuêch', 'nguêch', 'nhuêch', 'quuêch', 'ruêch', 'suêch', 'tuêch', 'thuêch', 'truêch', 'vuêch', 'xuêch', 'uềch', 'cuềch', 'chuềch', 'duềch', 'đuềch', 'muềch', 'nuềch', 'nguềch', 'nhuềch', 'quuềch', 'ruềch', 'suềch', 'tuềch', 'thuềch', 'truềch', 'vuềch', 'xuềch', 'uểch', 'cuểch', 'chuểch', 'duểch', 'đuểch', 'muểch', 'nuểch', 'nguểch', 'nhuểch', 'quuểch', 'ruểch', 'suểch', 'tuểch', 'thuểch', 'truểch', 'vuểch', 'xuểch', 'uễch', 'cuễch', 'chuễch', 'duễch', 'đuễch', 'muễch', 'nuễch', 'nguễch', 'nhuễch', 'quuễch', 'ruễch', 'suễch', 'tuễch', 'thuễch', 'truễch', 'vuễch', 'xuễch', 'uếch', 'cuếch', 'chuếch', 'duếch', 'đuếch', 'huếch', 'khuếch', 'muếch', 'nuếch', 'nguếch', 'nhuếch', 'quuếch', 'ruếch', 'suếch', 'tuếch', 'thuếch', 'truếch', 'vuếch', 'xuếch', 'uệch', 'cuệch', 'chuệch', 'duệch', 'đuệch', 'muệch', 'nuệch', 'nguệch', 'nhuệch', 'quuệch', 'ruệch', 'suệch', 'tuệch', 'thuệch', 'truệch', 'vuệch', 'xuệch', 'uênh', 'cuênh', 'chuênh', 'duênh', 'đuênh', 'huênh', 'muênh', 'nuênh', 'nguênh', 'nhuênh', 'quuênh', 'ruênh', 'suênh', 'tuênh', 'thuênh', 'truênh', 'vuênh', 'xuênh', 'uềnh', 'cuềnh', 'chuềnh', 'duềnh', 'đuềnh', 'muềnh', 'nuềnh', 'nguềnh', 'nhuềnh', 'quuềnh', 'ruềnh', 'suềnh', 'tuềnh', 'thuềnh', 'truềnh', 'vuềnh', 'xuềnh', 'uểnh', 'cuểnh', 'chuểnh', 'duểnh', 'đuểnh', 'muểnh', 'nuểnh', 'nguểnh', 'nhuểnh', 'quuểnh', 'ruểnh', 'suểnh', 'tuểnh', 'thuểnh', 'truểnh', 'vuểnh', 'xuểnh', 'uễnh', 'cuễnh', 'chuễnh', 'duễnh', 'đuễnh', 'muễnh', 'nuễnh', 'nguễnh', 'nhuễnh', 'quuễnh', 'ruễnh', 'suễnh', 'tuễnh', 'thuễnh', 'truễnh', 'vuễnh', 'xuễnh', 'uếnh', 'cuếnh', 'chuếnh', 'duếnh', 'đuếnh', 'muếnh', 'nuếnh', 'nguếnh', 'nhuếnh', 'quuếnh', 'ruếnh', 'suếnh', 'tuếnh', 'thuếnh', 'truếnh', 'vuếnh', 'xuếnh', 'uệnh', 'cuệnh', 'chuệnh', 'duệnh', 'đuệnh', 'muệnh', 'nuệnh', 'nguệnh', 'nhuệnh', 'quuệnh', 'ruệnh', 'suệnh', 'tuệnh', 'thuệnh', 'truệnh', 'vuệnh', 'xuệnh', 'uông', 'buông', 'cuông', 'chuông', 'duông', 'đuông', 'huông', 'khuông', 'luông', 'muông', 'nuông', 'nguông', 'nhuông', 'quuông', 'ruông', 'suông', 'tuông', 'thuông', 'truông', 'vuông', 'xuông', 'uồng', 'buồng', 'cuồng', 'chuồng', 'duồng', 'đuồng', 'guồng', 'luồng', 'muồng', 'nuồng', 'nguồng', 'nhuồng', 'quuồng', 'ruồng', 'suồng', 'tuồng', 'thuồng', 'truồng', 'vuồng', 'xuồng', 'uổng', 'cuổng', 'chuổng', 'duổng', 'đuổng', 'muổng', 'nuổng', 'nguổng', 'nhuổng', 'quuổng', 'ruổng', 'suổng', 'tuổng', 'thuổng', 'truổng', 'vuổng', 'xuổng', 'uỗng', 'cuỗng', 'chuỗng', 'duỗng', 'đuỗng', 'luỗng', 'muỗng', 'nuỗng', 'nguỗng', 'nhuỗng', 'quuỗng', 'ruỗng', 'suỗng', 'tuỗng', 'thuỗng', 'truỗng', 'vuỗng', 'xuỗng', 'uống', 'cuống', 'chuống', 'duống', 'đuống', 'huống', 'luống', 'muống', 'nuống', 'nguống', 'nhuống', 'quuống', 'ruống', 'suống', 'tuống', 'thuống', 'truống', 'vuống', 'xuống', 'uộng', 'cuộng', 'chuộng', 'duộng', 'đuộng', 'muộng', 'nuộng', 'nguộng', 'nhuộng', 'quuộng', 'ruộng', 'suộng', 'tuộng', 'thuộng', 'truộng', 'vuộng', 'xuộng', 'uych', 'cuych', 'chuych', 'duych', 'đuych', 'muych', 'nuych', 'nguych', 'nhuych', 'quuych', 'ruych', 'suych', 'tuych', 'thuych', 'truych', 'vuych', 'xuych', 'uỳch', 'cuỳch', 'chuỳch', 'duỳch', 'đuỳch', 'muỳch', 'nuỳch', 'nguỳch', 'nhuỳch', 'quuỳch', 'ruỳch', 'suỳch', 'tuỳch', 'thuỳch', 'truỳch', 'vuỳch', 'xuỳch', 'uỷch', 'cuỷch', 'chuỷch', 'duỷch', 'đuỷch', 'muỷch', 'nuỷch', 'nguỷch', 'nhuỷch', 'quuỷch', 'ruỷch', 'suỷch', 'tuỷch', 'thuỷch', 'truỷch', 'vuỷch', 'xuỷch', 'uỹch', 'cuỹch', 'chuỹch', 'duỹch', 'đuỹch', 'muỹch', 'nuỹch', 'nguỹch', 'nhuỹch', 'quuỹch', 'ruỹch', 'suỹch', 'tuỹch', 'thuỹch', 'truỹch', 'vuỹch', 'xuỹch', 'uých', 'cuých', 'chuých', 'duých', 'đuých', 'huých', 'muých', 'nuých', 'nguých', 'nhuých', 'quuých', 'ruých', 'suých', 'tuých', 'thuých', 'truých', 'vuých', 'xuých', 'uỵch', 'cuỵch', 'chuỵch', 'duỵch', 'đuỵch', 'muỵch', 'nuỵch', 'nguỵch', 'nhuỵch', 'quuỵch', 'ruỵch', 'suỵch', 'tuỵch', 'thuỵch', 'truỵch', 'vuỵch', 'xuỵch', 'uyên', 'cuyên', 'chuyên', 'duyên', 'đuyên', 'huyên', 'khuyên', 'luyên', 'muyên', 'nuyên', 'nguyên', 'nhuyên', 'quuyên', 'ruyên', 'suyên', 'tuyên', 'thuyên', 'truyên', 'vuyên', 'xuyên', 'uyền', 'cuyền', 'chuyền', 'duyền', 'đuyền', 'huyền', 'muyền', 'nuyền', 'nguyền', 'nhuyền', 'quuyền', 'ruyền', 'suyền', 'tuyền', 'thuyền', 'truyền', 'vuyền', 'xuyền', 'uyển', 'cuyển', 'chuyển', 'duyển', 'đuyển', 'khuyển', 'muyển', 'nuyển', 'nguyển', 'nhuyển', 'quuyển', 'ruyển', 'suyển', 'tuyển', 'thuyển', 'truyển', 'vuyển', 'xuyển', 'uyễn', 'cuyễn', 'chuyễn', 'duyễn', 'đuyễn', 'huyễn', 'muyễn', 'nuyễn', 'nguyễn', 'nhuyễn', 'quuyễn', 'ruyễn', 'suyễn', 'tuyễn', 'thuyễn', 'truyễn', 'vuyễn', 'xuyễn', 'uyến', 'cuyến', 'chuyến', 'duyến', 'đuyến', 'khuyến', 'luyến', 'muyến', 'nuyến', 'nguyến', 'nhuyến', 'quuyến', 'ruyến', 'suyến', 'tuyến', 'thuyến', 'truyến', 'vuyến', 'xuyến', 'uyện', 'cuyện', 'chuyện', 'duyện', 'đuyện', 'huyện', 'luyện', 'muyện', 'nuyện', 'nguyện', 'nhuyện', 'quuyện', 'ruyện', 'suyện', 'tuyện', 'thuyện', 'truyện', 'vuyện', 'xuyện', 'uyêt', 'cuyêt', 'chuyêt', 'duyêt', 'đuyêt', 'muyêt', 'nuyêt', 'nguyêt', 'nhuyêt', 'quuyêt', 'ruyêt', 'suyêt', 'tuyêt', 'thuyêt', 'truyêt', 'vuyêt', 'xuyêt', 'uyềt', 'cuyềt', 'chuyềt', 'duyềt', 'đuyềt', 'muyềt', 'nuyềt', 'nguyềt', 'nhuyềt', 'quuyềt', 'ruyềt', 'suyềt', 'tuyềt', 'thuyềt', 'truyềt', 'vuyềt', 'xuyềt', 'uyểt', 'cuyểt', 'chuyểt', 'duyểt', 'đuyểt', 'muyểt', 'nuyểt', 'nguyểt', 'nhuyểt', 'quuyểt', 'ruyểt', 'suyểt', 'tuyểt', 'thuyểt', 'truyểt', 'vuyểt', 'xuyểt', 'uyễt', 'cuyễt', 'chuyễt', 'duyễt', 'đuyễt', 'muyễt', 'nuyễt', 'nguyễt', 'nhuyễt', 'quuyễt', 'ruyễt', 'suyễt', 'tuyễt', 'thuyễt', 'truyễt', 'vuyễt', 'xuyễt', 'uyết', 'cuyết', 'chuyết', 'duyết', 'đuyết', 'huyết', 'khuyết', 'muyết', 'nuyết', 'nguyết', 'nhuyết', 'quuyết', 'ruyết', 'suyết', 'tuyết', 'thuyết', 'truyết', 'vuyết', 'xuyết', 'uyệt', 'cuyệt', 'chuyệt', 'duyệt', 'đuyệt', 'huyệt', 'muyệt', 'nuyệt', 'nguyệt', 'nhuyệt', 'quuyệt', 'ruyệt', 'suyệt', 'tuyệt', 'thuyệt', 'truyệt', 'vuyệt', 'xuyệt', 'uynh', 'cuynh', 'chuynh', 'duynh', 'đuynh', 'huynh', 'khuynh', 'muynh', 'nuynh', 'nguynh', 'nhuynh', 'quuynh', 'ruynh', 'suynh', 'tuynh', 'thuynh', 'truynh', 'vuynh', 'xuynh', 'uỳnh', 'cuỳnh', 'chuỳnh', 'duỳnh', 'đuỳnh', 'huỳnh', 'khuỳnh', 'muỳnh', 'nuỳnh', 'nguỳnh', 'nhuỳnh', 'quuỳnh', 'ruỳnh', 'suỳnh', 'tuỳnh', 'thuỳnh', 'truỳnh', 'vuỳnh', 'xuỳnh', 'uỷnh', 'cuỷnh', 'chuỷnh', 'duỷnh', 'đuỷnh', 'khuỷnh', 'muỷnh', 'nuỷnh', 'nguỷnh', 'nhuỷnh', 'quuỷnh', 'ruỷnh', 'suỷnh', 'tuỷnh', 'thuỷnh', 'truỷnh', 'vuỷnh', 'xuỷnh', 'uỹnh', 'cuỹnh', 'chuỹnh', 'duỹnh', 'đuỹnh', 'muỹnh', 'nuỹnh', 'nguỹnh', 'nhuỹnh', 'quuỹnh', 'ruỹnh', 'suỹnh', 'tuỹnh', 'thuỹnh', 'truỹnh', 'vuỹnh', 'xuỹnh', 'uýnh', 'cuýnh', 'chuýnh', 'duýnh', 'đuýnh', 'luýnh', 'muýnh', 'nuýnh', 'nguýnh', 'nhuýnh', 'quuýnh', 'ruýnh', 'suýnh', 'tuýnh', 'thuýnh', 'truýnh', 'vuýnh', 'xuýnh', 'uỵnh', 'cuỵnh', 'chuỵnh', 'duỵnh', 'đuỵnh', 'muỵnh', 'nuỵnh', 'nguỵnh', 'nhuỵnh', 'quuỵnh', 'ruỵnh', 'suỵnh', 'tuỵnh', 'thuỵnh', 'truỵnh', 'vuỵnh', 'xuỵnh', 'ương', 'bương', 'cương', 'chương', 'dương', 'đương', 'gương', 'giương', 'hương', 'lương', 'mương', 'nương', 'ngương', 'nhương', 'phương', 'quương', 'rương', 'sương', 'tương', 'thương', 'trương', 'vương', 'xương', 'ường', 'cường', 'chường', 'dường', 'đường', 'giường', 'lường', 'mường', 'nường', 'ngường', 'nhường', 'phường', 'quường', 'rường', 'sường', 'tường', 'thường', 'trường', 'vường', 'xường', 'ưởng', 'cưởng', 'chưởng', 'dưởng', 'đưởng', 'hưởng', 'mưởng', 'nưởng', 'ngưởng', 'nhưởng', 'quưởng', 'rưởng', 'sưởng', 'tưởng', 'thưởng', 'trưởng', 'vưởng', 'xưởng', 'ưỡng', 'cưỡng', 'chưỡng', 'dưỡng', 'đưỡng', 'gưỡng', 'khưỡng', 'lưỡng', 'mưỡng', 'nưỡng', 'ngưỡng', 'nhưỡng', 'quưỡng', 'rưỡng', 'sưỡng', 'tưỡng', 'thưỡng', 'trưỡng', 'vưỡng', 'xưỡng', 'ướng', 'bướng', 'cướng', 'chướng', 'dướng', 'đướng', 'hướng', 'lướng', 'mướng', 'nướng', 'ngướng', 'nhướng', 'phướng', 'quướng', 'rướng', 'sướng', 'tướng', 'thướng', 'trướng', 'vướng', 'xướng', 'ượng', 'cượng', 'chượng', 'dượng', 'đượng', 'gượng', 'lượng', 'mượng', 'nượng', 'ngượng', 'nhượng', 'phượng', 'quượng', 'rượng', 'sượng', 'tượng', 'thượng', 'trượng', 'vượng', 'xượng'],

		/**
		 * List of wrong accent placements which need to be corrected.
		 *
		 * Indexes:
		 * [wrong_lower_case] =>
		 *		1 -> Correct lower case.
		 *		2 -> Wrong upper-first case.
		 *		3 -> Correct upper-first case.
		 *		4 -> Wrong upper case.
		 *		5 -> Correct upper case.
		 */
		'accent_placements' => [
			'aì' => ['ài', 'Aì', 'Ài', 'AÌ', 'ÀI'],
			'aỉ' => ['ải', 'Aỉ', 'Ải', 'AỈ', 'ẢI'],
			'aĩ' => ['ãi', 'Aĩ', 'Ãi', 'AĨ', 'ÃI'],
			'aí' => ['ái', 'Aí', 'Ái', 'AÍ', 'ÁI'],
			'aị' => ['ại', 'Aị', 'Ại', 'AỊ', 'ẠI'],
			'aò' => ['ào', 'Aò', 'Ào', 'AÒ', 'ÀO'],
			'aỏ' => ['ảo', 'Aỏ', 'Ảo', 'AỎ', 'ẢO'],
			'aõ' => ['ão', 'Aõ', 'Ão', 'AÕ', 'ÃO'],
			'aó' => ['áo', 'Aó', 'Áo', 'AÓ', 'ÁO'],
			'aọ' => ['ạo', 'Aọ', 'Ạo', 'AỌ', 'ẠO'],
			'aù' => ['àu', 'Aù', 'Àu', 'AÙ', 'ÀU'],
			'aủ' => ['ảu', 'Aủ', 'Ảu', 'AỦ', 'ẢU'],
			'aũ' => ['ãu', 'Aũ', 'Ãu', 'AŨ', 'ÃU'],
			'aú' => ['áu', 'Aú', 'Áu', 'AÚ', 'ÁU'],
			'aụ' => ['ạu', 'Aụ', 'Ạu', 'AỤ', 'ẠU'],
			'aỳ' => ['ày', 'Aỳ', 'Ày', 'AỲ', 'ÀY'],
			'aỷ' => ['ảy', 'Aỷ', 'Ảy', 'AỶ', 'ẢY'],
			'aỹ' => ['ãy', 'Aỹ', 'Ãy', 'AỸ', 'ÃY'],
			'aý' => ['áy', 'Aý', 'Áy', 'AÝ', 'ÁY'],
			'aỵ' => ['ạy', 'Aỵ', 'Ạy', 'AỴ', 'ẠY'],
			'âù' => ['ầu', 'Âù', 'Ầu', 'ÂÙ', 'ẦU'],
			'âủ' => ['ẩu', 'Âủ', 'Ẩu', 'ÂỦ', 'ẨU'],
			'âũ' => ['ẫu', 'Âũ', 'Ẫu', 'ÂŨ', 'ẪU'],
			'âú' => ['ấu', 'Âú', 'Ấu', 'ÂÚ', 'ẤU'],
			'âụ' => ['ậu', 'Âụ', 'Ậu', 'ÂỤ', 'ẬU'],
			'âỳ' => ['ầy', 'Âỳ', 'Ầy', 'ÂỲ', 'ẦY'],
			'âỷ' => ['ẩy', 'Âỷ', 'Ẩy', 'ÂỶ', 'ẨY'],
			'âỹ' => ['ẫy', 'Âỹ', 'Ẫy', 'ÂỸ', 'ẪY'],
			'âý' => ['ấy', 'Âý', 'Ấy', 'ÂÝ', 'ẤY'],
			'âỵ' => ['ậy', 'Âỵ', 'Ậy', 'ÂỴ', 'ẬY'],
			'eò' => ['èo', 'Eò', 'Èo', 'EÒ', 'ÈO'],
			'eỏ' => ['ẻo', 'Eỏ', 'Ẻo', 'EỎ', 'ẺO'],
			'eõ' => ['ẽo', 'Eõ', 'Ẽo', 'EÕ', 'ẼO'],
			'eó' => ['éo', 'Eó', 'Éo', 'EÓ', 'ÉO'],
			'eọ' => ['ẹo', 'Eọ', 'Ẹo', 'EỌ', 'ẸO'],
			'êù' => ['ều', 'Êù', 'Ều', 'ÊÙ', 'ỀU'],
			'êủ' => ['ểu', 'Êủ', 'Ểu', 'ÊỦ', 'ỂU'],
			'êũ' => ['ễu', 'Êũ', 'Ễu', 'ÊŨ', 'ỄU'],
			'êú' => ['ếu', 'Êú', 'Ếu', 'ÊÚ', 'ẾU'],
			'êụ' => ['ệu', 'Êụ', 'Ệu', 'ÊỤ', 'ỆU'],
			'ià' => ['ìa', 'Ià', 'Ìa', 'IÀ', 'ÌA'],
			'iả' => ['ỉa', 'Iả', 'Ỉa', 'IẢ', 'ỈA'],
			'iã' => ['ĩa', 'Iã', 'Ĩa', 'IÃ', 'ĨA'],
			'iá' => ['ía', 'Iá', 'Ía', 'IÁ', 'ÍA'],
			'iạ' => ['ịa', 'Iạ', 'Ịa', 'IẠ', 'ỊA'],
			'iù' => ['ìu', 'Iù', 'Ìu', 'IÙ', 'ÌU'],
			'iủ' => ['ỉu', 'Iủ', 'Ỉu', 'IỦ', 'ỈU'],
			'iũ' => ['ĩu', 'Iũ', 'Ĩu', 'IŨ', 'ĨU'],
			'iú' => ['íu', 'Iú', 'Íu', 'IÚ', 'ÍU'],
			'iụ' => ['ịu', 'Iụ', 'Ịu', 'IỤ', 'ỊU'],
			'oà' => ['òa', 'Oà', 'Òa', 'OÀ', 'ÒA'],
			'oả' => ['ỏa', 'Oả', 'Ỏa', 'OẢ', 'ỎA'],
			'oã' => ['õa', 'Oã', 'Õa', 'OÃ', 'ÕA'],
			'oá' => ['óa', 'Oá', 'Óa', 'OÁ', 'ÓA'],
			'oạ' => ['ọa', 'Oạ', 'Ọa', 'OẠ', 'ỌA'],
			'oè' => ['òe', 'Oè', 'Òe', 'OÈ', 'ÒE'],
			'oẻ' => ['ỏe', 'Oẻ', 'Ỏe', 'OẺ', 'ỎE'],
			'oẽ' => ['õe', 'Oẽ', 'Õe', 'OẼ', 'ÕE'],
			'oé' => ['óe', 'Oé', 'Óe', 'OÉ', 'ÓE'],
			'oẹ' => ['ọe', 'Oẹ', 'Ọe', 'OẸ', 'ỌE'],
			'oì' => ['òi', 'Oì', 'Òi', 'OÌ', 'ÒI'],
			'oỉ' => ['ỏi', 'Oỉ', 'Ỏi', 'OỈ', 'ỎI'],
			'oĩ' => ['õi', 'Oĩ', 'Õi', 'OĨ', 'ÕI'],
			'oí' => ['ói', 'Oí', 'Ói', 'OÍ', 'ÓI'],
			'oị' => ['ọi', 'Oị', 'Ọi', 'OỊ', 'ỌI'],
			'ôì' => ['ồi', 'Ôì', 'Ồi', 'ÔÌ', 'ỒI'],
			'ôỉ' => ['ổi', 'Ôỉ', 'Ổi', 'ÔỈ', 'ỔI'],
			'ôĩ' => ['ỗi', 'Ôĩ', 'Ỗi', 'ÔĨ', 'ỖI'],
			'ôí' => ['ối', 'Ôí', 'Ối', 'ÔÍ', 'ỐI'],
			'ôị' => ['ội', 'Ôị', 'Ội', 'ÔỊ', 'ỘI'],
			'ơì' => ['ời', 'Ơì', 'Ời', 'ƠÌ', 'ỜI'],
			'ơỉ' => ['ởi', 'Ơỉ', 'Ởi', 'ƠỈ', 'ỞI'],
			'ơĩ' => ['ỡi', 'Ơĩ', 'Ỡi', 'ƠĨ', 'ỠI'],
			'ơí' => ['ới', 'Ơí', 'Ới', 'ƠÍ', 'ỚI'],
			'ơị' => ['ợi', 'Ơị', 'Ợi', 'ƠỊ', 'ỢI'],
			'uà' => ['ùa', 'Uà', 'Ùa', 'UÀ', 'ÙA'],
			'uả' => ['ủa', 'Uả', 'Ủa', 'UẢ', 'ỦA'],
			'uã' => ['ũa', 'Uã', 'Ũa', 'UÃ', 'ŨA'],
			'uá' => ['úa', 'Uá', 'Úa', 'UÁ', 'ÚA'],
			'uạ' => ['ụa', 'Uạ', 'Ụa', 'UẠ', 'ỤA'],
			'ùê' => ['uề', 'Ùê', 'Uề', 'ÙÊ', 'UỀ'],
			'ủê' => ['uể', 'Ủê', 'Uể', 'ỦÊ', 'UỂ'],
			'ũê' => ['uễ', 'Ũê', 'Uễ', 'ŨÊ', 'UỄ'],
			'úê' => ['uế', 'Úê', 'Uế', 'ÚÊ', 'UẾ'],
			'ụê' => ['uệ', 'Ụê', 'Uệ', 'ỤÊ', 'UỆ'],
			'uì' => ['ùi', 'Uì', 'Ùi', 'UÌ', 'ÙI'],
			'uỉ' => ['ủi', 'Uỉ', 'Ủi', 'UỈ', 'ỦI'],
			'uĩ' => ['ũi', 'Uĩ', 'Ũi', 'UĨ', 'ŨI'],
			'uí' => ['úi', 'Uí', 'Úi', 'UÍ', 'ÚI'],
			'uị' => ['ụi', 'Uị', 'Ụi', 'UỊ', 'ỤI'],
			'ùơ' => ['uờ', 'Ùơ', 'Uờ', 'ÙƠ', 'UỜ'],
			'ủơ' => ['uở', 'Ủơ', 'Uở', 'ỦƠ', 'UỞ'],
			'ũơ' => ['uỡ', 'Ũơ', 'Uỡ', 'ŨƠ', 'UỠ'],
			'úơ' => ['uớ', 'Úơ', 'Uớ', 'ÚƠ', 'UỚ'],
			'ụơ' => ['uợ', 'Ụơ', 'Uợ', 'ỤƠ', 'UỢ'],
			'uỳ' => ['ùy', 'Uỳ', 'Ùy', 'UỲ', 'ÙY'],
			'uỷ' => ['ủy', 'Uỷ', 'Ủy', 'UỶ', 'ỦY'],
			'uỹ' => ['ũy', 'Uỹ', 'Ũy', 'UỸ', 'ŨY'],
			'uý' => ['úy', 'Uý', 'Úy', 'UÝ', 'ÚY'],
			'uỵ' => ['ụy', 'Uỵ', 'Ụy', 'UỴ', 'ỤY'],
			'ưà' => ['ừa', 'Ưà', 'Ừa', 'ƯÀ', 'ỪA'],
			'ưả' => ['ửa', 'Ưả', 'Ửa', 'ƯẢ', 'ỬA'],
			'ưã' => ['ữa', 'Ưã', 'Ữa', 'ƯÃ', 'ỮA'],
			'ưá' => ['ứa', 'Ưá', 'Ứa', 'ƯÁ', 'ỨA'],
			'ưạ' => ['ựa', 'Ưạ', 'Ựa', 'ƯẠ', 'ỰA'],
			'ưì' => ['ừi', 'Ưì', 'Ừi', 'ƯÌ', 'ỪI'],
			'ưỉ' => ['ửi', 'Ưỉ', 'Ửi', 'ƯỈ', 'ỬI'],
			'ưĩ' => ['ữi', 'Ưĩ', 'Ữi', 'ƯĨ', 'ỮI'],
			'ưí' => ['ứi', 'Ưí', 'Ứi', 'ƯÍ', 'ỨI'],
			'ưị' => ['ựi', 'Ưị', 'Ựi', 'ƯỊ', 'ỰI'],
			'ưù' => ['ừu', 'Ưù', 'Ừu', 'ƯÙ', 'ỪU'],
			'ưủ' => ['ửu', 'Ưủ', 'Ửu', 'ƯỦ', 'ỬU'],
			'ưũ' => ['ữu', 'Ưũ', 'Ữu', 'ƯŨ', 'ỮU'],
			'ưú' => ['ứu', 'Ưú', 'Ứu', 'ƯÚ', 'ỨU'],
			'ưụ' => ['ựu', 'Ưụ', 'Ựu', 'ƯỤ', 'ỰU'],

			'ìêc' => ['iềc', 'Ìêc', 'Iềc', 'ÌÊC', 'IỀC'],
			'ỉêc' => ['iểc', 'Ỉêc', 'Iểc', 'ỈÊC', 'IỂC'],
			'ĩêc' => ['iễc', 'Ĩêc', 'Iễc', 'ĨÊC', 'IỄC'],
			'íêc' => ['iếc', 'Íêc', 'Iếc', 'ÍÊC', 'IẾC'],
			'ịêc' => ['iệc', 'Ịêc', 'Iệc', 'ỊÊC', 'IỆC'],
			'ìêm' => ['iềm', 'Ìêm', 'Iềm', 'ÌÊM', 'IỀM'],
			'ỉêm' => ['iểm', 'Ỉêm', 'Iểm', 'ỈÊM', 'IỂM'],
			'ĩêm' => ['iễm', 'Ĩêm', 'Iễm', 'ĨÊM', 'IỄM'],
			'íêm' => ['iếm', 'Íêm', 'Iếm', 'ÍÊM', 'IẾM'],
			'ịêm' => ['iệm', 'Ịêm', 'Iệm', 'ỊÊM', 'IỆM'],
			'ìên' => ['iền', 'Ìên', 'Iền', 'ÌÊN', 'IỀN'],
			'ỉên' => ['iển', 'Ỉên', 'Iển', 'ỈÊN', 'IỂN'],
			'ĩên' => ['iễn', 'Ĩên', 'Iễn', 'ĨÊN', 'IỄN'],
			'íên' => ['iến', 'Íên', 'Iến', 'ÍÊN', 'IẾN'],
			'ịên' => ['iện', 'Ịên', 'Iện', 'ỊÊN', 'IỆN'],
			'ìêp' => ['iềp', 'Ìêp', 'Iềp', 'ÌÊP', 'IỀP'],
			'ỉêp' => ['iểp', 'Ỉêp', 'Iểp', 'ỈÊP', 'IỂP'],
			'ĩêp' => ['iễp', 'Ĩêp', 'Iễp', 'ĨÊP', 'IỄP'],
			'íêp' => ['iếp', 'Íêp', 'Iếp', 'ÍÊP', 'IẾP'],
			'ịêp' => ['iệp', 'Ịêp', 'Iệp', 'ỊÊP', 'IỆP'],
			'ìêt' => ['iềt', 'Ìêt', 'Iềt', 'ÌÊT', 'IỀT'],
			'ỉêt' => ['iểt', 'Ỉêt', 'Iểt', 'ỈÊT', 'IỂT'],
			'ĩêt' => ['iễt', 'Ĩêt', 'Iễt', 'ĨÊT', 'IỄT'],
			'íêt' => ['iết', 'Íêt', 'Iết', 'ÍÊT', 'IẾT'],
			'ịêt' => ['iệt', 'Ịêt', 'Iệt', 'ỊÊT', 'IỆT'],
			'ìêu' => ['iều', 'Ìêu', 'Iều', 'ÌÊU', 'IỀU'],
			'ỉêu' => ['iểu', 'Ỉêu', 'Iểu', 'ỈÊU', 'IỂU'],
			'ĩêu' => ['iễu', 'Ĩêu', 'Iễu', 'ĨÊU', 'IỄU'],
			'íêu' => ['iếu', 'Íêu', 'Iếu', 'ÍÊU', 'IẾU'],
			'ịêu' => ['iệu', 'Ịêu', 'Iệu', 'ỊÊU', 'IỆU'],
			'iêù' => ['iều', 'Iêù', 'Iều', 'IÊÙ', 'IỀU'],
			'iêủ' => ['iểu', 'Iêủ', 'Iểu', 'IÊỦ', 'IỂU'],
			'iêũ' => ['iễu', 'Iêũ', 'Iễu', 'IÊŨ', 'IỄU'],
			'iêú' => ['iếu', 'Iêú', 'Iếu', 'IÊÚ', 'IẾU'],
			'iêụ' => ['iệu', 'Iêụ', 'Iệu', 'IÊỤ', 'IỆU'],
			'òac' => ['oàc', 'Òac', 'Oàc', 'ÒAC', 'OÀC'],
			'ỏac' => ['oảc', 'Ỏac', 'Oảc', 'ỎAC', 'OẢC'],
			'õac' => ['oãc', 'Õac', 'Oãc', 'ÕAC', 'OÃC'],
			'óac' => ['oác', 'Óac', 'Oác', 'ÓAC', 'OÁC'],
			'ọac' => ['oạc', 'Ọac', 'Oạc', 'ỌAC', 'OẠC'],
			'òai' => ['oài', 'Òai', 'Oài', 'ÒAI', 'OÀI'],
			'ỏai' => ['oải', 'Ỏai', 'Oải', 'ỎAI', 'OẢI'],
			'õai' => ['oãi', 'Õai', 'Oãi', 'ÕAI', 'OÃI'],
			'óai' => ['oái', 'Óai', 'Oái', 'ÓAI', 'OÁI'],
			'ọai' => ['oại', 'Ọai', 'Oại', 'ỌAI', 'OẠI'],
			'oaì' => ['oài', 'Oaì', 'Oài', 'OAÌ', 'OÀI'],
			'oaỉ' => ['oải', 'Oaỉ', 'Oải', 'OAỈ', 'OẢI'],
			'oaĩ' => ['oãi', 'Oaĩ', 'Oãi', 'OAĨ', 'OÃI'],
			'oaí' => ['oái', 'Oaí', 'Oái', 'OAÍ', 'OÁI'],
			'oaị' => ['oại', 'Oaị', 'Oại', 'OAỊ', 'OẠI'],
			'òan' => ['oàn', 'Òan', 'Oàn', 'ÒAN', 'OÀN'],
			'ỏan' => ['oản', 'Ỏan', 'Oản', 'ỎAN', 'OẢN'],
			'õan' => ['oãn', 'Õan', 'Oãn', 'ÕAN', 'OÃN'],
			'óan' => ['oán', 'Óan', 'Oán', 'ÓAN', 'OÁN'],
			'ọan' => ['oạn', 'Ọan', 'Oạn', 'ỌAN', 'OẠN'],
			'òat' => ['oàt', 'Òat', 'Oàt', 'ÒAT', 'OÀT'],
			'ỏat' => ['oảt', 'Ỏat', 'Oảt', 'ỎAT', 'OẢT'],
			'õat' => ['oãt', 'Õat', 'Oãt', 'ÕAT', 'OÃT'],
			'óat' => ['oát', 'Óat', 'Oát', 'ÓAT', 'OÁT'],
			'ọat' => ['oạt', 'Ọat', 'Oạt', 'ỌAT', 'OẠT'],
			'òay' => ['oày', 'Òay', 'Oày', 'ÒAY', 'OÀY'],
			'ỏay' => ['oảy', 'Ỏay', 'Oảy', 'ỎAY', 'OẢY'],
			'õay' => ['oãy', 'Õay', 'Oãy', 'ÕAY', 'OÃY'],
			'óay' => ['oáy', 'Óay', 'Oáy', 'ÓAY', 'OÁY'],
			'ọay' => ['oạy', 'Ọay', 'Oạy', 'ỌAY', 'OẠY'],
			'oaỳ' => ['oày', 'Oaỳ', 'Oày', 'OAỲ', 'OÀY'],
			'oaỷ' => ['oảy', 'Oaỷ', 'Oảy', 'OAỶ', 'OẢY'],
			'oaỹ' => ['oãy', 'Oaỹ', 'Oãy', 'OAỸ', 'OÃY'],
			'oaý' => ['oáy', 'Oaý', 'Oáy', 'OAÝ', 'OÁY'],
			'oaỵ' => ['oạy', 'Oaỵ', 'Oạy', 'OAỴ', 'OẠY'],
			'òăc' => ['oằc', 'Òăc', 'Oằc', 'ÒĂC', 'OẰC'],
			'ỏăc' => ['oẳc', 'Ỏăc', 'Oẳc', 'ỎĂC', 'OẲC'],
			'õăc' => ['oẵc', 'Õăc', 'Oẵc', 'ÕĂC', 'OẴC'],
			'óăc' => ['oắc', 'Óăc', 'Oắc', 'ÓĂC', 'OẮC'],
			'ọăc' => ['oặc', 'Ọăc', 'Oặc', 'ỌĂC', 'OẶC'],
			'òăn' => ['oằn', 'Òăn', 'Oằn', 'ÒĂN', 'OẰN'],
			'ỏăn' => ['oẳn', 'Ỏăn', 'Oẳn', 'ỎĂN', 'OẲN'],
			'õăn' => ['oẵn', 'Õăn', 'Oẵn', 'ÕĂN', 'OẴN'],
			'óăn' => ['oắn', 'Óăn', 'Oắn', 'ÓĂN', 'OẮN'],
			'ọăn' => ['oặn', 'Ọăn', 'Oặn', 'ỌĂN', 'OẶN'],
			'òăt' => ['oằt', 'Òăt', 'Oằt', 'ÒĂT', 'OẰT'],
			'ỏăt' => ['oẳt', 'Ỏăt', 'Oẳt', 'ỎĂT', 'OẲT'],
			'õăt' => ['oẵt', 'Õăt', 'Oẵt', 'ÕĂT', 'OẴT'],
			'óăt' => ['oắt', 'Óăt', 'Oắt', 'ÓĂT', 'OẮT'],
			'ọăt' => ['oặt', 'Ọăt', 'Oặt', 'ỌĂT', 'OẶT'],
			'òen' => ['oèn', 'Òen', 'Oèn', 'ÒEN', 'OÈN'],
			'ỏen' => ['oẻn', 'Ỏen', 'Oẻn', 'ỎEN', 'OẺN'],
			'õen' => ['oẽn', 'Õen', 'Oẽn', 'ÕEN', 'OẼN'],
			'óen' => ['oén', 'Óen', 'Oén', 'ÓEN', 'OÉN'],
			'ọen' => ['oẹn', 'Ọen', 'Oẹn', 'ỌEN', 'OẸN'],
			'ùân' => ['uần', 'Ùân', 'Uần', 'ÙÂN', 'UẦN'],
			'ủân' => ['uẩn', 'Ủân', 'Uẩn', 'ỦÂN', 'UẨN'],
			'ũân' => ['uẫn', 'Ũân', 'Uẫn', 'ŨÂN', 'UẪN'],
			'úân' => ['uấn', 'Úân', 'Uấn', 'ÚÂN', 'UẤN'],
			'ụân' => ['uận', 'Ụân', 'Uận', 'ỤÂN', 'UẬN'],
			'ùât' => ['uầt', 'Ùât', 'Uầt', 'ÙÂT', 'UẦT'],
			'ủât' => ['uẩt', 'Ủât', 'Uẩt', 'ỦÂT', 'UẨT'],
			'ũât' => ['uẫt', 'Ũât', 'Uẫt', 'ŨÂT', 'UẪT'],
			'úât' => ['uất', 'Úât', 'Uất', 'ÚÂT', 'UẤT'],
			'ụât' => ['uật', 'Ụât', 'Uật', 'ỤÂT', 'UẬT'],
			'ùây' => ['uầy', 'Ùây', 'Uầy', 'ÙÂY', 'UẦY'],
			'ủây' => ['uẩy', 'Ủây', 'Uẩy', 'ỦÂY', 'UẨY'],
			'ũây' => ['uẫy', 'Ũây', 'Uẫy', 'ŨÂY', 'UẪY'],
			'úây' => ['uấy', 'Úây', 'Uấy', 'ÚÂY', 'UẤY'],
			'ụây' => ['uậy', 'Ụây', 'Uậy', 'ỤÂY', 'UẬY'],
			'uâỳ' => ['uầy', 'Uâỳ', 'Uầy', 'UÂỲ', 'UẦY'],
			'uâỷ' => ['uẩy', 'Uâỷ', 'Uẩy', 'UÂỶ', 'UẨY'],
			'uâỹ' => ['uẫy', 'Uâỹ', 'Uẫy', 'UÂỸ', 'UẪY'],
			'uâý' => ['uấy', 'Uâý', 'Uấy', 'UÂÝ', 'UẤY'],
			'uâỵ' => ['uậy', 'Uâỵ', 'Uậy', 'UÂỴ', 'UẬY'],
			'ùôc' => ['uồc', 'Ùôc', 'Uồc', 'ÙÔC', 'UỒC'],
			'ủôc' => ['uổc', 'Ủôc', 'Uổc', 'ỦÔC', 'UỔC'],
			'ũôc' => ['uỗc', 'Ũôc', 'Uỗc', 'ŨÔC', 'UỖC'],
			'úôc' => ['uốc', 'Úôc', 'Uốc', 'ÚÔC', 'UỐC'],
			'ụôc' => ['uộc', 'Ụôc', 'Uộc', 'ỤÔC', 'UỘC'],
			'ùôi' => ['uồi', 'Ùôi', 'Uồi', 'ÙÔI', 'UỒI'],
			'ủôi' => ['uổi', 'Ủôi', 'Uổi', 'ỦÔI', 'UỔI'],
			'ũôi' => ['uỗi', 'Ũôi', 'Uỗi', 'ŨÔI', 'UỖI'],
			'úôi' => ['uối', 'Úôi', 'Uối', 'ÚÔI', 'UỐI'],
			'ụôi' => ['uội', 'Ụôi', 'Uội', 'ỤÔI', 'UỘI'],
			'uôì' => ['uồi', 'Uôì', 'Uồi', 'UÔÌ', 'UỒI'],
			'uôỉ' => ['uổi', 'Uôỉ', 'Uổi', 'UÔỈ', 'UỔI'],
			'uôĩ' => ['uỗi', 'Uôĩ', 'Uỗi', 'UÔĨ', 'UỖI'],
			'uôí' => ['uối', 'Uôí', 'Uối', 'UÔÍ', 'UỐI'],
			'uôị' => ['uội', 'Uôị', 'Uội', 'UÔỊ', 'UỘI'],
			'ùôm' => ['uồm', 'Ùôm', 'Uồm', 'ÙÔM', 'UỒM'],
			'ủôm' => ['uổm', 'Ủôm', 'Uổm', 'ỦÔM', 'UỔM'],
			'ũôm' => ['uỗm', 'Ũôm', 'Uỗm', 'ŨÔM', 'UỖM'],
			'úôm' => ['uốm', 'Úôm', 'Uốm', 'ÚÔM', 'UỐM'],
			'ụôm' => ['uộm', 'Ụôm', 'Uộm', 'ỤÔM', 'UỘM'],
			'ùôn' => ['uồn', 'Ùôn', 'Uồn', 'ÙÔN', 'UỒN'],
			'ủôn' => ['uổn', 'Ủôn', 'Uổn', 'ỦÔN', 'UỔN'],
			'ũôn' => ['uỗn', 'Ũôn', 'Uỗn', 'ŨÔN', 'UỖN'],
			'úôn' => ['uốn', 'Úôn', 'Uốn', 'ÚÔN', 'UỐN'],
			'ụôn' => ['uộn', 'Ụôn', 'Uộn', 'ỤÔN', 'UỘN'],
			'ùôt' => ['uồt', 'Ùôt', 'Uồt', 'ÙÔT', 'UỒT'],
			'ủôt' => ['uổt', 'Ủôt', 'Uổt', 'ỦÔT', 'UỔT'],
			'ũôt' => ['uỗt', 'Ũôt', 'Uỗt', 'ŨÔT', 'UỖT'],
			'úôt' => ['uốt', 'Úôt', 'Uốt', 'ÚÔT', 'UỐT'],
			'ụôt' => ['uột', 'Ụôt', 'Uột', 'ỤÔT', 'UỘT'],
			'ùya' => ['uỳa', 'Ùya', 'Uỳa', 'ÙYA', 'UỲA'],
			'ủya' => ['uỷa', 'Ủya', 'Uỷa', 'ỦYA', 'UỶA'],
			'ũya' => ['uỹa', 'Ũya', 'Uỹa', 'ŨYA', 'UỸA'],
			'úya' => ['uýa', 'Úya', 'Uýa', 'ÚYA', 'UÝA'],
			'ụya' => ['uỵa', 'Ụya', 'Uỵa', 'ỤYA', 'UỴA'],
			'uyà' => ['uỳa', 'Uyà', 'Uỳa', 'UYÀ', 'UỲA'],
			'uyả' => ['uỷa', 'Uyả', 'Uỷa', 'UYẢ', 'UỶA'],
			'uyã' => ['uỹa', 'Uyã', 'Uỹa', 'UYÃ', 'UỸA'],
			'uyá' => ['uýa', 'Uyá', 'Uýa', 'UYÁ', 'UÝA'],
			'uyạ' => ['uỵa', 'Uyạ', 'Uỵa', 'UYẠ', 'UỴA'],
			'ùyt' => ['uỳt', 'Ùyt', 'Uỳt', 'ÙYT', 'UỲT'],
			'ủyt' => ['uỷt', 'Ủyt', 'Uỷt', 'ỦYT', 'UỶT'],
			'ũyt' => ['uỹt', 'Ũyt', 'Uỹt', 'ŨYT', 'UỸT'],
			'úyt' => ['uýt', 'Úyt', 'Uýt', 'ÚYT', 'UÝT'],
			'ụyt' => ['uỵt', 'Ụyt', 'Uỵt', 'ỤYT', 'UỴT'],
			'ùyu' => ['uỳu', 'Ùyu', 'Uỳu', 'ÙYU', 'UỲU'],
			'ủyu' => ['uỷu', 'Ủyu', 'Uỷu', 'ỦYU', 'UỶU'],
			'ũyu' => ['uỹu', 'Ũyu', 'Uỹu', 'ŨYU', 'UỸU'],
			'úyu' => ['uýu', 'Úyu', 'Uýu', 'ÚYU', 'UÝU'],
			'ụyu' => ['uỵu', 'Ụyu', 'Uỵu', 'ỤYU', 'UỴU'],
			'uyù' => ['uỳu', 'Uyù', 'Uỳu', 'UYÙ', 'UỲU'],
			'uyủ' => ['uỷu', 'Uyủ', 'Uỷu', 'UYỦ', 'UỶU'],
			'uyũ' => ['uỹu', 'Uyũ', 'Uỹu', 'UYŨ', 'UỸU'],
			'uyú' => ['uýu', 'Uyú', 'Uýu', 'UYÚ', 'UÝU'],
			'uyụ' => ['uỵu', 'Uyụ', 'Uỵu', 'UYỤ', 'UỴU'],
			'ừơc' => ['ườc', 'Ừơc', 'Ườc', 'ỪƠC', 'ƯỜC'],
			'ửơc' => ['ưởc', 'Ửơc', 'Ưởc', 'ỬƠC', 'ƯỞC'],
			'ữơc' => ['ưỡc', 'Ữơc', 'Ưỡc', 'ỮƠC', 'ƯỠC'],
			'ứơc' => ['ước', 'Ứơc', 'Ước', 'ỨƠC', 'ƯỚC'],
			'ựơc' => ['ược', 'Ựơc', 'Ược', 'ỰƠC', 'ƯỢC'],
			'ừơi' => ['ười', 'Ừơi', 'Ười', 'ỪƠI', 'ƯỜI'],
			'ửơi' => ['ưởi', 'Ửơi', 'Ưởi', 'ỬƠI', 'ƯỞI'],
			'ữơi' => ['ưỡi', 'Ữơi', 'Ưỡi', 'ỮƠI', 'ƯỠI'],
			'ứơi' => ['ưới', 'Ứơi', 'Ưới', 'ỨƠI', 'ƯỚI'],
			'ựơi' => ['ượi', 'Ựơi', 'Ượi', 'ỰƠI', 'ƯỢI'],
			'ươì' => ['ười', 'Ươì', 'Ười', 'ƯƠÌ', 'ƯỜI'],
			'ươỉ' => ['ưởi', 'Ươỉ', 'Ưởi', 'ƯƠỈ', 'ƯỞI'],
			'ươĩ' => ['ưỡi', 'Ươĩ', 'Ưỡi', 'ƯƠĨ', 'ƯỠI'],
			'ươí' => ['ưới', 'Ươí', 'Ưới', 'ƯƠÍ', 'ƯỚI'],
			'ươị' => ['ượi', 'Ươị', 'Ượi', 'ƯƠỊ', 'ƯỢI'],
			'ừơm' => ['ườm', 'Ừơm', 'Ườm', 'ỪƠM', 'ƯỜM'],
			'ửơm' => ['ưởm', 'Ửơm', 'Ưởm', 'ỬƠM', 'ƯỞM'],
			'ữơm' => ['ưỡm', 'Ữơm', 'Ưỡm', 'ỮƠM', 'ƯỠM'],
			'ứơm' => ['ướm', 'Ứơm', 'Ướm', 'ỨƠM', 'ƯỚM'],
			'ựơm' => ['ượm', 'Ựơm', 'Ượm', 'ỰƠM', 'ƯỢM'],
			'ừơn' => ['ườn', 'Ừơn', 'Ườn', 'ỪƠN', 'ƯỜN'],
			'ửơn' => ['ưởn', 'Ửơn', 'Ưởn', 'ỬƠN', 'ƯỞN'],
			'ữơn' => ['ưỡn', 'Ữơn', 'Ưỡn', 'ỮƠN', 'ƯỠN'],
			'ứơn' => ['ướn', 'Ứơn', 'Ướn', 'ỨƠN', 'ƯỚN'],
			'ựơn' => ['ượn', 'Ựơn', 'Ượn', 'ỰƠN', 'ƯỢN'],
			'ừơp' => ['ườp', 'Ừơp', 'Ườp', 'ỪƠP', 'ƯỜP'],
			'ửơp' => ['ưởp', 'Ửơp', 'Ưởp', 'ỬƠP', 'ƯỞP'],
			'ữơp' => ['ưỡp', 'Ữơp', 'Ưỡp', 'ỮƠP', 'ƯỠP'],
			'ứơp' => ['ướp', 'Ứơp', 'Ướp', 'ỨƠP', 'ƯỚP'],
			'ựơp' => ['ượp', 'Ựơp', 'Ượp', 'ỰƠP', 'ƯỢP'],
			'ừơt' => ['ườt', 'Ừơt', 'Ườt', 'ỪƠT', 'ƯỜT'],
			'ửơt' => ['ưởt', 'Ửơt', 'Ưởt', 'ỬƠT', 'ƯỞT'],
			'ữơt' => ['ưỡt', 'Ữơt', 'Ưỡt', 'ỮƠT', 'ƯỠT'],
			'ứơt' => ['ướt', 'Ứơt', 'Ướt', 'ỨƠT', 'ƯỚT'],
			'ựơt' => ['ượt', 'Ựơt', 'Ượt', 'ỰƠT', 'ƯỢT'],
			'ừơu' => ['ườu', 'Ừơu', 'Ườu', 'ỪƠU', 'ƯỜU'],
			'ửơu' => ['ưởu', 'Ửơu', 'Ưởu', 'ỬƠU', 'ƯỞU'],
			'ữơu' => ['ưỡu', 'Ữơu', 'Ưỡu', 'ỮƠU', 'ƯỠU'],
			'ứơu' => ['ướu', 'Ứơu', 'Ướu', 'ỨƠU', 'ƯỚU'],
			'ựơu' => ['ượu', 'Ựơu', 'Ượu', 'ỰƠU', 'ƯỢU'],
			'ươù' => ['ườu', 'Ươù', 'Ườu', 'ƯƠÙ', 'ƯỜU'],
			'ươủ' => ['ưởu', 'Ươủ', 'Ưởu', 'ƯƠỦ', 'ƯỞU'],
			'ươũ' => ['ưỡu', 'Ươũ', 'Ưỡu', 'ƯƠŨ', 'ƯỠU'],
			'ươú' => ['ướu', 'Ươú', 'Ướu', 'ƯƠÚ', 'ƯỚU'],
			'ươụ' => ['ượu', 'Ươụ', 'Ượu', 'ƯƠỤ', 'ƯỢU'],
			'ỳên' => ['yền', 'Ỳên', 'Yền', 'ỲÊN', 'YỀN'],
			'ỷên' => ['yển', 'Ỷên', 'Yển', 'ỶÊN', 'YỂN'],
			'ỹên' => ['yễn', 'Ỹên', 'Yễn', 'ỸÊN', 'YỄN'],
			'ýên' => ['yến', 'Ýên', 'Yến', 'ÝÊN', 'YẾN'],
			'ỵên' => ['yện', 'Ỵên', 'Yện', 'ỴÊN', 'YỆN'],
			'ỳêt' => ['yềt', 'Ỳêt', 'Yềt', 'ỲÊT', 'YỀT'],
			'ỷêt' => ['yểt', 'Ỷêt', 'Yểt', 'ỶÊT', 'YỂT'],
			'ỹêt' => ['yễt', 'Ỹêt', 'Yễt', 'ỸÊT', 'YỄT'],
			'ýêt' => ['yết', 'Ýêt', 'Yết', 'ÝÊT', 'YẾT'],
			'ỵêt' => ['yệt', 'Ỵêt', 'Yệt', 'ỴÊT', 'YỆT'],
			'ỳêu' => ['yều', 'Ỳêu', 'Yều', 'ỲÊU', 'YỀU'],
			'ỷêu' => ['yểu', 'Ỷêu', 'Yểu', 'ỶÊU', 'YỂU'],
			'ỹêu' => ['yễu', 'Ỹêu', 'Yễu', 'ỸÊU', 'YỄU'],
			'ýêu' => ['yếu', 'Ýêu', 'Yếu', 'ÝÊU', 'YẾU'],
			'ỵêu' => ['yệu', 'Ỵêu', 'Yệu', 'ỴÊU', 'YỆU'],
			'yêù' => ['yều', 'Yêù', 'Yều', 'YÊÙ', 'YỀU'],
			'yêủ' => ['yểu', 'Yêủ', 'Yểu', 'YÊỦ', 'YỂU'],
			'yêũ' => ['yễu', 'Yêũ', 'Yễu', 'YÊŨ', 'YỄU'],
			'yêú' => ['yếu', 'Yêú', 'Yếu', 'YÊÚ', 'YẾU'],
			'yêụ' => ['yệu', 'Yêụ', 'Yệu', 'YÊỤ', 'YỆU'],

			'ìêng' => ['iềng', 'Ìêng', 'Iềng', 'ÌÊNG', 'IỀNG'],
			'ỉêng' => ['iểng', 'Ỉêng', 'Iểng', 'ỈÊNG', 'IỂNG'],
			'ĩêng' => ['iễng', 'Ĩêng', 'Iễng', 'ĨÊNG', 'IỄNG'],
			'íêng' => ['iếng', 'Íêng', 'Iếng', 'ÍÊNG', 'IẾNG'],
			'ịêng' => ['iệng', 'Ịêng', 'Iệng', 'ỊÊNG', 'IỆNG'],
			'òang' => ['oàng', 'Òang', 'Oàng', 'ÒANG', 'OÀNG'],
			'ỏang' => ['oảng', 'Ỏang', 'Oảng', 'ỎANG', 'OẢNG'],
			'õang' => ['oãng', 'Õang', 'Oãng', 'ÕANG', 'OÃNG'],
			'óang' => ['oáng', 'Óang', 'Oáng', 'ÓANG', 'OÁNG'],
			'ọang' => ['oạng', 'Ọang', 'Oạng', 'ỌANG', 'OẠNG'],
			'òanh' => ['oành', 'Òanh', 'Oành', 'ÒANH', 'OÀNH'],
			'ỏanh' => ['oảnh', 'Ỏanh', 'Oảnh', 'ỎANH', 'OẢNH'],
			'õanh' => ['oãnh', 'Õanh', 'Oãnh', 'ÕANH', 'OÃNH'],
			'óanh' => ['oánh', 'Óanh', 'Oánh', 'ÓANH', 'OÁNH'],
			'ọanh' => ['oạnh', 'Ọanh', 'Oạnh', 'ỌANH', 'OẠNH'],
			'òăng' => ['oằng', 'Òăng', 'Oằng', 'ÒĂNG', 'OẰNG'],
			'ỏăng' => ['oẳng', 'Ỏăng', 'Oẳng', 'ỎĂNG', 'OẲNG'],
			'õăng' => ['oẵng', 'Õăng', 'Oẵng', 'ÕĂNG', 'OẴNG'],
			'óăng' => ['oắng', 'Óăng', 'Oắng', 'ÓĂNG', 'OẮNG'],
			'ọăng' => ['oặng', 'Ọăng', 'Oặng', 'ỌĂNG', 'OẶNG'],
			'òong' => ['oòng', 'Òong', 'Oòng', 'ÒONG', 'OÒNG'],
			'ỏong' => ['oỏng', 'Ỏong', 'Oỏng', 'ỎONG', 'OỎNG'],
			'õong' => ['oõng', 'Õong', 'Oõng', 'ÕONG', 'OÕNG'],
			'óong' => ['oóng', 'Óong', 'Oóng', 'ÓONG', 'OÓNG'],
			'ọong' => ['oọng', 'Ọong', 'Oọng', 'ỌONG', 'OỌNG'],
			'ùâng' => ['uầng', 'Ùâng', 'Uầng', 'ÙÂNG', 'UẦNG'],
			'ủâng' => ['uẩng', 'Ủâng', 'Uẩng', 'ỦÂNG', 'UẨNG'],
			'ũâng' => ['uẫng', 'Ũâng', 'Uẫng', 'ŨÂNG', 'UẪNG'],
			'úâng' => ['uấng', 'Úâng', 'Uấng', 'ÚÂNG', 'UẤNG'],
			'ụâng' => ['uậng', 'Ụâng', 'Uậng', 'ỤÂNG', 'UẬNG'],
			'ùông' => ['uồng', 'Ùông', 'Uồng', 'ÙÔNG', 'UỒNG'],
			'ủông' => ['uổng', 'Ủông', 'Uổng', 'ỦÔNG', 'UỔNG'],
			'ũông' => ['uỗng', 'Ũông', 'Uỗng', 'ŨÔNG', 'UỖNG'],
			'úông' => ['uống', 'Úông', 'Uống', 'ÚÔNG', 'UỐNG'],
			'ụông' => ['uộng', 'Ụông', 'Uộng', 'ỤÔNG', 'UỘNG'],
			'ùyên' => ['uyền', 'Ùyên', 'Uyền', 'ÙYÊN', 'UYỀN'],
			'ủyên' => ['uyển', 'Ủyên', 'Uyển', 'ỦYÊN', 'UYỂN'],
			'ũyên' => ['uyễn', 'Ũyên', 'Uyễn', 'ŨYÊN', 'UYỄN'],
			'úyên' => ['uyến', 'Úyên', 'Uyến', 'ÚYÊN', 'UYẾN'],
			'ụyên' => ['uyện', 'Ụyên', 'Uyện', 'ỤYÊN', 'UYỆN'],
			'uỳên' => ['uyền', 'Uỳên', 'Uyền', 'UỲÊN', 'UYỀN'],
			'uỷên' => ['uyển', 'Uỷên', 'Uyển', 'UỶÊN', 'UYỂN'],
			'uỹên' => ['uyễn', 'Uỹên', 'Uyễn', 'UỸÊN', 'UYỄN'],
			'uýên' => ['uyến', 'Uýên', 'Uyến', 'UÝÊN', 'UYẾN'],
			'uỵên' => ['uyện', 'Uỵên', 'Uyện', 'UỴÊN', 'UYỆN'],
			'ùyêt' => ['uyềt', 'Ùyêt', 'Uyềt', 'ÙYÊT', 'UYỀT'],
			'ủyêt' => ['uyểt', 'Ủyêt', 'Uyểt', 'ỦYÊT', 'UYỂT'],
			'ũyêt' => ['uyễt', 'Ũyêt', 'Uyễt', 'ŨYÊT', 'UYỄT'],
			'úyêt' => ['uyết', 'Úyêt', 'Uyết', 'ÚYÊT', 'UYẾT'],
			'ụyêt' => ['uyệt', 'Ụyêt', 'Uyệt', 'ỤYÊT', 'UYỆT'],
			'uỳêt' => ['uyềt', 'Uỳêt', 'Uyềt', 'UỲÊT', 'UYỀT'],
			'uỷêt' => ['uyểt', 'Uỷêt', 'Uyểt', 'UỶÊT', 'UYỂT'],
			'uỹêt' => ['uyễt', 'Uỹêt', 'Uyễt', 'UỸÊT', 'UYỄT'],
			'uýêt' => ['uyết', 'Uýêt', 'Uyết', 'UÝÊT', 'UYẾT'],
			'uỵêt' => ['uyệt', 'Uỵêt', 'Uyệt', 'UỴÊT', 'UYỆT'],
			'ùynh' => ['uỳnh', 'Ùynh', 'Uỳnh', 'ÙYNH', 'UỲNH'],
			'ủynh' => ['uỷnh', 'Ủynh', 'Uỷnh', 'ỦYNH', 'UỶNH'],
			'ũynh' => ['uỹnh', 'Ũynh', 'Uỹnh', 'ŨYNH', 'UỸNH'],
			'úynh' => ['uýnh', 'Úynh', 'Uýnh', 'ÚYNH', 'UÝNH'],
			'ụynh' => ['uỵnh', 'Ụynh', 'Uỵnh', 'ỤYNH', 'UỴNH'],
			'ừơng' => ['ường', 'Ừơng', 'Ường', 'ỪƠNG', 'ƯỜNG'],
			'ửơng' => ['ưởng', 'Ửơng', 'Ưởng', 'ỬƠNG', 'ƯỞNG'],
			'ữơng' => ['ưỡng', 'Ữơng', 'Ưỡng', 'ỮƠNG', 'ƯỠNG'],
			'ứơng' => ['ướng', 'Ứơng', 'Ướng', 'ỨƠNG', 'ƯỚNG'],
			'ựơng' => ['ượng', 'Ựơng', 'Ượng', 'ỰƠNG', 'ƯỢNG']
		],

		/**
		 * I or Y cases.
		 *
		 * What is the rule?
		 * Please view details in the document file.
		 *
		 * Indexes:
		 *	0 -> Replace text in lowercase.
		 *	1 -> Find text in upper first character and lower remain characters.
		 *	2 -> Replace text in upper first character and lower remain characters.
		 *	3 -> Find text in uppercase.
		 *	4 -> Replace text in uppercase.
		 *	5 -> true: can within another word (ex: 'hì' within 'hình'), use preg_replace().
		 *		false: never within another word, safe to use str_replace().
		 */
		'i_or_y' => [
			'i' => [
				'only' => [
					'by'	=> ['bi', 'By', 'Bi', 'BY', 'BI', false],
					'bỳ'	=> ['bì', 'Bỳ', 'Bì', 'BỲ', 'BÌ', false],
					'bỷ'	=> ['bỉ', 'Bỷ', 'Bỉ', 'BỶ', 'BỈ', false],
					'bỹ'	=> ['bĩ', 'Bỹ', 'Bĩ', 'BỸ', 'BĨ', false],
					'bý'	=> ['bí', 'Bý', 'Bí', 'BÝ', 'BÍ', false],
					'bỵ'	=> ['bị', 'Bỵ', 'Bị', 'BỴ', 'BỊ', false],
					'chy'	=> ['chi', 'Chy', 'Chi', 'CHY', 'CHI', false],
					'chỳ'	=> ['chì', 'Chỳ', 'Chì', 'CHỲ', 'CHÌ', false],
					'chỷ'	=> ['chỉ', 'Chỷ', 'Chỉ', 'CHỶ', 'CHỈ', false],
					'chỹ'	=> ['chĩ', 'Chỹ', 'Chĩ', 'CHỸ', 'CHĨ', false],
					'chý'	=> ['chí', 'Chý', 'Chí', 'CHÝ', 'CHÍ', false],
					'chỵ'	=> ['chị', 'Chỵ', 'Chị', 'CHỴ', 'CHỊ', false],
					'dy'	=> ['di', 'Dy', 'Di', 'DY', 'DI', false],
					'dỳ'	=> ['dì', 'Dỳ', 'Dì', 'DỲ', 'DÌ', false],
					'dỷ'	=> ['dỉ', 'Dỷ', 'Dỉ', 'DỶ', 'DỈ', false],
					'dỹ'	=> ['dĩ', 'Dỹ', 'Dĩ', 'DỸ', 'DĨ', false],
					'dý'	=> ['dí', 'Dý', 'Dí', 'DÝ', 'DÍ', false],
					'dỵ'	=> ['dị', 'Dỵ', 'Dị', 'DỴ', 'DỊ', false],
					'đy'	=> ['đi', 'Đy', 'Đi', 'ĐY', 'ĐI', false],
					'đỳ'	=> ['đì', 'Đỳ', 'Đì', 'ĐỲ', 'ĐÌ', false],
					'đỷ'	=> ['đỉ', 'Đỷ', 'Đỉ', 'ĐỶ', 'ĐỈ', false],
					'đỹ'	=> ['đĩ', 'Đỹ', 'Đĩ', 'ĐỸ', 'ĐĨ', false],
					'đý'	=> ['đí', 'Đý', 'Đí', 'ĐÝ', 'ĐÍ', false],
					'đỵ'	=> ['đị', 'Đỵ', 'Đị', 'ĐỴ', 'ĐỊ', false],
					'ghy'	=> ['ghi', 'Ghy', 'Ghi', 'GHY', 'GHI', false],
					'ghỳ'	=> ['ghì', 'Ghỳ', 'Ghì', 'GHỲ', 'GHÌ', false],
					'ghỷ'	=> ['ghỉ', 'Ghỷ', 'Ghỉ', 'GHỶ', 'GHỈ', false],
					'ghỹ'	=> ['ghĩ', 'Ghỹ', 'Ghĩ', 'GHỸ', 'GHĨ', false],
					'ghý'	=> ['ghí', 'Ghý', 'Ghí', 'GHÝ', 'GHÍ', false],
					'ghỵ'	=> ['ghị', 'Ghỵ', 'Ghị', 'GHỴ', 'GHỊ', false],
					'gỳ'	=> ['gì', 'Gỳ', 'Gì', 'GỲ', 'GÌ', false],
					'gỷ'	=> ['gỉ', 'Gỷ', 'Gỉ', 'GỶ', 'GỈ', false],
					'gỹ'	=> ['gĩ', 'Gỹ', 'Gĩ', 'GỸ', 'GĨ', false],
					'gý'	=> ['gí', 'Gý', 'Gí', 'GÝ', 'GÍ', false],
					'gỵ'	=> ['gị', 'Gỵ', 'Gị', 'GỴ', 'GỊ', false],
					'hỳ'	=> ['hì', 'Hỳ', 'Hì', 'HỲ', 'HÌ', false],
					'hỹ'	=> ['hĩ', 'Hỹ', 'Hĩ', 'HỸ', 'HĨ', false],
					'hỵ'	=> ['hị', 'Hỵ', 'Hị', 'HỴ', 'HỊ', false],
					'khy'	=> ['khi', 'Khy', 'Khi', 'KHY', 'KHI', false],
					'khỳ'	=> ['khì', 'Khỳ', 'Khì', 'KHỲ', 'KHÌ', false],
					'khỷ'	=> ['khỉ', 'Khỷ', 'Khỉ', 'KHỶ', 'KHỈ', false],
					'khỹ'	=> ['khĩ', 'Khỹ', 'Khĩ', 'KHỸ', 'KHĨ', false],
					'khý'	=> ['khí', 'Khý', 'Khí', 'KHÝ', 'KHÍ', false],
					'khỵ'	=> ['khị', 'Khỵ', 'Khị', 'KHỴ', 'KHỊ', false],
					'ky'	=> ['ki', 'Ky', 'Ki', 'KY', 'KI', false],
					'lỳ'	=> ['lì', 'Lỳ', 'Lì', 'LỲ', 'LÌ', false],
					'lỹ'	=> ['lĩ', 'Lỹ', 'Lĩ', 'LỸ', 'LĨ', false],
					'mỷ'	=> ['mỉ', 'Mỷ', 'Mỉ', 'MỶ', 'MỈ', false],
					'mý'	=> ['mí', 'Mý', 'Mí', 'MÝ', 'MÍ', false],
					'nghy'	=> ['nghi', 'Nghy', 'Nghi', 'NGHY', 'NGHI', false],
					'nghỳ'	=> ['nghì', 'Nghỳ', 'Nghì', 'NGHỲ', 'NGHÌ', false],
					'nghỷ'	=> ['nghỉ', 'Nghỷ', 'Nghỉ', 'NGHỶ', 'NGHỈ', false],
					'nghỹ'	=> ['nghĩ', 'Nghỹ', 'Nghĩ', 'NGHỸ', 'NGHĨ', false],
					'nghý'	=> ['nghí', 'Nghý', 'Nghí', 'NGHÝ', 'NGHÍ', false],
					'nghỵ'	=> ['nghị', 'Nghỵ', 'Nghị', 'NGHỴ', 'NGHỊ', false],
					'nhy'	=> ['nhi', 'Nhy', 'Nhi', 'NHY', 'NHI', false],
					'nhỳ'	=> ['nhì', 'Nhỳ', 'Nhì', 'NHỲ', 'NHÌ', false],
					'nhỷ'	=> ['nhỉ', 'Nhỷ', 'Nhỉ', 'NHỶ', 'NHỈ', false],
					'nhỹ'	=> ['nhĩ', 'Nhỹ', 'Nhĩ', 'NHỸ', 'NHĨ', false],
					'nhý'	=> ['nhí', 'Nhý', 'Nhí', 'NHÝ', 'NHÍ', false],
					'nhỵ'	=> ['nhị', 'Nhỵ', 'Nhị', 'NHỴ', 'NHỊ', false],
					'ny'	=> ['ni', 'Ny', 'Ni', 'NY', 'NI', false],
					'nỳ'	=> ['nì', 'Nỳ', 'Nì', 'NỲ', 'NÌ', false],
					'nỷ'	=> ['nỉ', 'Nỷ', 'Nỉ', 'NỶ', 'NỈ', false],
					'nỹ'	=> ['nĩ', 'Nỹ', 'Nĩ', 'NỸ', 'NĨ', false],
					'ný'	=> ['ní', 'Ný', 'Ní', 'NÝ', 'NÍ', false],
					'nỵ'	=> ['nị', 'Nỵ', 'Nị', 'NỴ', 'NỊ', false],
					'oy'	=> ['oi', 'Oy', 'Oi', 'OY', 'OI', false],
					'òy'	=> ['òi', 'Òy', 'Òi', 'ÒY', 'ÒI', false],
					'ỏy'	=> ['ỏi', 'Ỏy', 'Ỏi', 'ỎY', 'ỎI', false],
					'õy'	=> ['õi', 'Õy', 'Õi', 'ÕY', 'ÕI', false],
					'óy'	=> ['ói', 'Óy', 'Ói', 'ÓY', 'ÓI', false],
					'ọy'	=> ['ọi', 'Ọy', 'Ọi', 'ỌY', 'ỌI', false],
					'ôy'	=> ['ôi', 'Ôy', 'Ôi', 'ÔY', 'ÔI', false],
					'ồy'	=> ['ồi', 'Ồy', 'Ồi', 'ỒY', 'ỒI', false],
					'ổy'	=> ['ổi', 'Ổy', 'Ổi', 'ỔY', 'ỔI', false],
					'ỗy'	=> ['ỗi', 'Ỗy', 'Ỗi', 'ỖY', 'ỖI', false],
					'ốy'	=> ['ối', 'Ốy', 'Ối', 'ỐY', 'ỐI', false],
					'ộy'	=> ['ội', 'Ộy', 'Ội', 'ỘY', 'ỘI', false],
					'ơy'	=> ['ơi', 'Ơy', 'Ơi', 'ƠY', 'ƠI', false],
					'ờy'	=> ['ời', 'Ờy', 'Ời', 'ỜY', 'ỜI', false],
					'ởy'	=> ['ởi', 'Ởy', 'Ởi', 'ỞY', 'ỞI', false],
					'ỡy'	=> ['ỡi', 'Ỡy', 'Ỡi', 'ỠY', 'ỠI', false],
					'ớy'	=> ['ới', 'Ớy', 'Ới', 'ỚY', 'ỚI', false],
					'ợy'	=> ['ợi', 'Ợy', 'Ợi', 'ỢY', 'ỢI', false],
					'phy'	=> ['phi', 'Phy', 'Phi', 'PHY', 'PHI', false],
					'phỳ'	=> ['phì', 'Phỳ', 'Phì', 'PHỲ', 'PHÌ', false],
					'phỷ'	=> ['phỉ', 'Phỷ', 'Phỉ', 'PHỶ', 'PHỈ', false],
					'phỹ'	=> ['phĩ', 'Phỹ', 'Phĩ', 'PHỸ', 'PHĨ', false],
					'phý'	=> ['phí', 'Phý', 'Phí', 'PHÝ', 'PHÍ', false],
					'phỵ'	=> ['phị', 'Phỵ', 'Phị', 'PHỴ', 'PHỊ', false],
					'ry'	=> ['ri', 'Ry', 'Ri', 'RY', 'RI', false],
					'rỳ'	=> ['rì', 'Rỳ', 'Rì', 'RỲ', 'RÌ', false],
					'rỷ'	=> ['rỉ', 'Rỷ', 'Rỉ', 'RỶ', 'RỈ', false],
					'rỹ'	=> ['rĩ', 'Rỹ', 'Rĩ', 'RỸ', 'RĨ', false],
					'rý'	=> ['rí', 'Rý', 'Rí', 'RÝ', 'RÍ', false],
					'rỵ'	=> ['rị', 'Rỵ', 'Rị', 'RỴ', 'RỊ', false],
					'sỳ'	=> ['sì', 'Sỳ', 'Sì', 'SỲ', 'SÌ', false],
					'sỷ'	=> ['sỉ', 'Sỷ', 'Sỉ', 'SỶ', 'SỈ', false],
					'thy'	=> ['thi', 'Thy', 'Thi', 'THY', 'THI', false],
					'thỳ'	=> ['thì', 'Thỳ', 'Thì', 'THỲ', 'THÌ', false],
					'thỷ'	=> ['thỉ', 'Thỷ', 'Thỉ', 'THỶ', 'THỈ', false],
					'thỹ'	=> ['thĩ', 'Thỹ', 'Thĩ', 'THỸ', 'THĨ', false],
					'thý'	=> ['thí', 'Thý', 'Thí', 'THÝ', 'THÍ', false],
					'thỵ'	=> ['thị', 'Thỵ', 'Thị', 'THỴ', 'THỊ', false],
					'try'	=> ['tri', 'Try', 'Tri', 'TRY', 'TRI', false],
					'trỳ'	=> ['trì', 'Trỳ', 'Trì', 'TRỲ', 'TRÌ', false],
					'trỷ'	=> ['trỉ', 'Trỷ', 'Trỉ', 'TRỶ', 'TRỈ', false],
					'trỹ'	=> ['trĩ', 'Trỹ', 'Trĩ', 'TRỸ', 'TRĨ', false],
					'trý'	=> ['trí', 'Trý', 'Trí', 'TRÝ', 'TRÍ', false],
					'trỵ'	=> ['trị', 'Trỵ', 'Trị', 'TRỴ', 'TRỊ', false],
					'tỹ'	=> ['tĩ', 'Tỹ', 'Tĩ', 'TỸ', 'TĨ', false],
					'ưy'	=> ['ưi', 'Ưy', 'Ưi', 'ƯY', 'ƯI', false],
					'ừy'	=> ['ừi', 'Ừy', 'Ừi', 'ỪY', 'ỪI', false],
					'ửy'	=> ['ửi', 'Ửy', 'Ửi', 'ỬY', 'ỬI', false],
					'ữy'	=> ['ữi', 'Ữy', 'Ữi', 'ỮY', 'ỮI', false],
					'ứy'	=> ['ứi', 'Ứy', 'Ứi', 'ỨY', 'ỨI', false],
					'ựy'	=> ['ựi', 'Ựy', 'Ựi', 'ỰY', 'ỰI', false],
					'vy'	=> ['vi', 'Vy', 'Vi', 'VY', 'VI', false],
					'vỳ'	=> ['vì', 'Vỳ', 'Vì', 'VỲ', 'VÌ', false],
					'vỷ'	=> ['vỉ', 'Vỷ', 'Vỉ', 'VỶ', 'VỈ', false],
					'vỹ'	=> ['vĩ', 'Vỹ', 'Vĩ', 'VỸ', 'VĨ', false],
					'vý'	=> ['ví', 'Vý', 'Ví', 'VÝ', 'VÍ', false],
					'vỵ'	=> ['vị', 'Vỵ', 'Vị', 'VỴ', 'VỊ', false],
					'xy'	=> ['xi', 'Xy', 'Xi', 'XY', 'XI', false],
					'xỳ'	=> ['xì', 'Xỳ', 'Xì', 'XỲ', 'XÌ', false],
					'xỷ'	=> ['xỉ', 'Xỷ', 'Xỉ', 'XỶ', 'XỈ', false],
					'xỹ'	=> ['xĩ', 'Xỹ', 'Xĩ', 'XỸ', 'XĨ', false],
					'xý'	=> ['xí', 'Xý', 'Xí', 'XÝ', 'XÍ', false],
					'xỵ'	=> ['xị', 'Xỵ', 'Xị', 'XỴ', 'XỊ', false]
				],
				'major' => [
					'my' => ['mi', 'My', 'Mi', 'MY', 'MI', false],
					'mỳ' => ['mì', 'Mỳ', 'Mì', 'MỲ', 'MÌ', false],
					'tý' => ['tí', 'Tý', 'Tí', 'TÝ', 'TÍ', false],
					'tỵ' => ['tị', 'Tỵ', 'Tị', 'TỴ', 'TỊ', false]
				],
				'fix' => [
					'tu mi'		=> ['tu my', 'Tu mi', 'Tu my', 'TU MI', 'TU MY', false],
					'nga mi'	=> ['nga my', 'Nga mi', 'Nga my', 'NGA MI', 'NGA MY', false],
					'Nga Mi'	=> ['Nga My', null, null, null, null, false],// Fix extra: nga my+
					'nhu mì'	=> ['nhu mỳ', 'Nhu mì', 'Nhu mỳ', 'NHU MÌ', 'NHU MỲ', false],
					'tuổi Tí'	=> ['tuổi Tý', 'Tuổi Tí', 'Tuổi Tý', 'TUỔI TÍ', 'TUỔI TÝ', false],// Fix extra: Tý+
					'Canh Tí'	=> ['Canh Tý', null, null, 'CANH TÍ', 'CANH TÝ', false],// Fix extra: Tý+
					'Nhâm Tí'	=> ['Nhâm Tý', null, null, 'NHÂM TÍ', 'NHÂM TÝ', false],// Fix extra: Tý+
					'Giáp Tí'	=> ['Giáp Tý', null, null, 'GIÁP TÍ', 'GIÁP TÝ', false],// Fix extra: Tý+
					'Bính Tí'	=> ['Bính Tý', null, null, 'BÍNH TÍ', 'BÍNH TÝ', false],// Fix extra: Tý+
					'Mậu Tí'	=> ['Mậu Tý', null, null, 'MẬU TÍ', 'MẬU TÝ', false]// Fix extra: Tý+
				]
			],
			'y' => [
				'only' => [
					'âi'	=> ['ây', 'Âi', 'Ây', 'ÂI', 'ÂY', false],
					'ầi'	=> ['ầy', 'Ầi', 'Ầy', 'ẦI', 'ẦY', false],
					'ẩi'	=> ['ẩy', 'Ẩi', 'Ẩy', 'ẨI', 'ẨY', false],
					'ẫi'	=> ['ẫy', 'Ẫi', 'Ẫy', 'ẪI', 'ẪY', false],
					'ấi'	=> ['ấy', 'Ấi', 'Ấy', 'ẤI', 'ẤY', false],
					'ậi'	=> ['ậy', 'Ậi', 'Ậy', 'ẬI', 'ẬY', false],
					'kỉ'	=> ['kỷ', 'Kỉ', 'Kỷ', 'KỈ', 'KỶ', true],// Ex: lỉnh kỉnh
					'mĩ'	=> ['mỹ', 'Mĩ', 'Mỹ', 'MĨ', 'MỸ', true],// Ex: mũm mĩm
					'qui'	=> ['quy', 'Qui', 'Quy', 'QUI', 'QUY', false],
					'quì'	=> ['quỳ', 'Quì', 'Quỳ', 'QUÌ', 'QUỲ', false],
					'quỉ'	=> ['quỷ', 'Quỉ', 'Quỷ', 'QUỈ', 'QUỶ', false],
					'quĩ'	=> ['quỹ', 'Quĩ', 'Quỹ', 'QUĨ', 'QUỸ', false],
					'quí'	=> ['quý', 'Quí', 'Quý', 'QUÍ', 'QUÝ', false],
					'quị'	=> ['quỵ', 'Quị', 'Quỵ', 'QUỊ', 'QUỴ', false],
					'sĩ'	=> ['sỹ', 'Sĩ', 'Sỹ', 'SĨ', 'SỸ', false]
				],
				'major' => [
					'hi' => ['hy', 'Hi', 'Hy', 'HI', 'HY', true],// Ex: Him Lam
					'hỉ' => ['hỷ', 'Hỉ', 'Hỷ', 'HỈ', 'HỶ', true],// Ex: thỉnh cầu
					'hí' => ['hý', 'Hí', 'Hý', 'HÍ', 'HÝ', true],// Ex: hít hà
					'kì' => ['kỳ', 'Kì', 'Kỳ', 'KÌ', 'KỲ', true],// Ex: kình ngư
					'kĩ' => ['kỹ', 'Kĩ', 'Kỹ', 'KĨ', 'KỸ', false],
					'kí' => ['ký', 'Kí', 'Ký', 'KÍ', 'KÝ', true],// Ex: kín đáo
					'kị' => ['kỵ', 'Kị', 'Kỵ', 'KỊ', 'KỴ', true],// Ex: đen kịt
					'li' => ['ly', 'Li', 'Ly', 'LI', 'LY', true],// Ex: thần linh
					'lí' => ['lý', 'Lí', 'Lý', 'LÍ', 'LÝ', true],// Ex: quân lính
					'lị' => ['lỵ', 'Lị', 'Lỵ', 'LỊ', 'LỴ', true],// Ex: lia lịa
					'mị' => ['mỵ', 'Mị', 'Mỵ', 'MỊ', 'MỴ', true],// Ex: mịn màng
					'si' => ['sy', 'Si', 'Sy', 'SI', 'SY', true],// Ex: sinh đẻ
					'sỉ' => ['sỷ', 'Sỉ', 'Sỷ', 'SỈ', 'SỶ', false],
					'ti' => ['ty', 'Ti', 'Ty', 'TI', 'TY', true],// Ex: tinh thần
					'tì' => ['tỳ', 'Tì', 'Tỳ', 'TÌ', 'TỲ', true],// Ex: tình yêu
					'tỉ' => ['tỷ', 'Tỉ', 'Tỷ', 'TỈ', 'TỶ', true],// Ex: tỉnh thành
					'tị' => ['tỵ', 'Tị', 'Tỵ', 'TỊ', 'TỴ', true]// Ex: tịt ngòi
				],
				'fix' => [
					'hy hy'		=> ['hi hi', 'Hy hy', 'Hi hi', 'HY HY', 'HI HI', false],
					'hý hý'		=> ['hí hí', 'Hý hý', 'Hí hí', 'HÝ HÝ', 'HÍ HÍ', false],
					'hý húi'	=> ['hí húi', 'Hý húi', 'Hí húi', 'HÝ HÚI', 'HÍ HÚI', false],
					'hý ha'		=> ['hí ha', 'Hý ha', 'Hí ha', 'HÝ HA', 'HÍ HA', false],
					'hý hửng'	=> ['hí hửng', 'Hý hửng', 'Hí hửng', 'HÝ HỬNG', 'HÍ HỬNG', false],
					'hý hởn'	=> ['hí hởn', 'Hý hởn', 'Hí hởn', 'HÝ HỞN', 'HÍ HỞN', false],
					'ti hý'		=> ['ti hí', 'Ti hý', 'Ti hí', 'TI HÝ', 'TI HÍ', false],
					'ty hý'		=> ['ti hí', 'Ty hý', 'Ti hí', 'TY HÝ', 'TI HÍ', false],// Fix combo: +ti
					'hỷ mũi'	=> ['hỉ mũi', 'Hỷ mũi', 'Hỉ mũi', 'HỶ MŨI', 'HỈ MŨI', false],
					'hủ hỷ'		=> ['hủ hỉ', 'Hủ hỷ', 'Hủ hỉ', 'HỦ HỶ', 'HỦ HỈ', false],
					'hỷ hả'		=> ['hỉ hả', 'Hỷ hả', 'Hỉ hả', 'HỶ HẢ', 'HỈ HẢ', false],
					'ký đầu'	=> ['kí đầu', 'Ký đầu', 'Kí đầu', 'KÝ ĐẦU', 'KÍ ĐẦU', false],// Fix extra: kí+
					'ký mỏ'		=> ['kí mỏ', 'Ký mỏ', 'Kí mỏ', 'KÝ MỎ', 'KÍ MỎ', false],// Fix extra: kí+
					'ký lô'		=> ['kí lô', 'Ký lô', 'Kí lô', 'KÝ LÔ', 'KÍ LÔ', false],// Fix extra: kí+
					'mấy ký'	=> ['mấy kí', 'Mấy ký', 'Mấy kí', 'MẤY KÝ', 'MẤY KÍ', false],// Fix extra: kí+
					'nhiêu ký'	=> ['nhiêu kí', 'Nhiêu ký', 'Nhiêu kí', 'NHIÊU KÝ', 'NHIÊU KÍ', false],// Fix extra: kí+
					'kỳ cọ'		=> ['kì cọ', 'Kỳ cọ', 'Kì cọ', 'KỲ CỌ', 'KÌ CỌ', false],
					'kỳ kèo'	=> ['kì kèo', 'Kỳ kèo', 'Kì kèo', 'KỲ KÈO', 'KÌ KÈO', false],
					'kỳ đà'		=> ['kì đà', 'Kỳ đà', 'Kì đà', 'KỲ ĐÀ', 'KÌ ĐÀ', false],
					'kỳ nhông'	=> ['kì nhông', 'Kỳ nhông', 'Kì nhông', 'KỲ NHÔNG', 'KÌ NHÔNG', false],
					'kỳ cùng'	=> ['kì cùng', 'Kỳ cùng', 'Kì cùng', 'KỲ CÙNG', 'KÌ CÙNG', false],
					'cũ kỹ'		=> ['cũ kĩ', 'Cũ kỹ', 'Cũ kĩ', 'CŨ KỸ', 'CŨ KĨ', false],
					'kỹ càng'	=> ['kĩ càng', 'Kỹ càng', 'Kĩ càng', 'KỸ CÀNG', 'KĨ CÀNG', false],// Fix extra: kĩ+
					'kỹ tính'	=> ['kĩ tính', 'Kỹ tính', 'Kĩ tính', 'KỸ TÍNH', 'KĨ TÍNH', false],// Fix extra: kĩ+
					'kỹ lưỡng'	=> ['kĩ lưỡng', 'Kỹ lưỡng', 'Kĩ lưỡng', 'KỸ LƯỠNG', 'KĨ LƯỠNG', false],// Fix extra: kĩ+
					'kỹ chưa'	=> ['kĩ chưa', 'Kỹ chưa', 'Kĩ chưa', 'KỸ CHƯA', 'KĨ CHƯA', false],// Fix extra: kĩ+
					'kỹ vào'	=> ['kĩ vào', 'Kỹ vào', 'Kĩ vào', 'KỸ VÀO', 'KĨ VÀO', false],// Fix extra: kĩ+
					'kỹ vô'		=> ['kĩ vô', 'Kỹ vô', 'Kĩ vô', 'KỸ VÔ', 'KĨ VÔ', false],// Fix extra: kĩ+
					'kỹ quá'	=> ['kĩ quá', 'Kỹ quá', 'Kĩ quá', 'KỸ QUÁ', 'KĨ QUÁ', false],// Fix extra: kĩ+
					'thật kỹ'	=> ['thật kĩ', 'Thật kỹ', 'Thật kĩ', 'THẬT KỸ', 'THẬT KĨ', false],// Fix extra: kĩ+
					'cụ kỵ'		=> ['cụ kị', 'Cụ kỵ', 'Cụ kị', 'CỤ KỴ', 'CỤ KỊ', false],
					'cu ly'		=> ['cu li', 'Cu ly', 'Cu li', 'CU LY', 'CU LI', false],
					'culi'		=> ['cu li', 'Culi', 'Cu li', 'CULI', 'CU LI', false],// Fix extra: cu li+
					'culy'		=> ['cu li', 'Culy', 'Cu li', 'CULY', 'CU LI', false],// Fix extra: cu li+
					'cu-li'		=> ['cu li', 'Cu-li', 'Cu li', 'CU-Li', 'CU LI', false],// Fix extra: cu li+
					'cu-ly'		=> ['cu li', 'Cu-ly', 'Cu li', 'CU-LY', 'CU LI', false],// Fix extra: cu li+
					'va ly'		=> ['va li', 'Va ly', 'Va li', 'VA LY', 'VA LI', false],
					'vali'		=> ['va li', 'Vali', 'Va li', 'VALI', 'VA LI', false],// Fix extra: va li+
					'valy'		=> ['va li', 'Valy', 'Va li', 'VALY', 'VA LI', false],// Fix extra: va li+
					'va-li'		=> ['va li', 'Va-li', 'Va li', 'VA-LI', 'VA LI', false],// Fix extra: va li+
					'va-ly'		=> ['va li', 'Va-ly', 'Va li', 'VA-LY', 'VA LI', false],// Fix extra: va li+
					'ly ti'		=> ['li ti', 'Ly ti', 'Li ti', 'LY TI', 'LI TI', false],
					'ly ty'		=> ['li ti', 'Ly ty', 'Li ti', 'LY TY', 'LI TI', false],// Fix combo: +ti
					'chi ly'	=> ['chi li', 'Chi ly', 'Chi li', 'CHI LY', 'CHI LI', false],
					'ly bì'		=> ['li bì', 'Ly bì', 'Li bì', 'LY BÌ', 'LI BÌ', false],
					'lâm ly'	=> ['lâm li', 'Lâm ly', 'Lâm li', 'LÂM LY', 'LÂM LI', false],
					'lý nhí'	=> ['lí nhí', 'Lý nhí', 'Lí nhí', 'LÝ NHÍ', 'LÍ NHÍ', false],
					'lý lắc'	=> ['lí lắc', 'Lý lắc', 'Lí lắc', 'LÝ LẮC', 'LÍ LẮC', false],
					'kiết lỵ'	=> ['kiết lị', 'Kiết lỵ', 'Kiết lị', 'KIẾT LỴ', 'KIẾT LỊ', false],
					'mụ mỵ'		=> ['mụ mị', 'Mụ mỵ', 'Mụ mị', 'MỤ MỴ', 'MỤ MỊ', false],
					'cây sy'	=> ['cây si', 'Cây sy', 'Cây si', 'CÂY SY', 'CÂY SI', false],
					'nốt sy'	=> ['nốt si', 'Nốt sy', 'Nốt si', 'NỐT SY', 'NỐT SI', false],
					'mua sỷ'	=> ['mua sỉ', 'Mua sỷ', 'Mua sỉ', 'MUA SỶ', 'MUA SỈ', false],// Fix extra: sỉ+
					'bán sỷ'	=> ['bán sỉ', 'Bán sỷ', 'Bán sỉ', 'BÁN SỶ', 'BÁN SỈ', false],// Fix extra: sỉ+
					'sỷ lẻ'		=> ['sỉ lẻ', 'Sỷ lẻ', 'Sỉ lẻ', 'SỶ LẺ', 'SỈ LẺ', false],// Fix extra: sỉ+
					'sỷ số'		=> ['sỉ số', 'Sỷ số', 'Sỉ số', 'SỶ SỐ', 'SỈ SỐ', false],// Fix extra: sỉ+
					'buôn sỷ'	=> ['buôn sỉ', 'Buôn sỷ', 'Buôn sỉ', 'BUÔN SỶ', 'BUÔN SỈ', false],// Fix extra: sỉ+
					'giá sỷ'	=> ['giá sỉ', 'Giá sỷ', 'Giá sỉ', 'GIÁ SỶ', 'GIÁ SỈ', false],// Fix extra: sỉ+
					'lấy sỷ'	=> ['lấy sỉ', 'Lấy sỷ', 'Lấy sỉ', 'LẤY SỶ', 'LẤY SỈ', false],// Fix extra: sỉ+
					'hàng sỷ'	=> ['hàng sỉ', 'Hàng sỷ', 'Hàng sỉ', 'HÀNG SỶ', 'HÀNG SỈ', false],// Fix extra: sỉ+
					'sờ ty'		=> ['sờ ti', 'Sờ ty', 'Sờ ti', 'SỜ TY', 'SỜ TI', false],// Fix extra: tỉ+
					'ty mẹ'		=> ['ti mẹ', 'Ty mẹ', 'Ti mẹ', 'TY MẸ', 'TI MẸ', false],// Fix extra: tỉ+
					'ty vợ'		=> ['ti vợ', 'Ty vợ', 'Ti vợ', 'TY VỢ', 'TI VỢ', false],// Fix extra: tỉ+
					'đầu ty'	=> ['đầu ti', 'Đầu ty', 'Đầu ti', 'ĐẦU TY', 'ĐẦU TI', false],// Fix extra: tỉ+
					'ty toe'	=> ['ti toe', 'Ty toe', 'Ti toe', 'TY TOE', 'TI TOE', false],
					'đinh ty'	=> ['đinh ti', 'Đinh ty', 'Đinh ti', 'ĐINH TY', 'ĐINH TI', false],
					'ty trôn'	=> ['ti trôn', 'Ty trôn', 'Ti trôn', 'TY TRÔN', 'TI TRÔN', false],
					'tí ty'		=> ['tí ti', 'Tí ty', 'Tí ti', 'TÍ TY', 'TÍ TI', false],
					'ty tỉ'		=> ['ti tỉ', 'Ty tỉ', 'Ti tỉ', 'TY TỈ', 'TI TỈ', false],
					'ty tỷ'		=> ['ti tỉ', 'Ty tỷ', 'Ti tỉ', 'TY TỶ', 'TI TỈ', false],// Fix combo: +tỉ
					'ty tiện'	=> ['ti tiện', 'Ty tiện', 'Ti tiện', 'TY TIỆN', 'TI TIỆN', false],
					'tỳ đè'		=> ['tì đè', 'Tỳ đè', 'Tì đè', 'TỲ ĐÈ', 'TÌ ĐÈ', false],// Fix extra: tì+
					'tỳ lên'	=> ['tì lên', 'Tỳ lên', 'Tì lên', 'TỲ LÊN', 'TÌ LÊN', false],// Fix extra: tì+
					'tỳ vào'	=> ['tì vào', 'Tỳ vào', 'Tì vào', 'TỲ VÀO', 'TÌ VÀO', false],// Fix extra: tì+
					'tỳ hằn'	=> ['tì hằn', 'Tỳ hằn', 'Tì hằn', 'TỲ HẰN', 'TÌ HẰN', false],// Fix extra: tì+
					'tỳ vết'	=> ['tì vết', 'Tỳ vết', 'Tì vết', 'TỲ VẾT', 'TÌ VẾT', false],// Fix extra: tì+
					'tù tỳ'		=> ['tù tì', 'Tù tỳ', 'Tù tì', 'TÙ TỲ', 'TÙ TÌ', false],
					'tỳ tỳ'		=> ['tì tì', 'Tỳ tỳ', 'Tì tì', 'TỲ TỲ', 'TÌ TÌ', false],
					'tỷ mỉ'		=> ['tỉ mỉ', 'Tỷ mỉ', 'Tỉ mỉ', 'TỶ MỈ', 'TỈ MỈ', false],
					'tỷ tê'		=> ['tỉ tê', 'Tỷ tê', 'Tỉ tê', 'TỶ TÊ', 'TỈ TÊ', false],
					'tỷ phú'	=> ['tỉ phú', 'Tỷ phú', 'Tỉ phú', 'TỶ PHÚ', 'TỈ PHÚ', false],// Fix extra: tỉ+
					'tiền tỷ'	=> ['tiền tỉ', 'Tiền tỷ', 'Tiền tỉ', 'TIỀN TỶ', 'TIỀN TỈ', false],// Fix extra: tỉ+
					'bạc tỷ'	=> ['bạc tỉ', 'Bạc tỷ', 'Bạc tỉ', 'BẠC TỶ', 'BẠC TỈ', false],// Fix extra: tỉ+
					'tỷ đồng'	=> ['tỉ đồng', 'Tỷ đồng', 'Tỉ đồng', 'TỶ ĐỒNG', 'TỈ ĐỒNG', false],// Fix extra: tỉ+
					'tỷ đô'		=> ['tỉ đô', 'Tỷ đô', 'Tỉ đô', 'TỶ ĐÔ', 'TỈ ĐÔ', false],// Fix extra: tỉ+
					'tỷ thứ'	=> ['tỉ thứ', 'Tỷ thứ', 'Tỉ thứ', 'TỶ THỨ', 'TỈ THỨ', false],// Fix extra: tỉ+
					'tuổi Tị'	=> ['tuổi Tỵ', 'Tuổi Tí', 'Tuổi Tý', 'TUỔI TỊ', 'TUỔI TỴ', false],// Fix extra: Tỵ++
					'Đinh Tị'	=> ['Đinh Tỵ', null, null, 'ĐINH TỊ', 'ĐINH TỴ', false],// Fix extra: Tỵ++
					'Kỷ Tị'		=> ['Kỷ Tỵ', null, null, 'KỶ TỊ', 'KỶ TỴ', false],// Fix extra: Tỵ++
					'Tân Tị'	=> ['Tân Tỵ', null, null, 'TÂN TỊ', 'TÂN TỴ', false],// Fix extra: Tỵ++
					'Quý Tị'	=> ['Quý Tỵ', null, null, 'QUÝ TỊ', 'QUÝ TỴ', false],// Fix extra: Tỵ++
					'Ất Tị'		=> ['Ất Tỵ', null, null, 'ẤT TỊ', 'ẤT TỴ', false]// Fix extra: Tỵ+
				]
			]
		],

		/**
		 * Alternate Unicode index for sorting Vietnamese letters with accents.
		 *
		 * Sorting order:
		 *	(1) Number first, letter last.
		 *	(2) lower first, UPPER last.
		 *	(3) Accent order: a à ả ã á ạ.
		 */
		'sort_index' => [
			'a' => 'aa',
			'à' => 'ab',
			'ả' => 'ac',
			'ã' => 'ad',
			'á' => 'ae',
			'ạ' => 'af',
			'ă' => 'ag',
			'ằ' => 'ah',
			'ẳ' => 'ai',
			'ẵ' => 'aj',
			'ắ' => 'ak',
			'ặ' => 'al',
			'â' => 'am',
			'ầ' => 'an',
			'ẩ' => 'ao',
			'ẫ' => 'ap',
			'ấ' => 'aq',
			'ậ' => 'ar',
			'd' => 'da',
			'đ' => 'db',
			'e' => 'ea',
			'è' => 'eb',
			'ẻ' => 'ec',
			'ẽ' => 'ed',
			'é' => 'ee',
			'ẹ' => 'ef',
			'ê' => 'eg',
			'ề' => 'eh',
			'ể' => 'ei',
			'ễ' => 'ej',
			'ế' => 'ek',
			'ệ' => 'el',
			'i' => 'ia',
			'ì' => 'ib',
			'ỉ' => 'ic',
			'ĩ' => 'id',
			'í' => 'ie',
			'ị' => 'if',
			'o' => 'oa',
			'ò' => 'ob',
			'ỏ' => 'oc',
			'õ' => 'od',
			'ó' => 'oe',
			'ọ' => 'of',
			'ô' => 'og',
			'ồ' => 'oh',
			'ổ' => 'oi',
			'ỗ' => 'oj',
			'ố' => 'ok',
			'ộ' => 'ol',
			'ơ' => 'om',
			'ờ' => 'on',
			'ở' => 'oo',
			'ỡ' => 'op',
			'ớ' => 'oq',
			'ợ' => 'or',
			'u' => 'ua',
			'ù' => 'ub',
			'ủ' => 'uc',
			'ũ' => 'ud',
			'ú' => 'ue',
			'ụ' => 'uf',
			'ư' => 'ug',
			'ừ' => 'uh',
			'ử' => 'ui',
			'ữ' => 'uj',
			'ứ' => 'uk',
			'ự' => 'ul',
			'y' => 'ya',
			'ỳ' => 'yb',
			'ỷ' => 'yc',
			'ỹ' => 'yd',
			'ý' => 'ye',
			'ỵ' => 'yf',
			'A' => 'AA',
			'À' => 'AB',
			'Ả' => 'AC',
			'Ã' => 'AD',
			'Á' => 'AE',
			'Ạ' => 'AF',
			'Ă' => 'AG',
			'Ằ' => 'AH',
			'Ẳ' => 'AI',
			'Ẵ' => 'AJ',
			'Ắ' => 'AK',
			'Ặ' => 'AL',
			'Â' => 'AM',
			'Ầ' => 'AN',
			'Ẩ' => 'AO',
			'Ẫ' => 'AP',
			'Ấ' => 'AQ',
			'Ậ' => 'AR',
			'D' => 'DA',
			'Đ' => 'DB',
			'E' => 'EA',
			'È' => 'EB',
			'Ẻ' => 'EC',
			'Ẽ' => 'ED',
			'É' => 'EE',
			'Ẹ' => 'EF',
			'Ê' => 'EG',
			'Ề' => 'EH',
			'Ể' => 'EI',
			'Ễ' => 'EJ',
			'Ế' => 'EK',
			'Ệ' => 'EL',
			'I' => 'IA',
			'Ì' => 'IB',
			'Ỉ' => 'IC',
			'Ĩ' => 'ID',
			'Í' => 'IE',
			'Ị' => 'IF',
			'O' => 'OA',
			'Ò' => 'OB',
			'Ỏ' => 'OC',
			'Õ' => 'OD',
			'Ó' => 'OE',
			'Ọ' => 'OF',
			'Ô' => 'OG',
			'Ồ' => 'OH',
			'Ổ' => 'OI',
			'Ỗ' => 'OJ',
			'Ố' => 'OK',
			'Ộ' => 'OL',
			'Ơ' => 'OM',
			'Ờ' => 'ON',
			'Ở' => 'OO',
			'Ỡ' => 'OP',
			'Ớ' => 'OQ',
			'Ợ' => 'OR',
			'U' => 'UA',
			'Ù' => 'UB',
			'Ủ' => 'UC',
			'Ũ' => 'UD',
			'Ú' => 'UE',
			'Ụ' => 'UF',
			'Ư' => 'UG',
			'Ừ' => 'UH',
			'Ử' => 'UI',
			'Ữ' => 'UJ',
			'Ứ' => 'UK',
			'Ự' => 'UL',
			'Y' => 'YA',
			'Ỳ' => 'YB',
			'Ỷ' => 'YC',
			'Ỹ' => 'YD',
			'Ý' => 'YE',
			'Ỵ' => 'YF'
		],

		/**
		 * Vietnamese number format strings.
		 */
		'number_format'	=> [
			'0'			=> 'không',
			'1'			=> 'một',
			'2'			=> 'hai',
			'3'			=> 'ba',
			'4'			=> 'bốn',
			'5'			=> 'năm',
			'6'			=> 'sáu',
			'7'			=> 'bảy',
			'8'			=> 'tám',
			'9'			=> 'chín',
			'10'		=> 'mười',
			'11'		=> 'mười một',
			'12'		=> 'mười hai',
			'13'		=> 'mười ba',
			'14'		=> 'mười bốn',
			'15'		=> 'mười lăm',
			'16'		=> 'mười sáu',
			'17'		=> 'mười bảy',
			'18'		=> 'mười tám',
			'19'		=> 'mười chín',
			'20'		=> 'hai mươi',
			'21'		=> 'hai mươi mốt',
			'22'		=> 'hai mươi hai',
			'23'		=> 'hai mươi ba',
			'24'		=> 'hai mươi bốn',
			'25'		=> 'hai mươi lăm',
			'26'		=> 'hai mươi sáu',
			'27'		=> 'hai mươi bảy',
			'28'		=> 'hai mươi tám',
			'29'		=> 'hai mươi chín',
			'30'		=> 'ba mươi',
			'31'		=> 'ba mươi mốt',
			'32'		=> 'ba mươi hai',
			'33'		=> 'ba mươi ba',
			'34'		=> 'ba mươi bốn',
			'35'		=> 'ba mươi lăm',
			'36'		=> 'ba mươi sáu',
			'37'		=> 'ba mươi bảy',
			'38'		=> 'ba mươi tám',
			'39'		=> 'ba mươi chín',
			'40'		=> 'bốn mươi',
			'41'		=> 'bốn mươi mốt',
			'42'		=> 'bốn mươi hai',
			'43'		=> 'bốn mươi ba',
			'44'		=> 'bốn mươi tư',
			'45'		=> 'bốn mươi lăm',
			'46'		=> 'bốn mươi sáu',
			'47'		=> 'bốn mươi bảy',
			'48'		=> 'bốn mươi tám',
			'49'		=> 'bốn mươi chín',
			'50'		=> 'năm mươi',
			'51'		=> 'năm mươi mốt',
			'52'		=> 'năm mươi hai',
			'53'		=> 'năm mươi ba',
			'54'		=> 'năm mươi bốn',
			'55'		=> 'năm mươi lăm',
			'56'		=> 'năm mươi sáu',
			'57'		=> 'năm mươi bảy',
			'58'		=> 'năm mươi tám',
			'59'		=> 'năm mươi chín',
			'60'		=> 'sáu mươi',
			'61'		=> 'sáu mươi mốt',
			'62'		=> 'sáu mươi hai',
			'63'		=> 'sáu mươi ba',
			'64'		=> 'sáu mươi bốn',
			'65'		=> 'sáu mươi lăm',
			'66'		=> 'sáu mươi sáu',
			'67'		=> 'sáu mươi bảy',
			'68'		=> 'sáu mươi tám',
			'69'		=> 'sáu mươi chín',
			'70'		=> 'bảy mươi',
			'71'		=> 'bảy mươi mốt',
			'72'		=> 'bảy mươi hai',
			'73'		=> 'bảy mươi ba',
			'74'		=> 'bảy mươi bốn',
			'75'		=> 'bảy mươi lăm',
			'76'		=> 'bảy mươi sáu',
			'77'		=> 'bảy mươi bảy',
			'78'		=> 'bảy mươi tám',
			'79'		=> 'bảy mươi chín',
			'80'		=> 'tám mươi',
			'81'		=> 'tám mươi mốt',
			'82'		=> 'tám mươi hai',
			'83'		=> 'tám mươi ba',
			'84'		=> 'tám mươi bốn',
			'85'		=> 'tám mươi lăm',
			'86'		=> 'tám mươi sáu',
			'87'		=> 'tám mươi bảy',
			'88'		=> 'tám mươi tám',
			'89'		=> 'tám mươi chín',
			'90'		=> 'chín mươi',
			'91'		=> 'chín mươi mốt',
			'92'		=> 'chín mươi hai',
			'93'		=> 'chín mươi ba',
			'94'		=> 'chín mươi bốn',
			'95'		=> 'chín mươi lăm',
			'96'		=> 'chín mươi sáu',
			'97'		=> 'chín mươi bảy',
			'98'		=> 'chín mươi tám',
			'99'		=> 'chín mươi chín',
			'H'			=> '%s trăm',
			'H_AND'		=> '%1$s trăm lẻ %2$s',
			'H_MORE'	=> '%1$s trăm %2$s',
			'K'			=> '%s nghìn',
			'M'			=> '%s triệu',
			'B'			=> '%s tỉ'
		]
	];

	/**
	 * Upper the first character of each single word, lower remain characters.
	 * Used for Vietnamese people names, administrative unit names...
	 */
	public static function formatName(string $text = ''): string
	{
		if (!empty($text))
		{
			// Reduce multiple spaces to a single one
			$text = trim(preg_replace('# {2,}#', ' ', $text));

			// Lower all characters
			$text = mb_strtolower($text);

			// Upper the first character of each single word
			$words = explode(' ', $text);
			$text = '';

			foreach ($words as $word)
			{
				preg_match('/^(.)(.*)$/us', $word, $matches);
				$text .= (empty($text) ? '' : ' ') . mb_strtoupper($matches[1]) . $matches[2];
			}
		}

		return $text;
	}

	/**
	 * Remove all accents or convert them to something.
	 * Used for SEO, out-dated browsers...
	 *
	 * @param string $mode Mode:
	 *		'remove': Remove all accents and convert special letters into English letters.
	 *		'alphabet': Remove only accents, keep Vietnamese letters in the alphabet.
	 *		'ncr_decimal': Convert accents into NCR Decimal.
	 */
	public static function removeAccent(string $text = '', string $mode = 'remove'): string
	{
		if (!empty($text))
		{
			switch ($mode)
			{
				default:
				case 'remove';
					$i_lower = 1;
					$i_upper = 2;
				break;

				case 'alphabet';
					$i_lower = 3;
					$i_upper = 4;
				break;

				case 'ncr_decimal';
					$i_lower = 5;
					$i_upper = 6;
				break;
			}

			foreach (static::$data['accent_letters'] as $key => $data)
			{
				$text = str_replace([$key, $data[0]], [$data[$i_lower], $data[$i_upper]], $text);
			}
		}

		return $text;
	}

	/**
	 * Correct wrong accent placements.
	 *
	 * We have 2 types of problems:
	 *	(1) Differences between the new method and the classic one.
	 *	(2) Wrong placement of accents, they are really errors.
	 */
	public static function fixAccent(string $text = ''): string
	{
		if (!empty($text))
		{
			foreach (static::$data['accent_placements'] as $key => $data)
			{
				$text = str_replace([$key, $data[1], $data[3]], [$data[0], $data[2], $data[4]], $text);
			}
		}

		return $text;
	}

	/**
	 * Correct wrong cases between using of I and Y.
	 *
	 *	Cases:
	 *		ZERO-CASE: NOT I, NOT Y (Skipped)
	 *		LEFT-CASE: ONLY I, NOT Y
	 *		RIGHT-CASE: NOT I, ONLY Y
	 *		MAJOR-I-CASE: MAJOR I, MINORITY Y
	 *			(1) Replace all *Y to *I.
	 *			(2) Fix specified "Y" cases.
	 *		MAJOR-Y-CASE: MINORITY I, MAJOR Y
	 *			(1) Replace all *I to *Y.
	 *			(2) Fix specified "I" cases.
	 */
	public static function fixIY(string $text = ''): string
	{
		if (!empty($text))
		{
			$i_y_ary = ['i', 'y'];
			$i_y_groups = ['only', 'major', 'fix'];// Must be in order

			foreach ($i_y_ary as $i_y)
			{
				foreach ($i_y_groups as $i_y_group)
				{
					foreach (static::$data['i_or_y'][$i_y][$i_y_group] as $key => $data)
					{
						// This string can be within another word
						if ($data[5])
						{
							$text = preg_replace("/(\A|\W)$key(\W|\Z)/u", "\\1$data[0]\\2", $text);
							$text = preg_replace("/(\A|\W)$data[1](\W|\Z)/u", "\\1$data[2]\\2", $text);
							$text = preg_replace("/(\A|\W)$data[3](\W|\Z)/u", "\\1$data[4]\\2", $text);
						}
						// Safe to replace all cases
						else
						{
							$text = str_replace([$key, $data[1], $data[3]], [$data[0], $data[2], $data[4]], $text);
						}
					}
				}
			}
		}

		return $text;
	}

	/**
	 * Sorting Vietnamese words.
	 *
	 * This method has 2 modes:
	 *	(1) Sorting by values in a simple array:
	 *		Vietnamese::sortWord(['a', 'b', 'c']);
	 *	(2) Sorting by one or more keys in a two-dimensional array:
	 *		Vietnamese::sortWord($array, ['name', 'date'])
	 *		-> Sorting by the name first, the date last.
	 */
	public static function sortWord(array $data = [], array $keys = []): array
	{
		usort($data, function($a, $b) use ($keys)
		{
			if (!is_array($a))
			{
				$a = str_replace(array_keys(static::$data['sort_index']), array_values(static::$data['sort_index']), $a);
				$b = str_replace(array_keys(static::$data['sort_index']), array_values(static::$data['sort_index']), $b);

				return $a <=> $b;
			}

			$result = 0;

			if ($keys)
			{
				foreach ($keys as $key)
				{
					$a[$key] = str_replace(array_keys(static::$data['sort_index']), array_values(static::$data['sort_index']), $a[$key]);
					$b[$key] = str_replace(array_keys(static::$data['sort_index']), array_values(static::$data['sort_index']), $b[$key]);
					$result = $a[$key] <=> $b[$key];

					if ($result !== 0)
					{
						break;
					}
				}
			}

			return $result;
		});

		return $data;
	}

	/**
	 * Sorting Vietnamese people names.
	 *
	 * If the first name and the last name in 2 different keys, use the `Vietnamese::sortWord()` instead.
	 *	Vietnamese::sortWord($array, ['first_name', 'last_name'])
	 * Use this method if both of fields are within a combined string = [last_name] + [first_name].
	 *
	 * Sorting order:
	 *	(1) First name.
	 *	(2) Last name.
	 *	(3) Other keys in order.
	 *
	 * This method has 2 modes:
	 *	(1) Sorting by values in a simple array:
	 *		Vietnamese::sortPeopleName(['Nguyễn Văn A', 'Nguyễn Văn B', 'Nguyễn Văn C']);
	 *	(2) Sorting by one or more keys in a two-dimensional array, with the first key is the people name:
	 *		Vietnamese::sortPeopleName($array, ['name', 'birth_date'])
	 *		-> Sorting by the name first, the birthdate last.
	 */
	public static function sortPeopleName(array $data = [], array $keys = []): array
	{
		$new_names = [];
		$first_name_key = uniqid() . '_first_name';
		$last_name_key = uniqid() . '_last_name';

		if ($data)
		{
			if (is_array(current($data)) && $keys)
			{
				$name_key = array_shift($keys);

				foreach ($data as &$row)
				{
					$name = static::formatName($row[$name_key]);
					$row[$name_key] = $name;
					$name_ary = explode(' ', $name);
					$row[$first_name_key] = array_pop($name_ary);
					$row[$last_name_key] = implode(' ', $name_ary);
				}

				if ($keys)
				{
					$new_names = static::sortWord($data, array_merge([$first_name_key, $last_name_key], $keys));
				}
				else
				{
					$new_names = static::sortWord($data, [$first_name_key, $last_name_key]);
				}

				// Remove temporary keys
				foreach ($new_names as &$new_name)
				{
					unset($new_name[$first_name_key]);
					unset($new_name[$last_name_key]);
				}
			}
			else
			{
				$tmp = [];

				foreach ($data as $name)
				{
					$name = static::formatName($name);
					$name_ary = explode(' ', $name);
					$first_name = array_pop($name_ary);
					$last_name = implode(' ', $name_ary);
					$tmp[] = [
						'first_name' => $first_name,
						'last_name' => $last_name
					];
				}

				$tmp = static::sortWord($tmp, ['first_name', 'last_name']);

				foreach ($tmp as $row)
				{
					$new_names[] = trim($row['last_name']  . ' ' . $row['first_name']);
				}
			}
		}

		return $new_names;
	}

	/**
	 * Check a character is one of Vietnamese characters or not?
	 * A Vietnamese character is an alphabet or a character with accents.
	 */
	public static function checkChar(string $char = ''): bool
	{
		$result = false;

		if (mb_strlen($char) == 1)
		{
			$check = implode(' ', array_merge(array_keys(static::$data['letters']), array_keys(static::$data['accent_letters'])));

			if (str_contains($check, mb_strtolower($char)))
			{
				$result = true;
			}
		}

		return $result;
	}

	/**
	 * Detect incorrect words in Vietnamese.
	 *
	 * @param bool $get_incorrect_words true: Return incorrect words.
	 *									false: Return correct words.
	 */
	public static function scanWords(string $text = '', bool $get_incorrect_words = true): array
	{
		$found_words = [];

		// Add to letters with accents
		$extra_match = '';
		foreach (static::$data['accent_letters'] as $key => $data)
		{
			$extra_match .= $key . $data[0];
		}

		// Get only letters with accents and one whitespace
		$text = preg_replace('/[^A-Za-z' . $extra_match . ' ]/u', '', $text);
		$text = preg_replace('/\s+/u', ' ', $text);

		if (!empty($text))
		{
			$correct_words = implode(' ', static::$data['words']);
			$words = array_unique(explode(' ', $text));

			foreach ($words as $word)
			{
				if (($get_incorrect_words && !str_contains($correct_words, mb_strtolower($word))) || (!$get_incorrect_words && str_contains($correct_words, mb_strtolower($word))))
				{
					$found_words[] = $word;
				}
			}
		}

		return $found_words;
	}

	/**
	 * Print out the way to spell Vietnamese words.
	 */
	public static function speak(string $text = ''): string
	{
		$read_text = '';

		if (!empty($text))
		{
			// Detect the accent
			$accent_letters = [
				1 => '',
				2 => '',
				3 => '',
				4 => '',
				5 => ''
			];

			foreach (static::$data['accent_to_vowels'] as $data)
			{
				for ($i = 1; $i < 6; $i++)
				{
					$accent_letters[$i] .= (empty($accent_letters[$i]) ? '' : ' ') . $data[$i][0];
				}
			}

			// Reduce multiple spaces to a single one
			$text = trim(preg_replace('# {2,}#', ' ', $text));

			// Lower all characters
			$text = mb_strtolower($text);

			if (isset(static::$data['consonants'][$text]) || isset(static::$data['letters'][$text]))
			{
				$read_text .= '/' . (isset(static::$data['consonants'][$text]) ? static::$data['consonants'][$text][1] : static::$data['letters'][$text][1]) . '/';
			}
			else
			{
				// Get elements of each single word
				$words = explode(' ', $text);

				foreach ($words as $word)
				{
					$consonant = $syllable_consonant = $accent = '';

					// Use the list which was sorted by length DESC to detect "ngh/ng/nh/n", "kh/h"... correctly
					if (preg_match('/\A(' . implode('|', static::$data['consonants_desc']) . ')(.+)/i', $word, $matches))
					{
						$consonant = $matches[1];
						$syllable = $matches[2];
					}
					else
					{
						$syllable = $word;
					}

					// Also check consonants within syllables
					if (preg_match('/(.+)(' . implode('|', static::$data['end_consonants_desc']) . ')\Z/i', $syllable, $matches))
					{
						$syllable_vowel = $matches[1];
						$syllable_consonant = $matches[2];
					}
					else
					{
						$syllable_vowel = $syllable;
					}

					// Accent is spelt lastly
					$syllable_vowel_no_accent = static::removeAccent($syllable_vowel, 'alphabet');

					if (!empty($syllable_vowel))
					{
						// Split at all positions not after the start: ^
						// and not before the end: $
						$letters = preg_split('/(?<!^)(?!$)/u', $syllable_vowel_no_accent);

						foreach ($letters as $letter)
						{
							$read_text .= static::$data['letters'][$letter][1] . ' ';
						}

						if (!empty($syllable_consonant))
						{
							$read_text .= static::$data['consonants'][$syllable_consonant][1] . ' ';
							$read_text .= $syllable_vowel_no_accent . $syllable_consonant . ', ';
						}
					}

					if (!empty($consonant))
					{
						$read_text .= static::$data['consonants'][$consonant][1] . ' ';
					}

					// Now let's spell the accent
					$vowel_has_accent = preg_replace('/[a-zăâđêôơư]/u', '', $word);

					if (!empty($vowel_has_accent))
					{
						foreach ($accent_letters as $accent_code => $data)
						{
							if (str_contains($data, $vowel_has_accent))
							{
								$accent = static::$data['accent_names'][$accent_code];
							}
						}
					}

					if (!empty($accent))
					{
						// Do not repeat if there is vowel only
						if (mb_strlen($word) > 1)
						{
							$read_text .= $syllable_vowel_no_accent . $syllable_consonant . ' ' . static::removeAccent($word, 'alphabet') . " $accent /$word/; ";
						}
						else
						{
							$read_text .= " $accent /$word/; ";
						}
					}
					else
					{
						// Do not repeat if there is vowel only
						if (mb_strlen($word) > 1)
						{
							$read_text .= $syllable_vowel_no_accent . $syllable_consonant . " /$word/; ";
						}
						else
						{
							$read_text .= " /$word/; ";
						}
					}
				}

				// Final rhythm
				$read_text .= "/$text/";
			}
		}

		return $read_text;
	}

	/**
	 * Convert number/amount into Vietnamese text.
	 */
	public static function numberToText(float $amount): string
	{
		$text = '';

		$steps = [
			static::$data['number_format']['B'],
			static::$data['number_format']['M'],
			static::$data['number_format']['K'],
			''
		];

		if (abs($amount) < 1000000000000)
		{
			$split = explode(',', number_format($amount));
			$n = count($split);

			if ($n > 1)
			{
				if ($n == 3)
				{
					unset($steps[0]);
				}
				elseif ($n == 2)
				{
					unset($steps[0]);
					unset($steps[1]);
				}

				$steps = array_values($steps);

				for ($i = 0; $i < $n; $i++)
				{
					if (static::convertSteps($split[$i]) != static::$data['number_format']['0'])
					{
						$text .= ($steps[$i] == '') ? static::convertSteps($split[$i]) : sprintf($steps[$i], static::convertSteps($split[$i]));
						$text .= ' ';
					}
				}
			}
			else
			{
				$text .= static::convertSteps($split[0]);
			}
		}

		return trim($text);
	}

	/**
	 * Convert amount into text for each thousand steps.
	 */
	private static function convertSteps(int $number): string
	{
		$text = '';

		if ($number >= 0 && $number <= 999)
		{
			$numbers = str_split($number);
			$a = intval(isset($numbers[2]) ? $numbers[0] : 0);
			$b = intval(isset($numbers[2]) ? $numbers[1] : (isset($numbers[1]) ? $numbers[0] : 0));
			$c = intval($numbers[2] ?? ($numbers[1] ?? $numbers[0]));

			if ($a > 0)
			{
				if ($b > 0)
				{
					$text .= sprintf(static::$data['number_format']['H_MORE'], static::$data['number_format'][$a], static::$data['number_format'][$b . $c]);
				}
				else
				{
					if ($c > 0)
					{
						$text .= sprintf(static::$data['number_format']['H_AND'], static::$data['number_format'][$a], static::$data['number_format'][$c]);
					}
					else
					{
						$text .= sprintf(static::$data['number_format']['H'], static::$data['number_format'][$a]);
					}
				}
			}
			else
			{
				$text .= static::$data['number_format'][intval($b . $c)];
			}
		}

		return $text;
	}
}
