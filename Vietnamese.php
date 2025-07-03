<?php
/**
 * This file is part of the Vietnamese package.
 *
 * @version 1.0.10
 * @copyright (c) NEDKA. All rights reserved.
 * @license MIT License.
 */

namespace NEDKA\Vietnamese;

/**
 * HOW TO USE?
 *
 * Format people names:
 *		$iVN->formatPeopleName('ViỆt NaM')
 *		Việt Nam
 * Remove all accents:
 *		$iVN->removeAccent('Việt Nam', 'remove')
 *		Viet Nam
 * Convert into NCR Decimal:
 *		$iVN->removeAccent('Việt Nam', 'ncr_decimal')
 *		Vi&#7879;t Nam
 * Correct wrong accent placements:
 *		$iVN->fixAccent('Vịêt Nam')
 *		Việt Nam
 * Correct wrong cases between "i" and "y":
 *		$iVN->fixIY('Thi tuổi Kỉ Tị')
 *		Thi tuổi Kỷ Tỵ
 * Sorting words:
 *		$iVN->sortWord(['Ă', 'A', 'Â', 'À', 'Á'])
 *		['A', 'Á', 'À', 'Ă', 'Â']
 * Sorting people names:
 *		$iVN->sortPeopleName(['Nguyễn Văn Đảnh', 'Nguyễn VĂN Đàn', 'nguYỄn Văn Đàng', 'NGUYỄN Văn Đang', 'nguyễn anh đang'])
 *		['Nguyễn Anh Đang', 'Nguyễn Văn Đang', 'Nguyễn Văn Đàn', 'Nguyễn Văn Đàng', 'Nguyễn Văn Đảnh']
 * Check a character in the Vietnamese alphabet:
 *		$iVN->checkChar('w')
 *		false
 * Place accent into a single word:
 *		$iVN->place_accent('Hoa', 2)
 *		Hỏa
 * Scan and detect incorrect words in Vietnamese:
 * > Find incorrect words:
 *		$iVN->scanWords('Xứ Wales thắng Nga, đứng nhất bảng B')
 *		['Wales']
 * > Otherwise, get correct words:
 *		$iVN->scanWords('Xứ Wales thắng Nga, đứng nhất bảng B', false)
 *		['Xứ', 'thắng', 'Nga', 'đứng', 'nhất', 'bảng', 'B']
 * Print the way to speak:
 *		$iVN->speak('Việt Nam')
 *		i ê tờ iêt, vờ iêt viêt nặng /việt/; a mờ am, nờ am /nam/; /việt nam/
 * Convert number to text:
 *		$iVN->numberToText(1452369)
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
	private array $data;

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->data = [
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
			 *
			 * @todo We will update this list as soon with a Vietnamese dictionary.
			 */
			'words' => [],

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
	}

	/**
	 * Upper the first character of each single word, lower remain characters.
	 * Used for Vietnamese people names, administrative unit names...
	 */
	public function formatPeopleName(string $text = ''): string
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
	public function removeAccent(string $text = '', string $mode = 'remove'): string
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

			foreach ($this->data['accent_letters'] as $key => $data)
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
	public function fixAccent(string $text = ''): string
	{
		if (!empty($text))
		{
			foreach ($this->data['accent_placements'] as $key => $data)
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
	public function fixIY(string $text = ''): string
	{
		if (!empty($text))
		{
			$i_y_ary = ['i', 'y'];
			$i_y_groups = ['only', 'major', 'fix'];// Must be in order

			foreach ($i_y_ary as $i_y)
			{
				foreach ($i_y_groups as $i_y_group)
				{
					foreach ($this->data['i_or_y'][$i_y][$i_y_group] as $key => $data)
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
	 * Binary safe case-insensitive string comparison.
	 * Internal use for $this->sortWord().
	 *
	 * @param string $a	First string.
	 * @param string $b	Second string.
	 * @return int Returns: < 0 if $a < $b; > 0 if $a > $b; 0 if $a == $b.
	 */
	private function cmp(string $a, string $b): int
	{
		$a = str_replace(array_keys($this->data['sort_index']), array_values($this->data['sort_index']), $a);
		$b = str_replace(array_keys($this->data['sort_index']), array_values($this->data['sort_index']), $b);

		return strcasecmp($a, $b);
	}

	/**
	 * Sorting Vietnamese words.
	 *
	 * @param bool $sort_keys Sorting by array keys (true) or array values (false).
	 */
	public function sortWord(array $data = [], bool $sort_keys = false): array
	{
		if ($data)
		{
			if ($sort_keys)
			{
				uksort($data, [$this, 'cmp']);
			}
			else
			{
				usort($data, [$this, 'cmp']);
			}
		}

		return $data;
	}

	/**
	 * Sorting Vietnamese people names.
	 *
	 * Sorting order:
	 *	(1) First name.
	 *	(2) Last name: surname + middle name.
	 */
	public function sortPeopleName(array $data = []): array
	{
		$new_names = [];

		if ($data)
		{
			$names = [];

			foreach ($data as $name)
			{
				$name = $this->formatPeopleName($name);
				$name_ary = explode(' ', $name);
				$firstname = array_pop($name_ary);
				$lastname = implode(' ', $name_ary);
				$names[$firstname][] = $lastname;
			}

			// Sorting
			$names = $this->sortWord($names, true);

			foreach ($names as $firstname => $rows)
			{
				$names[$firstname] = $this->sortWord($rows);
			}

			// Return the name list
			foreach ($names as $firstname => $rows)
			{
				foreach ($rows as $lastname)
				{
					$new_names[] = "$lastname $firstname";
				}
			}
		}

		return $new_names;
	}

	/**
	 * Check a character is one of Vietnamese characters or not?
	 * A Vietnamese character is an alphabet or a character with accents.
	 */
	public function checkChar(string $char = ''): bool
	{
		$result = false;

		if (mb_strlen($char) == 1)
		{
			$check = implode(' ', array_merge(array_keys($this->data['letters']), array_keys($this->data['accent_letters'])));

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
	public function scanWords(string $text = '', bool $get_incorrect_words = true): array
	{
		$found_words = [];

		// Add to letters with accents
		$extra_match = '';
		foreach ($this->data['accent_letters'] as $key => $data)
		{
			$extra_match .= $key . $data[0];
		}

		// Get only letters with accents and one whitespace
		$text = preg_replace('/[^A-Za-z' . $extra_match . ' ]/u', '', $text);
		$text = preg_replace('/\s+/u', ' ', $text);

		if (!empty($text))
		{
			$correct_words = implode(' ', $this->data['words']);
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
	public function speak(string $text = ''): string
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

			foreach ($this->data['accent_to_vowels'] as $data)
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

			if (isset($this->data['consonants'][$text]) || isset($this->data['letters'][$text]))
			{
				$read_text .= '/' . (isset($this->data['consonants'][$text]) ? $this->data['consonants'][$text][1] : $this->data['letters'][$text][1]) . '/';
			}
			else
			{
				// Get elements of each single word
				$words = explode(' ', $text);

				foreach ($words as $word)
				{
					$consonant = $syllable_consonant = $accent = '';

					// Use the list which was sorted by length DESC to detect "ngh/ng/nh/n", "kh/h"... correctly
					if (preg_match('/\A(' . implode('|', $this->data['consonants_desc']) . ')(.+)/i', $word, $matches))
					{
						$consonant = $matches[1];
						$syllable = $matches[2];
					}
					else
					{
						$syllable = $word;
					}

					// Also check consonants within syllables
					if (preg_match('/(.+)(' . implode('|', $this->data['end_consonants_desc']) . ')\Z/i', $syllable, $matches))
					{
						$syllable_vowel = $matches[1];
						$syllable_consonant = $matches[2];
					}
					else
					{
						$syllable_vowel = $syllable;
					}

					// Accent is spelt lastly
					$syllable_vowel_no_accent = $this->removeAccent($syllable_vowel, 'alphabet');

					if (!empty($syllable_vowel))
					{
						// Split at all positions not after the start: ^
						// and not before the end: $
						$letters = preg_split('/(?<!^)(?!$)/u', $syllable_vowel_no_accent);

						foreach ($letters as $letter)
						{
							$read_text .= $this->data['letters'][$letter][1] . ' ';
						}

						if (!empty($syllable_consonant))
						{
							$read_text .= $this->data['consonants'][$syllable_consonant][1] . ' ';
							$read_text .= $syllable_vowel_no_accent . $syllable_consonant . ', ';
						}
					}

					if (!empty($consonant))
					{
						$read_text .= $this->data['consonants'][$consonant][1] . ' ';
					}

					// Now let's spell the accent
					$vowel_has_accent = preg_replace('/[a-zăâđêôơư]/u', '', $word);

					if (!empty($vowel_has_accent))
					{
						foreach ($accent_letters as $accent_code => $data)
						{
							if (str_contains($data, $vowel_has_accent))
							{
								$accent = $this->data['accent_names'][$accent_code];
							}
						}
					}

					if (!empty($accent))
					{
						// Do not repeat if there is vowel only
						if (mb_strlen($word) > 1)
						{
							$read_text .= $syllable_vowel_no_accent . $syllable_consonant . ' ' . $this->removeAccent($word, 'alphabet') . " $accent /$word/; ";
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
	public function numberToText(float $amount): string
	{
		$text = '';

		$steps = [
			$this->data['number_format']['B'],
			$this->data['number_format']['M'],
			$this->data['number_format']['K'],
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
					if ($this->convertSteps($split[$i]) != $this->data['number_format']['0'])
					{
						$text .= ($steps[$i] == '') ? $this->convertSteps($split[$i]) : sprintf($steps[$i], $this->convertSteps($split[$i]));
						$text .= ' ';
					}
				}
			}
			else
			{
				$text .= $this->convertSteps($split[0]);
			}
		}

		return trim($text);
	}

	/**
	 * Convert amount into text for each thousand steps.
	 */
	private function convertSteps(int $number): string
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
					$text .= sprintf($this->data['number_format']['H_MORE'], $this->data['number_format'][$a], $this->data['number_format'][$b . $c]);
				}
				else
				{
					if ($c > 0)
					{
						$text .= sprintf($this->data['number_format']['H_AND'], $this->data['number_format'][$a], $this->data['number_format'][$c]);
					}
					else
					{
						$text .= sprintf($this->data['number_format']['H'], $this->data['number_format'][$a]);
					}
				}
			}
			else
			{
				$text .= $this->data['number_format'][intval($b . $c)];
			}
		}

		return $text;
	}
}
