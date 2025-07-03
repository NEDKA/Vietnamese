<?php
/**
 * This file is part of the Vietnamese package.
 *
 * @version 1.0.10
 * @copyright (c) NEDKA. All rights reserved.
 * @license MIT License.
 */

/**
 * HOW TO USE?
 *
 * Format people names:
 *		$iVN->format_people_name('ViỆt NaM')
 *		Việt Nam
 * Remove all accents:
 *		$iVN->convert_accent('Việt Nam', 'remove')
 *		Viet Nam
 * Convert into NCR Decimal:
 *		$iVN->convert_accent('Việt Nam', 'ncr_decimal')
 *		Vi&#7879;t Nam
 * Correct wrong accent placements:
 *		$iVN->fix_accent('Vịêt Nam')
 *		Việt Nam
 * Correct wrong cases between "i" and "y":
 *		$iVN->fix_i_or_y('Thi tuổi Kỉ Tị')
 *		Thi tuổi Kỷ Tỵ
 * Sorting words:
 *		$iVN->sort_word(['Ă', 'A', 'Â', 'À', 'Á'])
 *		['A', 'Á', 'À', 'Ă', 'Â']
 * Sorting people names:
 *		$iVN->sort_people_name(['Nguyễn Văn Đảnh', 'Nguyễn VĂN Đàn', 'nguYỄn Văn Đàng', 'NGUYỄN Văn Đang', 'nguyễn anh đang'])
 *		['Nguyễn Anh Đang', 'Nguyễn Văn Đang', 'Nguyễn Văn Đàn', 'Nguyễn Văn Đàng', 'Nguyễn Văn Đảnh']
 * Check a character in the Vietnamese alphabet:
 *		$iVN->check_char('w')
 *		false
 * Place accent into a single word:
 *		$iVN->place_accent('Hoa', 2)
 *		Hỏa
 * Scan and detect incorrect words in Vietnamese:
 * > Find incorrect words:
 *		$iVN->scan_words('Xứ Wales thắng Nga, đứng nhất bảng B')
 *		['Wales']
 * > Otherwise, get correct words:
 *		$iVN->scan_words('Xứ Wales thắng Nga, đứng nhất bảng B', false)
 *		['Xứ', 'thắng', 'Nga', 'đứng', 'nhất', 'bảng', 'B']
 * Print the way to speak:
 *		$iVN->speak('Việt Nam')
 *		i ê tờ iêt, vờ iêt viêt nặng /việt/; a mờ am, nờ am /nam/; /việt nam/
 * Convert number to text:
 *		$iVN->number_to_text(1452369)
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
 *	Use the internal function: $this->generate_words() to export the list of them.
 *	>> Total: ? rhythms (Uhm.... counting :-/)
 */

class Vietnamese
{
	/** @var array Vietnamese language data */
	protected array $data;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->data = [
			// List of all letters in the Vietnamese alphabet
			'letters'	=> [
				'a'	=> ['A', 'a'],
				'ă'	=> ['Ă', 'á'],
				'â'	=> ['Â', 'ớ'],
				'b'	=> ['B', 'bờ'],
				'c'	=> ['C', 'cờ'],
				'd'	=> ['D', 'dờ'],
				'đ'	=> ['Đ', 'đờ'],
				'e'	=> ['E', 'e'],
				'ê'	=> ['Ê', 'ê'],
				'g'	=> ['G', 'gờ'],
				'h'	=> ['H', 'hờ'],
				'i'	=> ['I', 'i'],
				'k'	=> ['K', 'k'],
				'l'	=> ['L', 'lờ'],
				'm'	=> ['M', 'mờ'],
				'n'	=> ['N', 'nờ'],
				'o'	=> ['O', 'o'],
				'ô'	=> ['Ô', 'ô'],
				'ơ'	=> ['Ơ', 'ơ'],
				'p'	=> ['P', 'bờ'],
				'q'	=> ['Q', 'quờ'],
				'r'	=> ['R', 'rờ'],
				's'	=> ['S', 'sờ'],
				't'	=> ['T', 'tờ'],
				'u'	=> ['U', 'u'],
				'ư'	=> ['Ư', 'ư'],
				'v'	=> ['V', 'vờ'],
				'x'	=> ['X', 'xờ'],
				'y'	=> ['Y', 'y']
			],
			// List of all accent names
			'accent_names'	=> [
				1	=> 'huyền',
				2	=> 'hỏi',
				3	=> 'ngã',
				4	=> 'sắc',
				5	=> 'nặng'
			],
			/**
			 * List of all Vietnamese letters with accents
			 *
			 * Indexes:
			 *	0 -> Upper case
			 *	1 -> Lower case (English alphabet)
			 *	2 -> Upper case (English alphabet)
			 *	3 -> Lower case (Vietnamese alphabet)
			 *	4 -> Upper case (Vietnamese alphabet)
			 *	5 -> Lower case in NCR Decimal
			 *	6 -> Upper case in NCR Decimal
			 */
			'accent_letters'	=> [
				'à'	=> ['À', 'a', 'A', 'a', 'A', '&#224;', '&#192;'],
				'ả'	=> ['Ả', 'a', 'A', 'a', 'A', '&#7843;', '&#7842;'],
				'ã'	=> ['Ã', 'a', 'A', 'a', 'A', '&#227;', '&#195;'],
				'á'	=> ['Á', 'a', 'A', 'a', 'A', '&#225;', '&#193;'],
				'ạ'	=> ['Ạ', 'a', 'A', 'a', 'A', '&#7841;', '&#7840;'],
				'ă'	=> ['Ă', 'a', 'A', 'ă', 'Ă', '&#259;', '&#258;'],
				'ằ'	=> ['Ằ', 'a', 'A', 'ă', 'Ă', '&#7857;', '&#7856;'],
				'ẳ'	=> ['Ẳ', 'a', 'A', 'ă', 'Ă', '&#7859;', '&#7858;'],
				'ẵ'	=> ['Ẵ', 'a', 'A', 'ă', 'Ă', '&#7861;', '&#7860;'],
				'ắ'	=> ['Ắ', 'a', 'A', 'ă', 'Ă', '&#7855;', '&#7854;'],
				'ặ'	=> ['Ặ', 'a', 'A', 'ă', 'Ă', '&#7863;', '&#7862;'],
				'â'	=> ['Â', 'a', 'A', 'â', 'Â', '&#226;', '&#194;'],
				'ầ'	=> ['Ầ', 'a', 'A', 'â', 'Â', '&#7847;', '&#7846;'],
				'ẩ'	=> ['Ẩ', 'a', 'A', 'â', 'Â', '&#7849;', '&#7848;'],
				'ẫ'	=> ['Ẫ', 'a', 'A', 'â', 'Â', '&#7851;', '&#7850;'],
				'ấ'	=> ['Ấ', 'a', 'A', 'â', 'Â', '&#7845;', '&#7844;'],
				'ậ'	=> ['Ậ', 'a', 'A', 'â', 'Â', '&#7853;', '&#7852;'],
				'đ'	=> ['Đ', 'd', 'D', 'đ', 'Đ', '&#273;', '&#272;'],
				'è'	=> ['È', 'e', 'E', 'e', 'E', '&#232;', '&#200;'],
				'ẻ'	=> ['Ẻ', 'e', 'E', 'e', 'E', '&#7867;', '&#7866;'],
				'ẽ'	=> ['Ẽ', 'e', 'E', 'e', 'E', '&#7869;', '&#7868;'],
				'é'	=> ['É', 'e', 'E', 'e', 'E', '&#233;', '&#201;'],
				'ẹ'	=> ['Ẹ', 'e', 'E', 'e', 'E', '&#7865;', '&#7864;'],
				'ê'	=> ['Ê', 'e', 'E', 'ê', 'Ê', '&#234;', '&#202;'],
				'ề'	=> ['Ề', 'e', 'E', 'ê', 'Ê', '&#7873;', '&#7872;'],
				'ể'	=> ['Ể', 'e', 'E', 'ê', 'Ê', '&#7875;', '&#7874;'],
				'ễ'	=> ['Ễ', 'e', 'E', 'ê', 'Ê', '&#7877;', '&#7876;'],
				'ế'	=> ['Ế', 'e', 'E', 'ê', 'Ê', '&#7871;', '&#7870;'],
				'ệ'	=> ['Ệ', 'e', 'E', 'ê', 'Ê', '&#7879;', '&#7878;'],
				'ì'	=> ['Ì', 'i', 'I', 'i', 'I', '&#236;', '&#204;'],
				'ỉ'	=> ['Ỉ', 'i', 'I', 'i', 'I', '&#7881;', '&#7880;'],
				'ĩ'	=> ['Ĩ', 'i', 'I', 'i', 'I', '&#297;', '&#296;'],
				'í'	=> ['Í', 'i', 'I', 'i', 'I', '&#237;', '&#205;'],
				'ị'	=> ['Ị', 'i', 'I', 'i', 'I', '&#7883;', '&#7882;'],
				'ò'	=> ['Ò', 'o', 'O', 'o', 'O', '&#242;', '&#210;'],
				'ỏ'	=> ['Ỏ', 'o', 'O', 'o', 'O', '&#7887;', '&#7886;'],
				'õ'	=> ['Õ', 'o', 'O', 'o', 'O', '&#245;', '&#213;'],
				'ó'	=> ['Ó', 'o', 'O', 'o', 'O', '&#243;', '&#211;'],
				'ọ'	=> ['Ọ', 'o', 'O', 'o', 'O', '&#7885;', '&#7884;'],
				'ô'	=> ['Ô', 'o', 'O', 'ô', 'Ô', '&#244;', '&#212;'],
				'ồ'	=> ['Ồ', 'o', 'O', 'ô', 'Ô', '&#7891;', '&#7890;'],
				'ổ'	=> ['Ổ', 'o', 'O', 'ô', 'Ô', '&#7893;', '&#7892;'],
				'ỗ'	=> ['Ỗ', 'o', 'O', 'ô', 'Ô', '&#7895;', '&#7894;'],
				'ố'	=> ['Ố', 'o', 'O', 'ô', 'Ô', '&#7889;', '&#7888;'],
				'ộ'	=> ['Ộ', 'o', 'O', 'ô', 'Ô', '&#7897;', '&#7896;'],
				'ơ'	=> ['Ơ', 'o', 'O', 'ơ', 'Ơ', '&#417;', '&#416;'],
				'ờ'	=> ['Ờ', 'o', 'O', 'ơ', 'Ơ', '&#7901;', '&#7900;'],
				'ở'	=> ['Ở', 'o', 'O', 'ơ', 'Ơ', '&#7903;', '&#7902;'],
				'ỡ'	=> ['Ỡ', 'o', 'O', 'ơ', 'Ơ', '&#7905;', '&#7904;'],
				'ớ'	=> ['Ớ', 'o', 'O', 'ơ', 'Ơ', '&#7899;', '&#7898;'],
				'ợ'	=> ['Ợ', 'o', 'O', 'ơ', 'Ơ', '&#7907;', '&#7906;'],
				'ù'	=> ['Ù', 'u', 'U', 'u', 'U', '&#249;', '&#217;'],
				'ủ'	=> ['Ủ', 'u', 'U', 'u', 'U', '&#7911;', '&#7910;'],
				'ũ'	=> ['Ũ', 'u', 'U', 'u', 'U', '&#361;', '&#360;'],
				'ú'	=> ['Ú', 'u', 'U', 'u', 'U', '&#250;', '&#218;'],
				'ụ'	=> ['Ụ', 'u', 'U', 'u', 'U', '&#7909;', '&#7908;'],
				'ư'	=> ['Ư', 'u', 'U', 'ư', 'Ư', '&#432;', '&#431;'],
				'ừ'	=> ['Ừ', 'u', 'U', 'ư', 'Ư', '&#7915;', '&#7914;'],
				'ử'	=> ['Ử', 'u', 'U', 'ư', 'Ư', '&#7917;', '&#7916;'],
				'ữ'	=> ['Ữ', 'u', 'U', 'ư', 'Ư', '&#7919;', '&#7918;'],
				'ứ'	=> ['Ứ', 'u', 'U', 'ư', 'Ư', '&#7913;', '&#7912;'],
				'ự'	=> ['Ự', 'u', 'U', 'ư', 'Ư', '&#7921;', '&#7920;'],
				'ỳ'	=> ['Ỳ', 'y', 'Y', 'y', 'Y', '&#7923;', '&#7922;'],
				'ỷ'	=> ['Ỷ', 'y', 'Y', 'y', 'Y', '&#7927;', '&#7926;'],
				'ỹ'	=> ['Ỹ', 'y', 'Y', 'y', 'Y', '&#7929;', '&#7928;'],
				'ý'	=> ['Ý', 'y', 'Y', 'y', 'Y', '&#253;', '&#221;'],
				'ỵ'	=> ['Ỵ', 'y', 'Y', 'y', 'Y', '&#7925;', '&#7924;']
			],
			// List of all consonants
			'consonants'	=> [
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
			'end_consonants'	=> ['c', 'ch', 'm', 'n', 'ng', 'nh', 'p', 't'],
			// List of all consonants (Sorted by length DESC)
			'consonants_desc'	=> ['ngh', 'ch', 'gh', 'gi', 'kh', 'ng', 'nh', 'ph', 'qu', 'th', 'tr', 'b', 'c', 'd', 'đ', 'g', 'h', 'k', 'l', 'm', 'n', 'p', 'r', 's', 't', 'v', 'x'],
			'end_consonants_desc'	=> ['ch', 'ng', 'nh', 'c', 'm', 'n', 'p', 't'],
			// List of all vowels
			'vowels'	=> ['a', 'ă', 'â', 'e', 'ê', 'i', 'o', 'ô', 'ơ', 'u', 'ư', 'y'],
			'en_vowels'	=> ['a', 'e', 'i', 'o', 'u', 'y'],
			// List of all syllables
			'syllables'	=> [
				// 1-char syllables
				'a', 'ă', 'â', 'e', 'ê', 'i', 'o', 'ô', 'ơ', 'u', 'ư', 'y',

				// 2-char syllables
				'ac', 'ai', 'am', 'an', 'ao', 'ap', 'at', 'au', 'ay',
				'ăc', 'ăm', 'ăn', 'ăp', 'ăt',
				'âc', 'âm', 'ân', 'âp', 'ât', 'âu', 'ây',
				'ec', 'em', 'en', 'eo', 'ep', 'et',
				'êm', 'ên', 'êp', 'êt', 'êu',
				'ia', 'ic', 'im', 'in', 'ip', 'it', 'iu',
				'oa', 'oc', 'oe', 'oi', 'om', 'on', 'op', 'ot',
				'ôc', 'ôi', 'ôm', 'ôn', 'ôp', 'ôt',
				'ơi', 'ơm', 'ơn', 'ơp', 'ơt',
				'ua', 'uc', 'uê', 'ui', 'um', 'un', 'up', 'uơ', 'ut', 'uy',
				'ưa', 'ưc', 'ưi', 'ưm', 'ưt', 'ưu',
				'yt',

				// 3-char syllables
				'ach', 'ang', 'anh',
				'ăng',
				'âng',
				'eng',
				'êch', 'ênh',
				'ich', 'iêc', 'iêm', 'iên', 'iêp', 'iêt', 'iêu', 'inh',
				'oac', 'oai', 'oan', 'oat', 'oay',
				'oăc', 'oăn', 'oăt',
				'oen', 'oeo', 'oet', 'ong',
				'ông',
				'uân', 'uât', 'uây', 'ung', 'uôc', 'uôi', 'uôm', 'uôn', 'uôt',
				'uya', 'uyt', 'uyu',
				'ưng', 'ươc', 'ươi', 'ươm', 'ươn', 'ươp', 'ươt', 'ươu',
				'yên', 'yêu',

				// 4-char syllables
				'iêng',
				'oang', 'oanh', 'oăng', 'oong',
				'uâng', 'uêch', 'uênh', 'uông', 'uyên', 'uyêt', 'uynh',
				'ương'
			],
			// List of vowels with all accents
			'accent_to_vowels'	=> [
				'a'	=> [
					1	=> ['à', 'À'],
					2	=> ['ả', 'Ả'],
					3	=> ['ã', 'Ã'],
					4	=> ['á', 'Á'],
					5	=> ['ạ', 'Ạ']
				],
				'ă'	=> [
					1	=> ['ằ', 'Ằ'],
					2	=> ['ẳ', 'Ẳ'],
					3	=> ['ẵ', 'Ẵ'],
					4	=> ['ắ', 'Ắ'],
					5	=> ['ặ', 'Ặ']
				],
				'â'	=> [
					1	=> ['ầ', 'Ầ'],
					2	=> ['ẩ', 'Ẩ'],
					3	=> ['ẫ', 'Ẫ'],
					4	=> ['ấ', 'Ấ'],
					5	=> ['ậ', 'Ậ']
				],
				'e'	=> [
					1	=> ['è', 'È'],
					2	=> ['ẻ', 'Ẻ'],
					3	=> ['ẽ', 'Ẽ'],
					4	=> ['é', 'É'],
					5	=> ['ẹ', 'Ẹ']
				],
				'ê'	=> [
					1	=> ['ề', 'Ề'],
					2	=> ['ể', 'Ể'],
					3	=> ['ễ', 'Ễ'],
					4	=> ['ế', 'Ế'],
					5	=> ['ệ', 'Ệ']
				],
				'i'	=> [
					1	=> ['ì', 'Ì'],
					2	=> ['ỉ', 'Ỉ'],
					3	=> ['ĩ', 'Ĩ'],
					4	=> ['í', 'Í'],
					5	=> ['ị', 'Ị']
				],
				'o'	=> [
					1	=> ['ò', 'Ò'],
					2	=> ['ỏ', 'Ỏ'],
					3	=> ['õ', 'Õ'],
					4	=> ['ó', 'Ó'],
					5	=> ['ọ', 'Ọ']
				],
				'ô'	=> [
					1	=> ['ồ', 'Ồ'],
					2	=> ['ổ', 'Ổ'],
					3	=> ['ỗ', 'Ỗ'],
					4	=> ['ố', 'Ố'],
					5	=> ['ộ', 'Ộ']
				],
				'ơ'	=> [
					1	=> ['ờ', 'Ờ'],
					2	=> ['ở', 'Ở'],
					3	=> ['ỡ', 'Ỡ'],
					4	=> ['ớ', 'Ớ'],
					5	=> ['ợ', 'Ợ']
				],
				'u'	=> [
					1	=> ['ù', 'Ù'],
					2	=> ['ủ', 'Ủ'],
					3	=> ['ũ', 'Ũ'],
					4	=> ['ú', 'Ú'],
					5	=> ['ụ', 'Ụ']
				],
				'ư'	=> [
					1	=> ['ừ', 'Ừ'],
					2	=> ['ử', 'Ử'],
					3	=> ['ữ', 'Ữ'],
					4	=> ['ứ', 'Ứ'],
					5	=> ['ự', 'Ự']
				],
				'y'	=> [
					1	=> ['ỳ', 'Ỳ'],
					2	=> ['ỷ', 'Ỷ'],
					3	=> ['ỹ', 'Ỹ'],
					4	=> ['ý', 'Ý'],
					5	=> ['ỵ', 'Ỵ']
				]
			],
			// List of syllables which be able assigned to appropriate consonants
			'syllable_to_consonants'	=> [
				// 1-char syllables
				'a'		=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'p', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'ă'		=> [],
				'â'		=> [],
				'e'		=> ['b', 'ch', 'd', 'đ', 'gh', 'gi', 'h', 'k', 'kh', 'l', 'm', 'n', 'ngh', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'ê'		=> ['b', 'ch', 'd', 'đ', 'gh', 'h', 'k', 'kh', 'l', 'm', 'n', 'ngh', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'i'		=> ['b', 'ch', 'd', 'đ', 'g', 'gh', 'h', 'k', 'kh', 'l', 'm', 'n', 'ngh', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'o'		=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'ô'		=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'ơ'		=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'u'		=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'ư'		=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'n', 'ng', 'nh', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'y'		=> ['h', 'k', 'l', 'm', 'ngh', 'qu', 's', 't', 'th', 'v'],

				// 2-char syllables
				'ac'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'ai'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'am'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'n', 'ng', 'nh', 'ph', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'an'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'ao'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'ap'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'at'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'au'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'ay'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'ăc'	=> ['b', 'c', 'ch', 'd', 'đ', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v'],
				'ăm'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'qu', 'r', 's', 't', 'th', 'tr', 'x'],
				'ăn'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v'],
				'ăp'	=> ['b', 'c', 'ch', 'đ', 'g', 'kh', 'l', 'n', 'ph', 'qu', 'r', 's', 't', 'th'],
				'ăt'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'ng', 'nh', 'r', 's', 't', 'th', 'v', 'x'],
				'âc'	=> ['b', 'g', 'gi', 'kh', 'n', 'nh', 't', 'x'],
				'âm'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'r', 's', 't', 'th', 'tr', 'x'],
				'ân'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'l', 'm', 'ng', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'âp'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'r', 's', 't', 'th', 'v', 'x'],
				'ât'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'ng', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v'],
				'âu'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'ây'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'ec'	=> ['kh', 'l', 'm', 'r', 's'],
				'em'	=> ['ch', 'đ', 'gh', 'gi', 'h', 'k', 'l', 'n', 'nh', 'r', 't', 'th', 'x'],
				'en'	=> ['b', 'ch', 'đ', 'gh', 'gi', 'h', 'k', 'kh', 'l', 'm', 'n', 'ngh', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'v', 'x'],
				'eo'	=> ['b', 'ch', 'd', 'đ', 'gh', 'gi', 'h', 'k', 'kh', 'l', 'm', 'n', 'ngh', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'ep'	=> ['b', 'ch', 'd', 'đ', 'gh', 'h', 'k', 'kh', 'l', 'm', 'n', 'nh', 'ph', 't', 'th', 'x'],
				'et'	=> ['b', 'ch', 'd', 'đ', 'gh', 'h', 'k', 'kh', 'l', 'm', 'n', 'ngh', 'nh', 'ph', 'qu', 'r', 's', 't', 'tr', 'v', 'x'],
				'êm'	=> ['ch', 'đ', 'k', 'n', 'th', 'x'],
				'ên'	=> ['b', 'đ', 'h', 'k', 'l', 'm', 'n', 'nh', 'ph', 'qu', 'r', 's', 't', 'tr', 'v'],
				'êp'	=> ['b', 'n', 'r', 's', 'th', 'x'],
				'êt'	=> ['b', 'ch', 'd', 'h', 'k', 'l', 'm', 'n', 'qu', 'r', 's', 't', 'v', 'x'],
				'êu'	=> ['b', 'đ', 'k', 'l', 'm', 'n', 'ngh', 'r', 's', 't', 'th', 'tr'],
				'ia'	=> ['b', 'ch', 'd', 'đ', 'k', 'kh', 'l', 'm', 'n', 'ngh', 'p', 'ph', 'r', 't', 'th', 'v', 'x'],
				'ic'	=> ['h', 't'],
				'im'	=> ['b', 'ch', 'd', 'gh', 'h', 'k', 'l', 'm', 'nh', 'ph', 's', 't', 'th'],
				'in'	=> ['b', 'ch', 'k', 'm', 'n', 'nh', 'ph', 't', 'th', 'v', 'x'],
				'ip'	=> ['b', 'ch', 'd', 'k', 'm', 'nh', 's'],
				'it'	=> ['b', 'ch', 'đ', 'h', 'k', 'kh', 'm', 'n', 'ngh', 'r', 's', 't', 'th', 'v', 'x'],
				'iu'	=> ['b', 'ch', 'd', 'h', 'l', 'n', 'r', 't', 'th', 'x'],
				'oa'	=> ['d', 'đ', 'g', 'h', 'kh', 'l', 'ng', 't', 'th', 'x'],
				'oc'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'r', 's', 't', 'th', 'tr', 'v'],
				'oe'	=> ['h', 'kh', 'l', 'ng', 'nh', 't', 'x'],
				'oi'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'om'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'h', 'kh', 'l', 'n', 'ng', 'nh', 'ph', 'r', 's', 't', 'v', 'x'],
				'on'	=> ['b', 'c', 'ch', 'đ', 'g', 'gi', 'h', 'l', 'm', 'n', 'ng', 'nh', 'r', 's', 't', 'th', 'tr', 'v'],
				'op'	=> ['b', 'c', 'ch', 'g', 'h', 'm', 'ng', 'nh', 'th'],
				'ot'	=> ['b', 'c', 'ch', 'đ', 'gi', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'ôc'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'ph', 'qu', 'r', 's', 't', 'th', 'x'],
				'ôi'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'ôm'	=> ['c', 'ch', 'đ', 'g', 'h', 'n', 'nh', 't', 'tr', 'x'],
				'ôn'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'r', 't', 'th', 'tr', 'v', 'x'],
				'ôp'	=> ['b', 'c', 'ch', 'đ', 'g', 'l', 'n', 'ng', 'r', 's', 't', 'th', 'x'],
				'ôt'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'h', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'r', 's', 't', 'th', 'x'],
				'ơi'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'ơm'	=> ['b', 'c', 'ch', 'đ', 'n', 'r', 's', 'th'],
				'ơn'	=> ['b', 'c', 'ch', 'đ', 'g', 'gi', 'h', 'l', 'm', 'nh', 'r', 's', 't', 'tr'],
				'ơp'	=> ['b', 'ch', 'd', 'đ', 'h', 'kh', 'l', 'n', 'ng', 'r'],
				'ơt'	=> ['b', 'c', 'ch', 'd', 'đ', 'h', 'l', 'ng', 'nh', 'ph', 'qu', 'r', 's', 'th', 'v'],
				'ua'	=> ['b', 'c', 'ch', 'd', 'đ', 'h', 'kh', 'l', 'm', 'n', 'nh', 'r', 's', 't', 'th', 'v', 'x'],
				'uc'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'r', 's', 't', 'th', 'tr', 'x'],
				'uê'	=> ['d', 'h', 'kh', 's', 't', 'th', 'v', 'x'],
				'ui'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'h', 'kh', 'l', 'm', 'n', 'nh', 'ph', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'um'	=> ['b', 'c', 'ch', 'd', 'đ', 'gi', 'kh', 'l', 'm', 'n', 'ng', 'nh', 's', 't', 'tr', 'x'],
				'un'	=> ['b', 'c', 'ch', 'đ', 'gi', 'h', 'l', 'm', 'ng', 'nh', 'ph', 'r', 's', 't', 'th', 'v'],
				'up'	=> ['b', 'c', 'ch', 'gi', 'h', 'l', 'm', 'n', 'ng', 'r', 's', 't', 'th'],
				'uơ'	=> ['th'],
				'ut'	=> ['b', 'c', 'ch', 'h', 'l', 'm', 'n', 'ng', 'ph', 'r', 's', 't', 'th', 'tr', 'v'],
				'uy'	=> ['d', 'h', 'kh', 'l', 'ng', 'nh', 'ph', 's', 't', 'th', 'tr', 'x'],
				'ưa'	=> ['b', 'c', 'ch', 'd', 'đ', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'ưc'	=> ['b', 'c', 'ch', 'đ', 'h', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'ưi'	=> ['c', 'ch', 'g', 'ng'],
				'ưm'	=> ['h', 'ng'],
				'ưt'	=> ['b', 'c', 'd', 'đ', 'gi', 'm', 'n', 'nh', 's', 'v', 'x'],
				'ưu'	=> ['b', 'c', 'h', 'kh', 'l', 'm', 'ng', 's', 't', 'tr'],
				'yt'	=> ['qu'],

				// 3-char syllables
				'ach'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'h', 'kh', 'l', 'm', 'n', 'ng', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'ang'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'anh'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'ăng'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'qu', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'âng'	=> ['b', 'd', 'l', 'n', 't', 'v'],
				'eng'	=> ['b', 'k', 'l', 'x'],
				'êch'	=> ['ch', 'k', 'l', 'ng', 'nh', 'ph', 'th', 'x'],
				'ênh'	=> ['b', 'ch', 'd', 'đ', 'gh', 'h', 'k', 'l', 'm', 'ngh', 't', 'th', 'v'],
				'ich'	=> ['b', 'ch', 'd', 'đ', 'h', 'k', 'kh', 'l', 'm', 'n', 'ngh', 'nh', 'ph', 'r', 't', 'th', 'tr', 'x'],
				'iêc'	=> ['b', 'ch', 'd', 'đ', 'gh', 'l', 'nh', 't', 'th', 'v', 'x'],
				'iêm'	=> ['b', 'ch', 'd', 'đ', 'h', 'k', 'kh', 'l', 'n', 'ngh', 'nh', 'ph', 't', 'th', 'v', 'x'],
				'iên'	=> ['b', 'ch', 'd', 'đ', 'gh', 'h', 'k', 'kh', 'l', 'm', 'n', 'ngh', 'nh', 'ph', 't', 'th', 'tr', 'v', 'x'],
				'iêp'	=> ['d', 'đ', 'h', 'ngh', 'nh', 't', 'th'],
				'iêt'	=> ['b', 'ch', 'd', 'g', 'k', 'kh', 'l', 'm', 'n', 'ngh', 'nh', 'ph', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'iêu'	=> ['b', 'ch', 'd', 'đ', 'h', 'k', 'kh', 'l', 'm', 'n', 'nh', 'ph', 'r', 's', 't', 'th', 'tr', 'x'],
				'inh'	=> ['b', 'ch', 'd', 'đ', 'h', 'k', 'kh', 'l', 'm', 'n', 'ngh', 'nh', 'ph', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'oac'	=> ['ch', 'kh', 'ng'],
				'oai'	=> ['ch', 'đ', 'h', 'kh', 'l', 'ng', 'nh', 's', 't', 'th', 'x'],
				'oam'	=> ['ng'],
				'oan'	=> ['d', 'đ', 'h', 'kh', 'l', 'ng', 's', 't', 'x'],
				'oat'	=> ['đ', 'h', 'kh', 'l', 's', 't', 'th'],
				'oay'	=> ['h', 'kh', 'l', 'ng', 'x'],
				'oăc'	=> ['h', 'ng'],
				'oăm'	=> [],
				'oăn'	=> ['th', 'x'],
				'oăt'	=> ['h', 'th'],
				'oen'	=> ['h', 'kh'],
				'oeo'	=> ['ng'],
				'oet'	=> ['kh', 'l', 't'],
				'ong'	=> ['b', 'c', 'ch', 'd', 'đ', 'gi', 'h', 'l', 'm', 'n', 'ng', 'ph', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'ông'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gh', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'uân'	=> ['ch', 'd', 'h', 'kh', 'l', 'nh', 't', 'th', 'x'],
				'uât'	=> ['d', 'kh', 'l', 's', 't', 'th', 'tr', 'x'],
				'uây'	=> ['kh', 'ng'],
				'ung'	=> ['b', 'c', 'ch', 'd', 'đ', 'h', 'kh', 'l', 'm', 'n', 'nh', 'ph', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'uôc'	=> ['b', 'c', 'ch', 'đ', 'g', 'gi', 'l', 'nh', 'r', 't', 'th'],
				'uôi'	=> ['c', 'ch', 'd', 'đ', 'm', 'n', 'ng', 's', 'x'],
				'uôm'	=> ['b', 'c', 'nh', 'th'],
				'uôn'	=> ['b', 'c', 'ch', 'kh', 'l', 'm', 'ng', 'r', 's', 't', 'th'],
				'uôt'	=> ['b', 'ch', 'r', 't', 'th', 'tr'],
				'uya'	=> ['kh'],
				'uyt'	=> ['h', 's', 't'],
				'uyu'	=> ['kh'],
				'ưng'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'r', 's', 't', 'th', 'tr', 'v', 'x'],
				'ươc'	=> ['b', 'c', 'ch', 'd', 'đ', 'kh', 'l', 'ng', 'nh', 'ph', 'r', 't', 'th', 'tr', 'x'],
				'ươi'	=> ['b', 'c', 'd', 'đ', 'kh', 'l', 'm', 'ng', 'r', 's', 't'],
				'ươm'	=> ['ch', 'g', 'l', 't'],
				'ươn'	=> ['b', 'l', 'tr', 'v'],
				'ươp'	=> ['c', 'm'],
				'ươt'	=> ['l', 'm', 'ph', 'r', 's', 't', 'th', 'tr', 'v'],
				'ươu'	=> ['b', 'h', 'kh', 'r'],
				'yên'	=> ['qu'],
				'yêt'	=> ['qu'],
				'yêu'	=> [],
				'ynh'	=> ['qu'],

				// 4-char syllables
				'iêng'	=> ['b', 'ch', 'đ', 'g', 'k', 'kh', 'l', 'ngh', 'r', 's', 't', 'th', 'v'],
				'oach'	=> ['x'],
				'oang'	=> ['ch', 'đ', 'h', 'kh', 'l', 'nh', 'th', 'x'],
				'oanh'	=> ['d', 'h', 'l', 't', 'x'],
				'oăng'	=> ['gi', 'h'],
				'oong'	=> ['b', 'c', 'đ', 'k', 'x'],
				'uâng'	=> ['kh'],
				'uêch'	=> ['kh'],
				'uênh'	=> ['h'],
				'uông'	=> ['b', 'c', 'ch', 'đ', 'h', 'kh', 'l', 'm', 'n', 't', 'th', 'tr', 'v', 'x'],
				'uych'	=> [],
				'uyên'	=> ['ch', 'd', 'h', 'kh', 'l', 'ng', 'nh', 's', 't', 'th', 'tr', 'x'],
				'uyêt'	=> ['d', 'h', 'kh', 'ng', 't', 'th', 'x'],
				'uynh'	=> ['h', 'kh'],
				'ương'	=> ['b', 'c', 'ch', 'd', 'đ', 'g', 'gi', 'h', 'kh', 'l', 'm', 'n', 'ng', 'nh', 'ph', 'r', 's', 't', 'th', 'tr', 'v', 'x']
			],
			// List of correct words
			'words'	=> [
				'a', 'ba', 'ca', 'cha', 'da', 'đa', 'ga', 'gia', 'ha', 'kha'/* kha khá */, 'la', 'ma', 'na', 'nga', 'nha', 'pa'/* Sa Pa */, 'pha', 'qua', 'ra', 'sa', 'ta', 'tha', 'tra', 'va', 'xa',
				'à', 'bà', 'cà', 'chà', 'đà', 'gà', 'già', 'hà', 'khà', 'là', 'mà', 'ngà', 'nhà', 'phà', 'quà', 'rà', 'sà'/* sà xuống */, 'tà', 'thà', 'trà', 'và', 'xà',
				'ả', 'bả', 'cả', 'chả', 'đả', 'gả', 'giả', 'hả', 'khả', 'lả'/* lả lướt */, 'mả', 'ngả', 'nhả', 'phả', 'quả', 'rả'/* rôm rả */, 'sả', 'tả', 'thả', 'trả', 'vả', 'xả',
				'bã', 'dã', 'đã', 'gã', 'giã'/* từ giã */, 'lã'/* nước lã */, 'mã', 'nã', 'ngã', 'nhã'/* nhã nhặn */, 'rã'/* rệu rã */, 'sã'/* suồng sã */, 'tã'/* cái tã */, 'vã'/* vật vã */, 'xã',
				'á', 'bá', 'cá', 'đá', 'gá'/* đồ gá: mâm cặp máy tiện */, 'giá', 'há', 'khá', 'lá', 'má', 'ná', 'nhá', 'phá', 'quá', 'rá'/* rổ rá */, 'sá', 'tá', 'thá'/* cái thá gì */, 'trá', 'vá', 'xá',
				'ạ', 'bạ', 'cạ', 'chạ'/* chung chạ */, 'dạ', 'gạ', 'hạ', 'lạ', 'mạ'/* gieo mạ */, 'nạ', 'quạ', 'rạ'/* rơm rạ */, 'sạ'/* gieo sạ */, 'tạ', 'vạ', 'xạ',
				'e', 'be', 'che', 'de'/* lùi xe */, 'đe', 'ghe', 'he', 'ke'/* xạo ke */, 'khe', 'le'/* le lói */, 'me', 'nghe', 'nhe', 'phe', 'que', 're'/* khỏe re */, 'se'/* se lạnh */, 'te', 'the', 'tre', 've', 'xe',
				'è', 'bè', 'chè', 'dè', 'đè', 'hè', 'kè', 'khè', 'lè', 'mè', 'nè', 'phè'/* phè phỡn */, 'què', 'rè', 'tè', 'vè', 'xè'/* xè xè: tiếng máy cưa */,
				'bẻ', 'chẻ', 'dẻ', 'đẻ', 'ghẻ', 'giẻ'/* giẻ lau */, 'kẻ', 'lẻ', 'mẻ'/* mẻ kho */, 'nẻ'/* nứt nẻ */, 'quẻ', 'rẻ', 'sẻ', 'tẻ'/* gạo tẻ */, 'thẻ', 'vẻ', 'xẻ',
				'bẽ'/* bẽ bàng */, 'chẽ'/* chặt chẽ */, 'kẽ', 'khẽ'/* đi nhẹ nói khẽ */, 'lẽ', 'mẽ'/* mạnh mẽ */, 'nhẽ', 'rẽ', 'sẽ', 'tẽ'/* tẽ: tách hạt ra */, 'thẽ'/* thẽ thọt */, 'vẽ',
				'é', 'bé', 'ché'/* ché rượu */, 'ghé', 'hé', 'ké', 'khé'/* chua khé */, 'lé', 'mé'/* mấp mé */, 'né', 'nghé'/* con nghé */, 'nhé', 'ré'/* ré lên */, 'té', 'thé'/* the thé */, 'vé', 'xé',
				'ẹ', 'bẹ'/* bập bẹ */, 'ghẹ', 'hẹ', 'kẹ'/* ông kẹ */, 'lẹ', 'mẹ', 'nhẹ',
				'ê', 'bê', 'chê', 'dê', 'đê', 'ghê', 'hê', 'kê', 'khê'/* nhiêu khê */, 'lê', 'mê', 'nê'/* cây cùng họ với na */, 'phê', 'quê', 'rê', 'tê', 'thê', 'trê', 'vê'/* vê bột */, 'xê',
				'bề', 'chề'/* ê chề */, 'dề'/* tạp dề */, 'đề', 'ghề'/* gồ ghề */, 'hề', 'kề', 'lề', 'mề', 'nề', 'nghề', 'sề'/* sồ sề */, 'tề'/* vùng bị chiếm đóng */, 'thề', 'về', 'xề'/* xề xệ */,
				'bể', 'để', 'hể'/* hể hả */, 'kể', 'nể', 'rể', 'tể'/* tể tướng */, 'thể',
				'dễ', 'hễ', 'lễ', 'mễ'/* đồ dùng để kê đỡ */, 'rễ', 'tễ'/* thuốc tễ */, 'trễ',
				'ế', 'bế', 'chế', 'dế', 'đế', 'ghế', 'hế', 'kế', 'khế', 'phế', 'quế', 'rế'/* bánh rế */, 'tế', 'thế', 'vế', 'xế',
				'bệ', 'chệ'/* chiễm chệ */, 'đệ', 'ghệ', 'hệ', 'kệ', 'khệ'/* khệ nệ */, 'lệ', 'mệ'/* mẹ */, 'nệ'/* nệ theo lối cũ */, 'nghệ', 'quệ'/* kiệt quệ */, 'rệ'/* rệ bánh */, 'tệ', 'thệ'/* tuyên thệ */, 'trệ'/* đình trệ */, 'vệ', 'xệ',
				'i', 'bi', 'chi', 'di', 'đi', 'ghi', 'hi', 'ki'/* ki bo */, 'khi', 'li'/* chi li */, 'mi', 'ni'/* nay */, 'nghi', 'nhi', 'phi', 'ri'/* cà ri */, 'si'/* cây si */, 'ti', 'thi', 'tri', 'vi', 'xi'/* xi măng */,
				'bì', 'chì', 'dì', 'đì', 'gì', 'ghì', 'hì', 'kì'/* kì cục */, 'khì', 'lì', 'mì'/* mì gói */, 'nhì', 'phì', 'rì'/* rù rì */, 'sì'/* đen sì */, 'tì'/* tì đè */, 'thì', 'trì', 'vì', 'xì'/* hắt xì */,
				'ỉ'/* ỉ eo */, 'bỉ'/* bền bỉ */, 'chỉ', 'hỉ'/* hỉ mũi */, 'khỉ', 'mỉ'/* tỉ mỉ */, 'nỉ'/* năn nỉ */, 'nghỉ', 'nhỉ', 'phỉ', 'rỉ'/* rò rỉ */, 'sỉ'/* số lượng nhiều */, 'tỉ'/* tỉ phú */, 'thỉ'/* thủ thỉ */, 'vỉ', 'xỉ'/* xỉ vả */,
				'bĩ'/* bĩ cực */, 'dĩ'/* bất đắc dĩ */, 'đĩ', 'kĩ'/* cũ kĩ */, 'lĩ'/* lầm lĩ */, 'nghĩ', 'nhĩ', 'rĩ'/* rầu rĩ */, 'tĩ'/* hậu môn */, 'trĩ', 'vĩ',
				'í', 'bí', 'chí', 'dí', 'hí', 'kí'/* kí đầu */, 'khí', 'lí'/* lí nhí */, 'mí'/* mí mắt */, 'nhí', 'phí', 'rí'/* cười ri rí */, 'tí'/* bé tí */, 'thí', 'trí', 'ví', 'xí',
				'ị', 'bị', 'chị', 'dị', 'kị'/* cụ kị */, 'khị'/* dụ khị */, 'lị'/* kiết lị */, 'mị'/* mụ mị */, 'nghị', 'nhị', 'tị'/* tị nạnh */, 'thị', 'trị', 'vị', 'xị'/* xị rượu */,
				'o', 'bo', 'co', 'cho', 'do', 'đo', 'ho', 'kho', 'lo', 'mo'/* quạt mo */, 'no', 'nho', 'pho', 'ro'/* rủi ro */, 'so', 'to', 'tho'/* Mỹ Tho */, 'tro', 'vo', 'xo'/* lò xo */,
				'ò', 'bò', 'cò', 'dò', 'đò', 'gò', 'giò', 'hò', 'khò', 'lò', 'mò', 'ngò', 'phò', 'rò', 'sò', 'tò', 'thò', 'trò', 'vò',
				'bỏ', 'cỏ', 'chỏ'/* cùi chỏ */, 'đỏ', 'giỏ', 'mỏ', 'nỏ', 'ngỏ'/* bỏ ngỏ */, 'nhỏ', 'sỏ'/* đầu sỏ */, 'tỏ', 'thỏ', 'trỏ'/* trỏ chuột */, 'vỏ', 'xỏ'/* chơi xỏ */,
				'õ', 'bõ'/* bõ bèn */, 'chõ'/* nói chõ vào */, 'gõ', 'lõ'/* mũi lõ */, 'ngõ', 'rõ', 'võ',
				'ó', 'bó', 'có', 'chó', 'dó'/* giấy dó */, 'đó', 'gió', 'hó'/* hó hé */, 'khó', 'ló', 'mó'/* sờ mó */, 'nó', 'ngó', 'nhó'/* nhăn nhó */, 'phó', 'ró'/* ró gạo */, 'thó'/* lấy cắp */, 'vó'/* cất vó */, 'xó'/* xó xỉnh */,
				'bọ', 'cọ', 'đọ', 'họ', 'lọ', 'mọ'/* lọ mọ */, 'nọ', 'ngọ'/* giờ Ngọ */, 'nhọ'/* số nhọ */, 'rọ'/* cái rọ */, 'sọ'/* sọ não */, 'thọ', 'trọ', 'xọ'/* xéo xọ */,
				'ô', 'bô', 'cô', 'dô'/* trán dô */, 'đô', 'gô'/* gô cổ */, 'hô', 'khô', 'lô', 'mô', 'nô', 'ngô', 'nhô', 'phô', 'rô', 'sô'/* sô diễn */, 'tô', 'thô', 'trô'/* trô trố */, 'vô', 'xô',
				'ồ', 'bồ', 'cồ'/* to lớn */, 'dồ'/* xông tới */, 'đồ', 'gồ'/* gồ lên */, 'hồ', 'lồ'/* khổng lồ */, 'mồ', 'rồ'/* điên rồ */, 'sồ'/* sồ sề */, 'tồ'/* khờ khạo */, 'thồ', 'trồ'/* trầm trồ */, 'vồ', 'xồ'/* chạy xồ ra */,
				'ổ', 'bổ', 'cổ', 'đổ', 'hổ', 'khổ', 'lổ'/* loang lổ */, 'mổ', 'nổ', 'ngổ'/* ngổ ngáo */, 'nhổ', 'phổ', 'rổ', 'sổ', 'tổ', 'thổ', 'trổ', 'xổ'/* xổ số */,
				'cỗ', 'chỗ', 'dỗ'/* dỗ dành */, 'đỗ'/* đỗ đạt */, 'gỗ', 'giỗ', 'lỗ', 'mỗ'/* đại từ tự xưng */, 'nỗ', 'ngỗ'/* ngỗ nghịch */, 'vỗ',
				'bố', 'cố', 'đố', 'hố', 'khố', 'lố', 'mố'/* mố cầu */, 'ngố', 'nhố'/* nhí nhố */, 'phố', 'số', 'tố', 'thố', 'trố'/* trố mắt */, 'vố',
				'bộ', 'cộ'/* xe cộ */, 'độ', 'hộ', 'lộ', 'mộ', 'nộ', 'ngộ', 'rộ'/* rầm rộ */, 'sộ'/* đồ sộ */, 'tộ'/* tô */, 'thộ',
				'ơ', 'bơ', 'cơ', 'chơ'/* chỏng chơ */, 'dơ', 'đơ', 'giơ', 'hơ', 'khơ'/* tú lơ khơ */, 'lơ', 'mơ', 'nơ', 'ngơ', 'nhơ'/* nhơ nhuốc */, 'phơ'/* phất phơ */, 'quơ', 'rơ'/* ăn rơ */, 'sơ', 'tơ', 'thơ', 'trơ', 'vơ'/* bơ vơ */, 'xơ'/* xơ xác */,
				'ờ', 'bờ', 'cờ', 'chờ', 'gờ', 'giờ', 'hờ'/* hờ hững */, 'khờ', 'lờ', 'mờ', 'ngờ', 'nhờ', 'phờ'/* phờ phạc */, 'rờ', 'sờ', 'tờ', 'thờ', 'trờ'/* trờ tới */, 'vờ',
				'ở', 'bở'/* béo bở */, 'chở', 'dở', 'gở'/* điềm gở */, 'giở', 'hở', 'lở'/* sạt lở */, 'mở', 'nở', 'nhở'/* nham nhở */, 'phở', 'quở'/* quở trách */, 'sở', 'tở'/* vôi tở */, 'thở', 'trở', 'vở',
				'bỡ'/* bỡ ngỡ */, 'cỡ', 'dỡ'/* dỡ hàng */, 'đỡ', 'gỡ', 'lỡ', 'mỡ', 'nỡ', 'ngỡ', 'nhỡ', 'rỡ', 'sỡ', 'vỡ',
				'ớ', 'bớ', 'cớ', 'chớ', 'đớ'/* đớ lưỡi */, 'hớ', 'lớ'/* lớ mớ */, 'mớ', 'ngớ'/* ngớ ngẩn */, 'nhớ', 'rớ', 'sớ', 'tớ', 'thớ'/* thớ thịt */, 'trớ'/* trớ sữa */, 'vớ',
				'ợ', 'bợ', 'chợ', 'đợ', 'lợ', 'mợ', 'nợ', 'ngợ'/* ngờ ngợ */, 'nhợ'/* dây nhợ */, 'rợ', 'sợ', 'thợ', 'trợ', 'vợ',
				'u', 'bu', 'cu', 'chu', 'du', 'đu', 'gu', 'hu', 'khu', 'lu', 'mu', 'ngu', 'nhu', 'phu', 'ru', 'su'/* su hào */, 'tu', 'thu', 'tru'/* sói tru */, 'vu', 'xu',
				'ù', 'bù', 'cù', 'chù'/* chuột chù */, 'dù', 'đù'/* lù đù */, 'gù', 'hù', 'khù'/* khù khờ */, 'lù'/* thù lù */, 'mù', 'phù', 'rù'/* rù rì */, 'tù', 'thù', 'trù', 'vù'/* bay vù vù */, 'xù',
				'ủ', 'củ', 'đủ', 'hủ', 'khủ'/* lủ khủ */, 'lủ'/* lủ khủ */, 'mủ', 'ngủ', 'nhủ'/* nhắn nhủ */, 'phủ', 'rủ', 'tủ', 'thủ',
				'cũ', 'lũ', 'mũ', 'ngũ', 'nhũ', 'phũ'/* phũ phàng */, 'rũ', 'vũ',
				'ú', 'bú', 'cú', 'chú', 'đú'/* đú đỡn */, 'hú', 'lú', 'mú'/* cá mú */, 'nhú', 'phú', 'rú'/* mừng rú lên */, 'sú'/* cây bụi mọc ở vùng nước lợ */, 'tú', 'thú', 'trú', 'vú',
				'ụ', 'bụ'/* bụ bẫm */, 'cụ', 'dụ', 'đụ', 'hụ', 'khụ'/* lụ khụ */, 'lụ'/* lụ khụ */, 'mụ', 'nụ', 'ngụ', 'phụ', 'sụ'/* giàu sụ */, 'tụ', 'thụ', 'trụ', 'vụ',
				'ư', 'bư'/* mặt bư */, 'cư', 'chư'/* chư vị */, 'dư', 'hư', 'khư', 'lư'/* lắc lư */, 'nư'/* cái nư */, 'ngư', 'như', 'sư', 'tư', 'thư', 'trư'/* con heo */,
				'ừ', 'cừ', 'chừ'/* chần chừ */, 'đừ'/* đừ người */, 'gừ'/* gầm gừ */, 'hừ', 'ngừ', 'nhừ'/* ninh nhừ */, 'từ', 'thừ'/* thừ người */, 'trừ',
				'cử'/* cử nhân */, 'hử'/* ừ hử */, 'khử', 'nhử', 'sử', 'tử', 'thử', 'xử',
				'cữ'/* kiêng cữ */, 'chữ', 'dữ', 'giữ', 'lữ'/* lữ đoàn */, 'nữ', 'ngữ', 'trữ',
				'ứ', 'cứ', 'chứ', 'hứ', 'khứ', 'sứ', 'tứ', 'thứ', 'trứ', 'xứ',
				'ự', 'bự', 'cự', 'hự', 'lự'/* ngay tắp lự */, 'ngự', 'sự', 'tự', 'thự',
				'hy'/* hy sinh */, 'ly'/* ly biệt */, 'my'/* lông mày */, 'quy', 'sy'/* sy tình */, 'ty'/* công ty */, 'thy'/* tên người */, 'vy'/* tên người */,
				'ỳ', 'kỳ'/* thời kỳ */, 'mỳ'/* nhu mỳ */, 'quỳ', 'tỳ'/* đàn tỳ bà */,
				'ỷ'/* ỷ lại */, 'hỷ'/* hoan hỷ */, 'kỷ'/* kỷ niệm */, 'quỷ', 'tỷ'/* tỷ tỷ */,
				'kỹ'/* kỹ thuật */, 'mỹ'/* mỹ thuật */, 'quỹ', 'sỹ'/* liệt sỹ */,
				'ý', 'hý'/* du hý */, 'ký'/* nhật ký */, 'lý'/* lý lẽ */, 'quý', 'tý'/* tuổi Tý */,
				'kỵ'/* kiêng kỵ */, 'lỵ'/* huyện lỵ */, 'mỵ'/* ủy mỵ */, 'quỵ'/* đột quỵ */, 'tỵ'/* tỵ nạn */,

				'ác', 'bác', 'các', 'chác'/* chia chác */, 'dác'/* dáo dác */, 'đác'/* lác đác */, 'gác', 'giác', 'hác'/* hốc hác */, 'khác', 'lác'/* cá thác lác */, 'mác', 'ngác', 'nhác'/* nhác thấy bóng người */, 'phác', 'quác', 'rác', 'sác'/* Rừng Sác */, 'tác', 'thác', 'trác', 'vác', 'xác',
				'bạc', 'chạc', 'đạc', 'gạc', 'hạc', 'khạc', 'lạc', 'mạc', 'nạc', 'ngạc', 'nhạc', 'quạc', 'sạc', 'tạc', 'thạc', 'trạc', 'vạc', 'xạc',
				'ai', 'cai', 'chai', 'dai', 'đai', 'gai', 'giai', 'hai', 'khai', 'lai', 'mai', 'nai', 'ngai', 'nhai', 'phai', 'quai', 'rai', 'sai', 'tai', 'thai', 'trai', 'vai',
				'bài', 'cài', 'chài', 'dài', 'đài', 'gài', 'hài', 'lài', 'mài', 'nài', 'ngài', 'nhài', 'quài', 'sài', 'tài', 'vài', 'xài',
				'ải', 'cải', 'chải', 'dải', 'giải', 'hải', 'khải', 'lải', 'mải', 'nải', 'ngải', 'nhải', 'phải', 'quải', 'rải', 'sải', 'tải', 'thải', 'trải', 'vải',
				'bãi', 'cãi', 'dãi', 'đãi', 'gãi', 'hãi', 'lãi', 'mãi', 'ngãi', 'nhãi', 'trãi', 'vãi',
				'ái', 'bái', 'cái', 'dái', 'đái', 'gái', 'hái', 'khái', 'lái', 'mái', 'nái', 'ngái', 'nhái', 'phái', 'quái', 'rái', 'sái'/* ngã sái tay */, 'tái', 'thái', 'trái', 'vái',
				'bại', 'dại', 'đại', 'hại', 'lại', 'mại', 'nại', 'ngại', 'nhại'/* bắt chước */, 'tại', 'trại', 'vại',
				'am', 'cam', 'đam', 'gam', 'giam', 'ham', 'kham'/* kham khổ */, 'lam', 'nam', 'nham', 'sam'/* con sam */, 'tam', 'tham',
				'càm', 'chàm', 'đàm', 'hàm', 'làm', 'ngàm', 'nhàm', 'phàm', 'ràm'/* càm ràm */, 'sàm', 'tràm', 'vàm', 'xàm',
				'ảm', 'cảm', 'đảm', 'giảm', 'khảm', 'nhảm', 'thảm', 'trảm',
				'hãm', 'lãm',
				'ám', 'bám', 'cám', 'dám', 'đám', 'giám', 'hám', 'khám', 'nám', 'nhám', 'rám', 'sám', 'tám', 'thám', 'trám', 'xám',
				'chạm', 'dạm', 'đạm', 'hạm', 'lạm', 'nạm', 'phạm', 'rạm', 'tạm', 'trạm', 'vạm',
				'an', 'ban', 'can', 'chan'/* chứa chan */, 'đan', 'gan', 'gian', 'khan', 'lan', 'man', 'nan', 'ngan', 'nhan', 'phan', 'quan', 'san', 'tan', 'than', 'van',
				'bàn', 'càn', 'đàn', 'gàn'/* gàn dở */, 'giàn', 'hàn', 'khàn', 'làn', 'màn', 'ngàn', 'nhàn', 'sàn', 'tàn', 'tràn', 'vàn',
				'bản', 'cản', 'đản'/* Phật Đản */, 'giản', 'khản', 'nản', 'phản', 'quản', 'sản', 'tản', 'thản',
				'giãn', 'hãn', 'mãn', 'nhãn', 'vãn',
				'án', 'bán', 'cán', 'chán', 'dán', 'đán'/* Tết Nguyên Đán */, 'gán', 'gián', 'hán', 'khán', 'lán', 'mán', 'nán', 'ngán', 'phán', 'quán', 'rán', 'sán', 'tán', 'thán', 'trán', 'ván', 'xán'/* xán lạn */,
				'bạn', 'cạn', 'dạn', 'đạn', 'gạn'/* gạn đục khơi trong */, 'hạn', 'mạn', 'nạn', 'ngạn', 'nhạn', 'phạn'/* Tiếng Phạn */, 'rạn', 'sạn', 'vạn',
				'ao', 'bao', 'cao', 'chao', 'dao', 'đao', 'gao', 'giao', 'hao', 'khao', 'lao', 'mao', 'nao', 'ngao'/* con nghêu */, 'nhao', 'phao', 'rao', 'sao', 'tao', 'thao', 'trao', 'xao',
				'ào', 'bào', 'cào', 'chào', 'dào', 'đào', 'gào', 'hào', 'lào', 'mào', 'nào', 'ngào', 'nhào', 'phào', 'rào', 'sào', 'tào', 'thào', 'trào', 'vào', 'xào',
				'ảo', 'bảo', 'cảo', 'chảo', 'đảo', 'hảo', 'khảo', 'lảo', 'tảo', 'thảo', 'trảo'/* móng vuốt */,
				'bão', 'hão'/* hão huyền */, 'lão', 'mão', 'não', 'nhão',
				'áo', 'báo', 'cáo', 'cháo', 'dáo', 'đáo', 'gáo', 'giáo', 'háo', 'láo', 'náo', 'ngáo', 'nháo', 'pháo', 'ráo', 'sáo', 'táo', 'tháo', 'tráo', 'xáo'/* xào xáo */,
				'bạo', 'dạo', 'đạo', 'gạo', 'hạo'/* trời */, 'khạo', 'mạo', 'nạo', 'nhạo', 'quạo', 'thạo', 'xạo',
				'áp', 'cáp', 'đáp', 'giáp', 'ngáp', 'nháp', 'pháp', 'quáp', 'ráp', 'sáp', 'táp', 'tháp', 'tráp', 'xáp'/* xáp lá cà */,
				'cạp', 'đạp', 'hạp', 'khạp', 'lạp', 'nạp', 'rạp', 'sạp', 'tạp',
				'át', 'bát', 'cát', 'chát', 'dát', 'đát', 'hát', 'khát', 'lát', 'mát', 'nát', 'ngát', 'nhát', 'phát', 'quát', 'rát', 'sát', 'tát', 'trát', 'vát', 'xát',
				'bạt', 'dạt', 'đạt', 'gạt', 'hạt', 'lạt', 'mạt', 'nạt', 'ngạt', 'nhạt', 'phạt', 'quạt', 'sạt', 'tạt', 'vạt',
				'au', 'cau', 'đau', 'mau', 'nhau', 'rau', 'sau', 'thau',
				'bàu', 'càu', 'giàu', 'hàu', 'màu', 'nhàu', 'tàu',
				'báu', 'cáu', 'cháu', 'máu', 'náu', 'sáu',
				'bay', 'cay', 'chay', 'day', 'đay', 'gay', 'hay', 'khay', 'lay', 'may', 'nay', 'ngay', 'phay', 'quay', 'say', 'tay', 'thay', 'vay', 'xay',
				'bày', 'cày', 'chày', 'dày', 'đày', 'giày', 'mày', 'này', 'ngày', 'rày', 'tày'/* người Tày */,
				'bảy', 'chảy', 'gảy', 'mảy'/* mảy may */, 'nảy'/* cầm cân nảy mực */, 'nhảy', 'sảy', 'thảy', 'vảy', 'xảy',
				'dãy', 'gãy', 'giãy', 'hãy', 'nãy',
				'áy', 'cáy', 'cháy', 'đáy', 'gáy', 'láy', 'máy', 'ngáy', 'nháy', 'ráy'/* ráy tai */, 'táy', 'váy',
				'chạy', 'dạy', 'lạy', 'nạy', 'nhạy',
				'bắc', 'cắc', 'chắc', 'đắc', 'hắc', 'khắc', 'lắc', 'mắc', 'ngắc'/* ngắc ngứ */, 'nhắc', 'phắc'/* im phăng phắc */, 'quắc'/* quắc cần câu */, 'rắc', 'sắc', 'tắc', 'thắc', 'trắc',
				'ặc', 'cặc', 'đặc', 'giặc', 'mặc', 'sặc', 'tặc', 'trặc',
				'băm', 'căm', 'chăm', 'dăm', 'găm'/* dao găm */, 'lăm', 'măm', 'năm', 'ngăm', 'nhăm'/* nhăm nhe */, 'răm'/* rau răm */, 'tăm'/* tăm xỉa răng */, 'thăm', 'trăm', 'xăm',
				'bằm', 'cằm', 'chằm', 'dằm', 'đằm', 'nằm', 'nhằm', 'rằm', 'tằm',
				'thẳm',
				'ẵm', 'bẵm',
				'cắm', 'đắm', 'khắm', 'lắm', 'mắm', 'nắm', 'ngắm', 'nhắm', 'rắm', 'sắm', 'tắm', 'thắm',
				'bặm', 'cặm', 'chặm', 'dặm', 'gặm',
				'ăn', 'băn', 'căn', 'chăn', 'khăn', 'lăn', 'năn', 'ngăn', 'nhăn', 'quăn', 'răn', 'săn', 'tăn'/* lăn tăn */, 'thăn', 'trăn', 'văn',
				'cằn', 'chằn', 'dằn', 'hằn'/* hằn học */, 'lằn', 'nhằn', 'thằn'/* thằn lằn */,
				'chẵn',
				'bắn', 'cắn', 'chắn', 'đắn', 'gắn', 'hắn', 'nắn', 'ngắn', 'nhắn', 'quắn', 'rắn', 'sắn', 'thắn',
				'cặn', 'chặn', 'dặn', 'đặn', 'lặn', 'mặn', 'nặn', 'vặn',
				'ắp', 'bắp', 'cắp', 'chắp', 'đắp', 'gắp', 'khắp', 'lắp', 'nắp', 'sắp', 'thắp',
				'cặp', 'gặp', 'lặp',
				'bắt', 'cắt', 'chắt', 'dắt', 'đắt', 'gắt', 'giắt', 'hắt', 'lắt'/* lắt lẻo */, 'mắt', 'ngắt', 'nhắt', 'sắt', 'tắt', 'thắt', 'vắt', 'xắt',
				'chặt', 'đặt', 'gặt', 'giặt', 'lặt', 'mặt', 'ngặt', 'nhặt', 'vặt',
				'bấc', 'gấc', 'giấc', 'khấc'/* chỗ cắt gọt sâu */, 'nấc', 'nhấc', 'tấc', 'xấc',
				'bậc',
				'âm', 'câm', 'châm', 'dâm', 'đâm', 'hâm', 'khâm', 'lâm', 'mâm', 'ngâm', 'nhâm', 'râm'/* bóng râm */, 'sâm', 'tâm', 'thâm', 'trâm',
				'ầm', 'bầm', 'cầm', 'chầm'/* chầm chậm */, 'dầm', 'đầm', 'gầm', 'hầm', 'lầm', 'mầm', 'ngầm', 'nhầm', 'rầm', 'sầm', 'tầm', 'thầm', 'trầm', 'xầm'/* xì xầm */,
				'ẩm', 'bẩm', 'cẩm', 'hẩm', 'mẩm'/* chắc mẩm */, 'nhẩm', 'phẩm', 'tẩm', 'thẩm', 'trẩm'/* trẩm thư */, 'xẩm'/* hát xẩm */,
				'bẫm', 'đẫm', 'giẫm', 'ngẫm', 'trẫm',
				'ấm', 'bấm', 'cấm', 'chấm', 'đấm', 'gấm', 'giấm', 'lấm', 'nấm', 'ngấm', 'nhấm', 'sấm', 'tấm', 'thấm',
				'ậm'/* ậm ừ */, 'bậm', 'chậm', 'đậm', 'giậm', 'ngậm', 'nhậm', 'rậm', 'sậm',
				'ân', 'bân', 'cân', 'chân', 'dân', 'gân', 'hân', 'lân', 'mân'/* mân mê */, 'ngân', 'nhân', 'phân', 'quân', 'sân', 'tân', 'thân', 'trân', 'vân',
				'bần', 'cần', 'chần', 'dần', 'đần', 'gần', 'lần', 'mần', 'ngần', 'phần', 'quần', 'rần', 'sần', 'tần', 'thần', 'trần', 'vần',
				'ẩn', 'bẩn', 'cẩn', 'lẩn'/* lẩn quẩn */, 'mẩn'/* mẩn ngứa */, 'ngẩn', 'quẩn'/* lẩn quẩn */, 'tẩn', 'thẩn', 'vẩn',
				'dẫn', 'lẫn', 'mẫn', 'nhẫn', 'phẫn', 'quẫn', 'vẫn',

				// Un-Completed
				'ấn', 'bấn', 'cấn', 'chấn', 'dấn', 'đấn', 'gấn', 'giấn', 'hấn', 'lấn', 'mấn', 'ngấn', 'nhấn', 'phấn', 'quấn', 'rấn', 'sấn', 'tấn', 'thấn', 'trấn', 'vấn', 'xấn',
				'ận', 'bận', 'cận', 'chận', 'dận', 'đận', 'gận', 'giận', 'hận', 'lận', 'mận', 'ngận', 'nhận', 'phận', 'quận', 'rận', 'sận', 'tận', 'thận', 'trận', 'vận', 'xận',
				'âp', 'bâp', 'câp', 'châp', 'dâp', 'đâp', 'gâp', 'giâp', 'hâp', 'khâp', 'lâp', 'mâp', 'nâp', 'ngâp', 'nhâp', 'phâp', 'râp', 'sâp', 'tâp', 'thâp', 'vâp', 'xâp',
				'ấp', 'bấp', 'cấp', 'chấp', 'dấp', 'đấp', 'gấp', 'giấp', 'hấp', 'khấp', 'lấp', 'mấp', 'nấp', 'ngấp', 'nhấp', 'phấp', 'rấp', 'sấp', 'tấp', 'thấp', 'vấp', 'xấp',
				'ập', 'bập', 'cập', 'chập', 'dập', 'đập', 'gập', 'giập', 'hập', 'khập', 'lập', 'mập', 'nập', 'ngập', 'nhập', 'phập', 'rập', 'sập', 'tập', 'thập', 'vập', 'xập',
				'ất', 'bất', 'cất', 'chất', 'dất', 'đất', 'gất', 'giất', 'hất', 'khất', 'lất', 'mất', 'ngất', 'nhất', 'phất', 'quất', 'rất', 'sất', 'tất', 'thất', 'trất', 'vất',
				'ật', 'bật', 'cật', 'chật', 'dật', 'đật', 'gật', 'giật', 'hật', 'khật', 'lật', 'mật', 'ngật', 'nhật', 'phật', 'quật', 'rật', 'sật', 'tật', 'thật', 'trật', 'vật',
				'âu', 'bâu', 'câu', 'châu', 'dâu', 'đâu', 'gâu', 'giâu', 'hâu', 'khâu', 'lâu', 'mâu', 'nâu', 'ngâu', 'nhâu', 'phâu', 'râu', 'sâu', 'tâu', 'thâu', 'trâu', 'vâu', 'xâu',
				'ầu', 'bầu', 'cầu', 'chầu', 'dầu', 'đầu', 'gầu', 'giầu', 'hầu', 'khầu', 'lầu', 'mầu', 'nầu', 'ngầu', 'nhầu', 'phầu', 'rầu', 'sầu', 'tầu', 'thầu', 'trầu', 'vầu', 'xầu',
				'ẩu', 'bẩu', 'cẩu', 'chẩu', 'dẩu', 'đẩu', 'gẩu', 'giẩu', 'hẩu', 'khẩu', 'lẩu', 'mẩu', 'nẩu', 'ngẩu', 'nhẩu', 'phẩu', 'rẩu', 'sẩu', 'tẩu', 'thẩu', 'trẩu', 'vẩu', 'xẩu',
				'ẫu', 'bẫu', 'cẫu', 'chẫu', 'dẫu', 'đẫu', 'gẫu', 'giẫu', 'hẫu', 'khẫu', 'lẫu', 'mẫu', 'nẫu', 'ngẫu', 'nhẫu', 'phẫu', 'rẫu', 'sẫu', 'tẫu', 'thẫu', 'trẫu', 'vẫu', 'xẫu',
				'ấu', 'bấu', 'cấu', 'chấu', 'dấu', 'đấu', 'gấu', 'giấu', 'hấu', 'khấu', 'lấu', 'mấu', 'nấu', 'ngấu', 'nhấu', 'phấu', 'rấu', 'sấu', 'tấu', 'thấu', 'trấu', 'vấu', 'xấu',
				'ậu', 'bậu', 'cậu', 'chậu', 'dậu', 'đậu', 'gậu', 'giậu', 'hậu', 'khậu', 'lậu', 'mậu', 'nậu', 'ngậu', 'nhậu', 'phậu', 'rậu', 'sậu', 'tậu', 'thậu', 'trậu', 'vậu', 'xậu',
				'ây', 'bây', 'cây', 'chây', 'dây', 'đây', 'gây', 'giây', 'hây', 'khây', 'lây', 'mây', 'nây', 'ngây', 'nhây', 'phây', 'quây', 'rây', 'sây', 'tây', 'thây', 'trây', 'vây', 'xây',
				'ầy', 'bầy', 'cầy', 'chầy', 'dầy', 'đầy', 'gầy', 'giầy', 'hầy', 'khầy', 'lầy', 'mầy', 'nầy', 'ngầy', 'nhầy', 'phầy', 'quầy', 'rầy', 'sầy', 'tầy', 'thầy', 'trầy', 'vầy', 'xầy',
				'ẩy', 'bẩy', 'cẩy', 'chẩy', 'dẩy', 'đẩy', 'gẩy', 'giẩy', 'hẩy', 'khẩy', 'lẩy', 'mẩy', 'nẩy', 'ngẩy', 'nhẩy', 'phẩy', 'quẩy', 'rẩy', 'sẩy', 'tẩy', 'thẩy', 'trẩy', 'vẩy', 'xẩy',
				'ẫy', 'bẫy', 'cẫy', 'chẫy', 'dẫy', 'đẫy', 'gẫy', 'giẫy', 'hẫy', 'khẫy', 'lẫy', 'mẫy', 'nẫy', 'ngẫy', 'nhẫy', 'phẫy', 'quẫy', 'rẫy', 'sẫy', 'tẫy', 'thẫy', 'trẫy', 'vẫy', 'xẫy',
				'ấy', 'bấy', 'cấy', 'chấy', 'dấy', 'đấy', 'gấy', 'giấy', 'hấy', 'khấy', 'lấy', 'mấy', 'nấy', 'ngấy', 'nhấy', 'phấy', 'quấy', 'rấy', 'sấy', 'tấy', 'thấy', 'trấy', 'vấy', 'xấy',
				'ậy', 'bậy', 'cậy', 'chậy', 'dậy', 'đậy', 'gậy', 'giậy', 'hậy', 'khậy', 'lậy', 'mậy', 'nậy', 'ngậy', 'nhậy', 'phậy', 'quậy', 'rậy', 'sậy', 'tậy', 'thậy', 'trậy', 'vậy', 'xậy',
				'éc', 'khéc', 'léc', 'méc', 'réc', 'séc',
				'ẹc', 'khẹc', 'lẹc', 'mẹc', 'rẹc', 'sẹc',
				'em', 'chem', 'đem', 'ghem', 'giem', 'hem', 'kem', 'lem', 'nem', 'nhem', 'rem', 'tem', 'them', 'xem',
				'èm', 'chèm', 'đèm', 'ghèm', 'gièm', 'hèm', 'kèm', 'lèm', 'nèm', 'nhèm', 'rèm', 'tèm', 'thèm', 'xèm',
				'ẻm', 'chẻm', 'đẻm', 'ghẻm', 'giẻm', 'hẻm', 'kẻm', 'lẻm', 'nẻm', 'nhẻm', 'rẻm', 'tẻm', 'thẻm', 'xẻm',
				'ẽm', 'chẽm', 'đẽm', 'ghẽm', 'giẽm', 'hẽm', 'kẽm', 'lẽm', 'nẽm', 'nhẽm', 'rẽm', 'tẽm', 'thẽm', 'xẽm',
				'ém', 'chém', 'đém', 'ghém', 'giém', 'hém', 'kém', 'lém', 'ném', 'nhém', 'rém', 'tém', 'thém', 'xém',
				'ẹm', 'chẹm', 'đẹm', 'ghẹm', 'giẹm', 'hẹm', 'kẹm', 'lẹm', 'nẹm', 'nhẹm', 'rẹm', 'tẹm', 'thẹm', 'xẹm',
				'en', 'ben', 'chen', 'đen', 'ghen', 'gien', 'hen', 'ken', 'khen', 'len', 'men', 'nen', 'nghen', 'nhen', 'phen', 'quen', 'ren', 'sen', 'ten', 'then', 'ven', 'xen',
				'èn', 'bèn', 'chèn', 'đèn', 'ghèn', 'gièn', 'hèn', 'kèn', 'khèn', 'lèn', 'mèn', 'nèn', 'nghèn', 'nhèn', 'phèn', 'quèn', 'rèn', 'sèn', 'tèn', 'thèn', 'vèn', 'xèn',
				'ẻn', 'bẻn', 'chẻn', 'đẻn', 'ghẻn', 'giẻn', 'hẻn', 'kẻn', 'khẻn', 'lẻn', 'mẻn', 'nẻn', 'nghẻn', 'nhẻn', 'phẻn', 'quẻn', 'rẻn', 'sẻn', 'tẻn', 'thẻn', 'vẻn', 'xẻn',
				'ẽn', 'bẽn', 'chẽn', 'đẽn', 'ghẽn', 'giẽn', 'hẽn', 'kẽn', 'khẽn', 'lẽn', 'mẽn', 'nẽn', 'nghẽn', 'nhẽn', 'phẽn', 'quẽn', 'rẽn', 'sẽn', 'tẽn', 'thẽn', 'vẽn', 'xẽn',
				'én', 'bén', 'chén', 'đén', 'ghén', 'gién', 'hén', 'kén', 'khén', 'lén', 'mén', 'nén', 'nghén', 'nhén', 'phén', 'quén', 'rén', 'sén', 'tén', 'thén', 'vén', 'xén',
				'ẹn', 'bẹn', 'chẹn', 'đẹn', 'ghẹn', 'giẹn', 'hẹn', 'kẹn', 'khẹn', 'lẹn', 'mẹn', 'nẹn', 'nghẹn', 'nhẹn', 'phẹn', 'quẹn', 'rẹn', 'sẹn', 'tẹn', 'thẹn', 'vẹn', 'xẹn',
				'eo', 'beo', 'cheo', 'deo', 'đeo', 'gheo', 'gieo', 'heo', 'keo', 'kheo', 'leo', 'meo', 'neo', 'ngheo', 'nheo', 'pheo', 'queo', 'reo', 'seo', 'teo', 'theo', 'treo', 'veo', 'xeo',
				'èo', 'bèo', 'chèo', 'dèo', 'đèo', 'ghèo', 'gièo', 'hèo', 'kèo', 'khèo', 'lèo', 'mèo', 'nèo', 'nghèo', 'nhèo', 'phèo', 'quèo', 'rèo', 'sèo', 'tèo', 'thèo', 'trèo', 'vèo', 'xèo',
				'ẻo', 'bẻo', 'chẻo', 'dẻo', 'đẻo', 'ghẻo', 'giẻo', 'hẻo', 'kẻo', 'khẻo', 'lẻo', 'mẻo', 'nẻo', 'nghẻo', 'nhẻo', 'phẻo', 'quẻo', 'rẻo', 'sẻo', 'tẻo', 'thẻo', 'trẻo', 'vẻo', 'xẻo',
				'ẽo', 'bẽo', 'chẽo', 'dẽo', 'đẽo', 'ghẽo', 'giẽo', 'hẽo', 'kẽo', 'khẽo', 'lẽo', 'mẽo', 'nẽo', 'nghẽo', 'nhẽo', 'phẽo', 'quẽo', 'rẽo', 'sẽo', 'tẽo', 'thẽo', 'trẽo', 'vẽo', 'xẽo',
				'éo', 'béo', 'chéo', 'déo', 'đéo', 'ghéo', 'giéo', 'héo', 'kéo', 'khéo', 'léo', 'méo', 'néo', 'nghéo', 'nhéo', 'phéo', 'quéo', 'réo', 'séo', 'téo', 'théo', 'tréo', 'véo', 'xéo',
				'ẹo', 'bẹo', 'chẹo', 'dẹo', 'đẹo', 'ghẹo', 'giẹo', 'hẹo', 'kẹo', 'khẹo', 'lẹo', 'mẹo', 'nẹo', 'nghẹo', 'nhẹo', 'phẹo', 'quẹo', 'rẹo', 'sẹo', 'tẹo', 'thẹo', 'trẹo', 'vẹo', 'xẹo',
				'ep', 'bep', 'chep', 'dep', 'đep', 'ghep', 'hep', 'kep', 'khep', 'lep', 'mep', 'nep', 'nhep', 'phep', 'tep', 'thep', 'xep',
				'èp', 'bèp', 'chèp', 'dèp', 'đèp', 'ghèp', 'hèp', 'kèp', 'khèp', 'lèp', 'mèp', 'nèp', 'nhèp', 'phèp', 'tèp', 'thèp', 'xèp',
				'ẻp', 'bẻp', 'chẻp', 'dẻp', 'đẻp', 'ghẻp', 'hẻp', 'kẻp', 'khẻp', 'lẻp', 'mẻp', 'nẻp', 'nhẻp', 'phẻp', 'tẻp', 'thẻp', 'xẻp',
				'ẽp', 'bẽp', 'chẽp', 'dẽp', 'đẽp', 'ghẽp', 'hẽp', 'kẽp', 'khẽp', 'lẽp', 'mẽp', 'nẽp', 'nhẽp', 'phẽp', 'tẽp', 'thẽp', 'xẽp',
				'ép', 'bép', 'chép', 'dép', 'đép', 'ghép', 'hép', 'kép', 'khép', 'lép', 'mép', 'nép', 'nhép', 'phép', 'tép', 'thép', 'xép',
				'ẹp', 'bẹp', 'chẹp', 'dẹp', 'đẹp', 'ghẹp', 'hẹp', 'kẹp', 'khẹp', 'lẹp', 'mẹp', 'nẹp', 'nhẹp', 'phẹp', 'tẹp', 'thẹp', 'xẹp',
				'ét', 'bét', 'chét', 'dét', 'đét', 'ghét', 'hét', 'két', 'khét', 'lét', 'mét', 'nét', 'nghét', 'nhét', 'phét', 'quét', 'rét', 'sét', 'tét', 'trét', 'vét', 'xét',
				'ẹt', 'bẹt', 'chẹt', 'dẹt', 'đẹt', 'ghẹt', 'hẹt', 'kẹt', 'khẹt', 'lẹt', 'mẹt', 'nẹt', 'nghẹt', 'nhẹt', 'phẹt', 'quẹt', 'rẹt', 'sẹt', 'tẹt', 'trẹt', 'vẹt', 'xẹt',
				'êm', 'chêm', 'đêm', 'kêm', 'nêm', 'thêm', 'xêm', 'ềm', 'chềm', 'đềm', 'kềm', 'nềm', 'thềm', 'xềm', 'ểm', 'chểm', 'đểm', 'kểm', 'nểm', 'thểm', 'xểm',
				'ễm', 'chễm', 'đễm', 'kễm', 'nễm', 'thễm', 'xễm', 'ếm', 'chếm', 'đếm', 'kếm', 'nếm', 'thếm', 'xếm', 'ệm', 'chệm', 'đệm', 'kệm', 'nệm', 'thệm', 'xệm',
				'ên', 'bên', 'đên', 'hên', 'kên', 'lên', 'mên', 'nên', 'nhên', 'phên', 'quên', 'rên', 'sên', 'tên', 'trên', 'vên', 'ền', 'bền', 'đền', 'hền', 'kền', 'lền', 'mền', 'nền', 'nhền', 'phền', 'quền', 'rền', 'sền', 'tền', 'trền', 'vền',
				'ển', 'bển', 'đển', 'hển', 'kển', 'lển', 'mển', 'nển', 'nhển', 'phển', 'quển', 'rển', 'sển', 'tển', 'trển', 'vển', 'ễn', 'bễn', 'đễn', 'hễn', 'kễn', 'lễn', 'mễn', 'nễn', 'nhễn', 'phễn', 'quễn', 'rễn', 'sễn', 'tễn', 'trễn', 'vễn',
				'ến', 'bến', 'đến', 'hến', 'kến', 'lến', 'mến', 'nến', 'nhến', 'phến', 'quến', 'rến', 'sến', 'tến', 'trến', 'vến', 'ện', 'bện', 'đện', 'hện', 'kện', 'lện', 'mện', 'nện', 'nhện', 'phện', 'quện', 'rện', 'sện', 'tện', 'trện', 'vện',
				'ếp', 'bếp', 'nếp', 'rếp', 'sếp', 'thếp', 'xếp', 'ệp', 'bệp', 'nệp', 'rệp', 'sệp', 'thệp', 'xệp',
				'ết', 'bết', 'chết', 'dết', 'hết', 'kết', 'lết', 'mết', 'nết', 'quết', 'rết', 'sết', 'tết', 'vết', 'xết',
				'ệt', 'bệt', 'chệt', 'dệt', 'hệt', 'kệt', 'lệt', 'mệt', 'nệt', 'quệt', 'rệt', 'sệt', 'tệt', 'vệt', 'xệt',
				'êu', 'bêu', 'đêu', 'kêu', 'lêu', 'mêu', 'nêu', 'nghêu', 'rêu', 'sêu', 'têu', 'thêu', 'trêu',
				'ều', 'bều', 'đều', 'kều', 'lều', 'mều', 'nều', 'nghều', 'rều', 'sều', 'tều', 'thều', 'trều',
				'ểu', 'bểu', 'đểu', 'kểu', 'lểu', 'mểu', 'nểu', 'nghểu', 'rểu', 'sểu', 'tểu', 'thểu', 'trểu',
				'ễu', 'bễu', 'đễu', 'kễu', 'lễu', 'mễu', 'nễu', 'nghễu', 'rễu', 'sễu', 'tễu', 'thễu', 'trễu',
				'ếu', 'bếu', 'đếu', 'kếu', 'lếu', 'mếu', 'nếu', 'nghếu', 'rếu', 'sếu', 'tếu', 'thếu', 'trếu',
				'ệu', 'bệu', 'đệu', 'kệu', 'lệu', 'mệu', 'nệu', 'nghệu', 'rệu', 'sệu', 'tệu', 'thệu', 'trệu',
				'ia', 'bia', 'chia', 'dia', 'đia', 'kia', 'khia', 'lia', 'mia', 'nia', 'nghia', 'pia', 'phia', 'ria', 'tia', 'thia', 'via', 'xia',
				'ìa', 'bìa', 'chìa', 'dìa', 'đìa', 'kìa', 'khìa', 'lìa', 'mìa', 'nìa', 'nghìa', 'pìa', 'phìa', 'rìa', 'tìa', 'thìa', 'vìa', 'xìa',
				'ỉa', 'bỉa', 'chỉa', 'dỉa', 'đỉa', 'kỉa', 'khỉa', 'lỉa', 'mỉa', 'nỉa', 'nghỉa', 'pỉa', 'phỉa', 'rỉa', 'tỉa', 'thỉa', 'vỉa', 'xỉa',
				'ĩa', 'bĩa', 'chĩa', 'dĩa', 'đĩa', 'kĩa', 'khĩa', 'lĩa', 'mĩa', 'nĩa', 'nghĩa', 'pĩa', 'phĩa', 'rĩa', 'tĩa', 'thĩa', 'vĩa', 'xĩa',
				'ía', 'bía', 'chía', 'día', 'đía', 'kía', 'khía', 'lía', 'mía', 'nía', 'nghía', 'pía', 'phía', 'ría', 'tía', 'thía', 'vía', 'xía',
				'ịa', 'bịa', 'chịa', 'dịa', 'địa', 'kịa', 'khịa', 'lịa', 'mịa', 'nịa', 'nghịa', 'pịa', 'phịa', 'rịa', 'tịa', 'thịa', 'vịa', 'xịa',
				'ic', 'hic', 'tic', 'ìc', 'hìc', 'tìc', 'ỉc', 'hỉc', 'tỉc', 'ĩc', 'hĩc', 'tĩc', 'íc', 'híc', 'tíc', 'ịc', 'hịc', 'tịc',
				'im', 'bim', 'chim', 'dim', 'ghim', 'him', 'kim', 'lim', 'mim', 'nhim', 'phim', 'sim', 'tim', 'thim',
				'ìm', 'bìm', 'chìm', 'dìm', 'ghìm', 'hìm', 'kìm', 'lìm', 'mìm', 'nhìm', 'phìm', 'sìm', 'tìm', 'thìm',
				'ỉm', 'bỉm', 'chỉm', 'dỉm', 'ghỉm', 'hỉm', 'kỉm', 'lỉm', 'mỉm', 'nhỉm', 'phỉm', 'sỉm', 'tỉm', 'thỉm',
				'ĩm', 'bĩm', 'chĩm', 'dĩm', 'ghĩm', 'hĩm', 'kĩm', 'lĩm', 'mĩm', 'nhĩm', 'phĩm', 'sĩm', 'tĩm', 'thĩm',
				'ím', 'bím', 'chím', 'dím', 'ghím', 'hím', 'kím', 'lím', 'mím', 'nhím', 'phím', 'sím', 'tím', 'thím',
				'ịm', 'bịm', 'chịm', 'dịm', 'ghịm', 'hịm', 'kịm', 'lịm', 'mịm', 'nhịm', 'phịm', 'sịm', 'tịm', 'thịm',
				'in', 'bin', 'chin', 'kin', 'min', 'nin', 'nhin', 'phin', 'tin', 'thin', 'vin', 'xin',
				'ìn', 'bìn', 'chìn', 'kìn', 'mìn', 'nìn', 'nhìn', 'phìn', 'tìn', 'thìn', 'vìn', 'xìn',
				'ỉn', 'bỉn', 'chỉn', 'kỉn', 'mỉn', 'nỉn', 'nhỉn', 'phỉn', 'tỉn', 'thỉn', 'vỉn', 'xỉn',
				'ĩn', 'bĩn', 'chĩn', 'kĩn', 'mĩn', 'nĩn', 'nhĩn', 'phĩn', 'tĩn', 'thĩn', 'vĩn', 'xĩn',
				'ín', 'bín', 'chín', 'kín', 'mín', 'nín', 'nhín', 'phín', 'tín', 'thín', 'vín', 'xín',
				'ịn', 'bịn', 'chịn', 'kịn', 'mịn', 'nịn', 'nhịn', 'phịn', 'tịn', 'thịn', 'vịn', 'xịn',
				'íp', 'bíp', 'chíp', 'díp', 'kíp', 'míp', 'nhíp', 'síp',
				'ịp', 'bịp', 'chịp', 'dịp', 'kịp', 'mịp', 'nhịp', 'sịp',
				'it', 'bit', 'chit', 'đit', 'hit', 'kit', 'khit', 'mit', 'nit', 'nghit', 'rit', 'sit', 'tit', 'thit', 'vit', 'xit',
				'ít', 'bít', 'chít', 'đít', 'hít', 'kít', 'khít', 'mít', 'nít', 'nghít', 'rít', 'sít', 'tít', 'thít', 'vít', 'xít',
				'ịt', 'bịt', 'chịt', 'địt', 'hịt', 'kịt', 'khịt', 'mịt', 'nịt', 'nghịt', 'rịt', 'sịt', 'tịt', 'thịt', 'vịt', 'xịt',
				'iu', 'biu', 'chiu', 'diu', 'hiu', 'liu', 'niu', 'riu', 'tiu', 'thiu', 'xiu',
				'ìu', 'bìu', 'chìu', 'dìu', 'hìu', 'lìu', 'nìu', 'rìu', 'tìu', 'thìu', 'xìu',
				'ỉu', 'bỉu', 'chỉu', 'dỉu', 'hỉu', 'lỉu', 'nỉu', 'rỉu', 'tỉu', 'thỉu', 'xỉu',
				'ĩu', 'bĩu', 'chĩu', 'dĩu', 'hĩu', 'lĩu', 'nĩu', 'rĩu', 'tĩu', 'thĩu', 'xĩu',
				'íu', 'bíu', 'chíu', 'díu', 'híu', 'líu', 'níu', 'ríu', 'tíu', 'thíu', 'xíu',
				'ịu', 'bịu', 'chịu', 'dịu', 'hịu', 'lịu', 'nịu', 'rịu', 'tịu', 'thịu', 'xịu',
				'oa', 'doa', 'đoa', 'goa', 'hoa', 'khoa', 'loa', 'ngoa', 'toa', 'thoa', 'xoa',
				'òa', 'dòa', 'đòa', 'gòa', 'hòa', 'khòa', 'lòa', 'ngòa', 'tòa', 'thòa', 'xòa',
				'ỏa', 'dỏa', 'đỏa', 'gỏa', 'hỏa', 'khỏa', 'lỏa', 'ngỏa', 'tỏa', 'thỏa', 'xỏa',
				'õa', 'dõa', 'đõa', 'gõa', 'hõa', 'khõa', 'lõa', 'ngõa', 'tõa', 'thõa', 'xõa',
				'óa', 'dóa', 'đóa', 'góa', 'hóa', 'khóa', 'lóa', 'ngóa', 'tóa', 'thóa', 'xóa',
				'ọa', 'dọa', 'đọa', 'gọa', 'họa', 'khọa', 'lọa', 'ngọa', 'tọa', 'thọa', 'xọa',
				'oc', 'boc', 'coc', 'choc', 'doc', 'đoc', 'goc', 'hoc', 'khoc', 'loc', 'moc',
				'noc', 'ngoc', 'nhoc', 'phoc', 'roc', 'soc', 'toc', 'thoc', 'troc', 'voc',
				'óc', 'bóc', 'cóc', 'chóc', 'dóc', 'đóc', 'góc', 'hóc', 'khóc', 'lóc', 'móc', 'nóc', 'ngóc', 'nhóc', 'phóc', 'róc', 'sóc', 'tóc', 'thóc', 'tróc', 'vóc',
				'ọc', 'bọc', 'cọc', 'chọc', 'dọc', 'đọc', 'gọc', 'học', 'khọc', 'lọc', 'mọc', 'nọc', 'ngọc', 'nhọc', 'phọc', 'rọc', 'sọc', 'tọc', 'thọc', 'trọc', 'vọc',
				'oe', 'hoe', 'khoe', 'loe', 'ngoe', 'nhoe', 'toe', 'xoe', 'òe', 'hòe', 'khòe', 'lòe', 'ngòe', 'nhòe', 'tòe', 'xòe',
				'ỏe', 'hỏe', 'khỏe', 'lỏe', 'ngỏe', 'nhỏe', 'tỏe', 'xỏe', 'õe', 'hõe', 'khõe', 'lõe', 'ngõe', 'nhõe', 'tõe', 'xõe',
				'óe', 'hóe', 'khóe', 'lóe', 'ngóe', 'nhóe', 'tóe', 'xóe', 'ọe', 'họe', 'khọe', 'lọe', 'ngọe', 'nhọe', 'tọe', 'xọe',
				'oi', 'boi', 'coi', 'choi', 'doi', 'đoi', 'goi', 'gioi', 'hoi', 'khoi', 'loi', 'moi', 'noi', 'ngoi', 'nhoi', 'roi', 'soi', 'toi', 'thoi', 'troi', 'voi', 'xoi',
				'òi', 'bòi', 'còi', 'chòi', 'dòi', 'đòi', 'gòi', 'giòi', 'hòi', 'khòi', 'lòi', 'mòi', 'nòi', 'ngòi', 'nhòi', 'ròi', 'sòi', 'tòi', 'thòi', 'tròi', 'vòi', 'xòi',
				'ỏi', 'bỏi', 'cỏi', 'chỏi', 'dỏi', 'đỏi', 'gỏi', 'giỏi', 'hỏi', 'khỏi', 'lỏi', 'mỏi', 'nỏi', 'ngỏi', 'nhỏi', 'rỏi', 'sỏi', 'tỏi', 'thỏi', 'trỏi', 'vỏi', 'xỏi',
				'õi', 'bõi', 'cõi', 'chõi', 'dõi', 'đõi', 'gõi', 'giõi', 'hõi', 'khõi', 'lõi', 'mõi', 'nõi', 'ngõi', 'nhõi', 'rõi', 'sõi', 'tõi', 'thõi', 'trõi', 'või', 'xõi',
				'ói', 'bói', 'cói', 'chói', 'dói', 'đói', 'gói', 'giói', 'hói', 'khói', 'lói', 'mói', 'nói', 'ngói', 'nhói', 'rói', 'sói', 'tói', 'thói', 'trói', 'vói', 'xói',
				'ọi', 'bọi', 'cọi', 'chọi', 'dọi', 'đọi', 'gọi', 'giọi', 'họi', 'khọi', 'lọi', 'mọi', 'nọi', 'ngọi', 'nhọi', 'rọi', 'sọi', 'tọi', 'thọi', 'trọi', 'vọi', 'xọi',
				'om', 'bom', 'com', 'chom', 'dom', 'đom', 'gom', 'hom', 'khom', 'lom', 'nom', 'ngom', 'nhom', 'phom', 'rom', 'som', 'tom', 'vom', 'xom',
				'òm', 'bòm', 'còm', 'chòm', 'dòm', 'đòm', 'gòm', 'hòm', 'khòm', 'lòm', 'nòm', 'ngòm', 'nhòm', 'phòm', 'ròm', 'sòm', 'tòm', 'vòm', 'xòm',
				'ỏm', 'bỏm', 'cỏm', 'chỏm', 'dỏm', 'đỏm', 'gỏm', 'hỏm', 'khỏm', 'lỏm', 'nỏm', 'ngỏm', 'nhỏm', 'phỏm', 'rỏm', 'sỏm', 'tỏm', 'vỏm', 'xỏm',
				'õm', 'bõm', 'cõm', 'chõm', 'dõm', 'đõm', 'gõm', 'hõm', 'khõm', 'lõm', 'nõm', 'ngõm', 'nhõm', 'phõm', 'rõm', 'sõm', 'tõm', 'võm', 'xõm',
				'óm', 'bóm', 'cóm', 'chóm', 'dóm', 'đóm', 'góm', 'hóm', 'khóm', 'lóm', 'nóm', 'ngóm', 'nhóm', 'phóm', 'róm', 'sóm', 'tóm', 'vóm', 'xóm',
				'ọm', 'bọm', 'cọm', 'chọm', 'dọm', 'đọm', 'gọm', 'họm', 'khọm', 'lọm', 'nọm', 'ngọm', 'nhọm', 'phọm', 'rọm', 'sọm', 'tọm', 'vọm', 'xọm',
				'on', 'bon', 'con', 'chon', 'đon', 'gon', 'gion', 'hon', 'lon', 'mon', 'non', 'ngon', 'nhon', 'ron', 'son', 'ton', 'thon', 'tron', 'von',
				'òn', 'bòn', 'còn', 'chòn', 'đòn', 'gòn', 'giòn', 'hòn', 'lòn', 'mòn', 'nòn', 'ngòn', 'nhòn', 'ròn', 'sòn', 'tòn', 'thòn', 'tròn', 'vòn',
				'ỏn', 'bỏn', 'cỏn', 'chỏn', 'đỏn', 'gỏn', 'giỏn', 'hỏn', 'lỏn', 'mỏn', 'nỏn', 'ngỏn', 'nhỏn', 'rỏn', 'sỏn', 'tỏn', 'thỏn', 'trỏn', 'vỏn',
				'õn', 'bõn', 'cõn', 'chõn', 'đõn', 'gõn', 'giõn', 'hõn', 'lõn', 'mõn', 'nõn', 'ngõn', 'nhõn', 'rõn', 'sõn', 'tõn', 'thõn', 'trõn', 'võn',
				'ón', 'bón', 'cón', 'chón', 'đón', 'gón', 'gión', 'hón', 'lón', 'món', 'nón', 'ngón', 'nhón', 'rón', 'són', 'tón', 'thón', 'trón', 'vón',
				'ọn', 'bọn', 'cọn', 'chọn', 'đọn', 'gọn', 'giọn', 'họn', 'lọn', 'mọn', 'nọn', 'ngọn', 'nhọn', 'rọn', 'sọn', 'tọn', 'thọn', 'trọn', 'vọn',
				'op', 'bop', 'cop', 'chop', 'gop', 'hop', 'mop', 'ngop', 'nhop', 'thop', 'òp', 'bòp', 'còp', 'chòp', 'gòp', 'hòp', 'mòp', 'ngòp', 'nhòp', 'thòp',
				'óp', 'bóp', 'cóp', 'chóp', 'góp', 'hóp', 'móp', 'ngóp', 'nhóp', 'thóp', 'ọp', 'bọp', 'cọp', 'chọp', 'gọp', 'họp', 'mọp', 'ngọp', 'nhọp', 'thọp',
				'ót', 'bót', 'cót', 'chót', 'đót', 'giót', 'khót', 'lót', 'mót', 'nót', 'ngót', 'nhót', 'rót', 'sót', 'tót', 'thót', 'trót', 'vót', 'xót',
				'ọt', 'bọt', 'cọt', 'chọt', 'đọt', 'giọt', 'khọt', 'lọt', 'mọt', 'nọt', 'ngọt', 'nhọt', 'rọt', 'sọt', 'tọt', 'thọt', 'trọt', 'vọt', 'xọt',
				'ốc', 'bốc', 'cốc', 'chốc', 'dốc', 'đốc', 'gốc', 'giốc', 'hốc', 'khốc', 'lốc', 'mốc', 'nốc', 'ngốc', 'phốc', 'quốc', 'rốc', 'sốc', 'tốc', 'thốc', 'xốc',
				'ộc', 'bộc', 'cộc', 'chộc', 'dộc', 'độc', 'gộc', 'giộc', 'hộc', 'khộc', 'lộc', 'mộc', 'nộc', 'ngộc', 'phộc', 'quộc', 'rộc', 'sộc', 'tộc', 'thộc', 'xộc',
				'ôi', 'bôi', 'côi', 'chôi', 'dôi', 'đôi', 'gôi', 'hôi', 'khôi', 'lôi', 'môi', 'nôi', 'ngôi', 'nhôi', 'phôi', 'rôi', 'sôi', 'tôi', 'thôi', 'trôi', 'vôi', 'xôi',
				'ồi', 'bồi', 'cồi', 'chồi', 'dồi', 'đồi', 'gồi', 'hồi', 'khồi', 'lồi', 'mồi', 'nồi', 'ngồi', 'nhồi', 'phồi', 'rồi', 'sồi', 'tồi', 'thồi', 'trồi', 'vồi', 'xồi',
				'ổi', 'bổi', 'cổi', 'chổi', 'dổi', 'đổi', 'gổi', 'hổi', 'khổi', 'lổi', 'mổi', 'nổi', 'ngổi', 'nhổi', 'phổi', 'rổi', 'sổi', 'tổi', 'thổi', 'trổi', 'vổi', 'xổi',
				'ỗi', 'bỗi', 'cỗi', 'chỗi', 'dỗi', 'đỗi', 'gỗi', 'hỗi', 'khỗi', 'lỗi', 'mỗi', 'nỗi', 'ngỗi', 'nhỗi', 'phỗi', 'rỗi', 'sỗi', 'tỗi', 'thỗi', 'trỗi', 'vỗi', 'xỗi',
				'ối', 'bối', 'cối', 'chối', 'dối', 'đối', 'gối', 'hối', 'khối', 'lối', 'mối', 'nối', 'ngối', 'nhối', 'phối', 'rối', 'sối', 'tối', 'thối', 'trối', 'vối', 'xối',
				'ội', 'bội', 'cội', 'chội', 'dội', 'đội', 'gội', 'hội', 'khội', 'lội', 'mội', 'nội', 'ngội', 'nhội', 'phội', 'rội', 'sội', 'tội', 'thội', 'trội', 'vội', 'xội',
				'ôm', 'côm', 'chôm', 'đôm', 'gôm', 'hôm', 'nôm', 'nhôm', 'tôm', 'trôm', 'xôm', 'ồm', 'cồm', 'chồm', 'đồm', 'gồm', 'hồm', 'nồm', 'nhồm', 'tồm', 'trồm', 'xồm',
				'ổm', 'cổm', 'chổm', 'đổm', 'gổm', 'hổm', 'nổm', 'nhổm', 'tổm', 'trổm', 'xổm', 'ỗm', 'cỗm', 'chỗm', 'đỗm', 'gỗm', 'hỗm', 'nỗm', 'nhỗm', 'tỗm', 'trỗm', 'xỗm',
				'ốm', 'cốm', 'chốm', 'đốm', 'gốm', 'hốm', 'nốm', 'nhốm', 'tốm', 'trốm', 'xốm', 'ộm', 'cộm', 'chộm', 'độm', 'gộm', 'hộm', 'nộm', 'nhộm', 'tộm', 'trộm', 'xộm',
				'ôn', 'bôn', 'côn', 'chôn', 'dôn', 'đôn', 'gôn', 'hôn', 'khôn', 'lôn', 'môn', 'nôn', 'ngôn', 'nhôn', 'rôn', 'tôn', 'thôn', 'trôn', 'vôn', 'xôn',
				'ồn', 'bồn', 'cồn', 'chồn', 'dồn', 'đồn', 'gồn', 'hồn', 'khồn', 'lồn', 'mồn', 'nồn', 'ngồn', 'nhồn', 'rồn', 'tồn', 'thồn', 'trồn', 'vồn', 'xồn',
				'ổn', 'bổn', 'cổn', 'chổn', 'dổn', 'đổn', 'gổn', 'hổn', 'khổn', 'lổn', 'mổn', 'nổn', 'ngổn', 'nhổn', 'rổn', 'tổn', 'thổn', 'trổn', 'vổn', 'xổn',
				'ỗn', 'bỗn', 'cỗn', 'chỗn', 'dỗn', 'đỗn', 'gỗn', 'hỗn', 'khỗn', 'lỗn', 'mỗn', 'nỗn', 'ngỗn', 'nhỗn', 'rỗn', 'tỗn', 'thỗn', 'trỗn', 'vỗn', 'xỗn',
				'ốn', 'bốn', 'cốn', 'chốn', 'dốn', 'đốn', 'gốn', 'hốn', 'khốn', 'lốn', 'mốn', 'nốn', 'ngốn', 'nhốn', 'rốn', 'tốn', 'thốn', 'trốn', 'vốn', 'xốn',
				'ộn', 'bộn', 'cộn', 'chộn', 'dộn', 'độn', 'gộn', 'hộn', 'khộn', 'lộn', 'mộn', 'nộn', 'ngộn', 'nhộn', 'rộn', 'tộn', 'thộn', 'trộn', 'vộn', 'xộn',
				'ôp', 'bôp', 'côp', 'chôp', 'đôp', 'gôp', 'lôp', 'nôp', 'ngôp', 'rôp', 'sôp', 'tôp', 'thôp', 'xôp', 'ồp', 'bồp', 'cồp', 'chồp', 'đồp', 'gồp', 'lồp', 'nồp', 'ngồp', 'rồp', 'sồp', 'tồp', 'thồp', 'xồp',
				'ốp', 'bốp', 'cốp', 'chốp', 'đốp', 'gốp', 'lốp', 'nốp', 'ngốp', 'rốp', 'sốp', 'tốp', 'thốp', 'xốp', 'ộp', 'bộp', 'cộp', 'chộp', 'độp', 'gộp', 'lộp', 'nộp', 'ngộp', 'rộp', 'sộp', 'tộp', 'thộp', 'xộp',
				'ốt', 'bốt', 'cốt', 'chốt', 'dốt', 'đốt', 'gốt', 'hốt', 'lốt', 'mốt', 'nốt', 'ngốt', 'nhốt', 'phốt', 'rốt', 'sốt', 'tốt', 'thốt', 'xốt',
				'ột', 'bột', 'cột', 'chột', 'dột', 'đột', 'gột', 'hột', 'lột', 'một', 'nột', 'ngột', 'nhột', 'phột', 'rột', 'sột', 'tột', 'thột', 'xột',
				'ơi', 'bơi', 'cơi', 'chơi', 'dơi', 'đơi', 'gơi', 'giơi', 'hơi', 'khơi', 'lơi', 'mơi', 'nơi', 'ngơi', 'phơi', 'quơi', 'rơi', 'sơi', 'tơi', 'thơi', 'trơi', 'vơi', 'xơi',
				'ời', 'bời', 'cời', 'chời', 'dời', 'đời', 'gời', 'giời', 'hời', 'khời', 'lời', 'mời', 'nời', 'ngời', 'phời', 'quời', 'rời', 'sời', 'tời', 'thời', 'trời', 'vời', 'xời',
				'ởi', 'bởi', 'cởi', 'chởi', 'dởi', 'đởi', 'gởi', 'giởi', 'hởi', 'khởi', 'lởi', 'mởi', 'nởi', 'ngởi', 'phởi', 'quởi', 'rởi', 'sởi', 'tởi', 'thởi', 'trởi', 'vởi', 'xởi',
				'ỡi', 'bỡi', 'cỡi', 'chỡi', 'dỡi', 'đỡi', 'gỡi', 'giỡi', 'hỡi', 'khỡi', 'lỡi', 'mỡi', 'nỡi', 'ngỡi', 'phỡi', 'quỡi', 'rỡi', 'sỡi', 'tỡi', 'thỡi', 'trỡi', 'vỡi', 'xỡi',
				'ới', 'bới', 'cới', 'chới', 'dới', 'đới', 'gới', 'giới', 'hới', 'khới', 'lới', 'mới', 'nới', 'ngới', 'phới', 'quới', 'rới', 'sới', 'tới', 'thới', 'trới', 'với', 'xới',
				'ợi', 'bợi', 'cợi', 'chợi', 'dợi', 'đợi', 'gợi', 'giợi', 'hợi', 'khợi', 'lợi', 'mợi', 'nợi', 'ngợi', 'phợi', 'quợi', 'rợi', 'sợi', 'tợi', 'thợi', 'trợi', 'vợi', 'xợi',
				'ơm', 'bơm', 'cơm', 'chơm', 'đơm', 'nơm', 'rơm', 'sơm', 'thơm', 'ờm', 'bờm', 'cờm', 'chờm', 'đờm', 'nờm', 'rờm', 'sờm', 'thờm', 'ởm', 'bởm', 'cởm', 'chởm', 'đởm', 'nởm', 'rởm', 'sởm', 'thởm',
				'ỡm', 'bỡm', 'cỡm', 'chỡm', 'đỡm', 'nỡm', 'rỡm', 'sỡm', 'thỡm', 'ớm', 'bớm', 'cớm', 'chớm', 'đớm', 'nớm', 'rớm', 'sớm', 'thớm', 'ợm', 'bợm', 'cợm', 'chợm', 'đợm', 'nợm', 'rợm', 'sợm', 'thợm',
				'ơn', 'bơn', 'cơn', 'chơn', 'đơn', 'gơn', 'giơn', 'hơn', 'lơn', 'mơn', 'nhơn', 'rơn', 'sơn', 'tơn', 'trơn', 'ờn', 'bờn', 'cờn', 'chờn', 'đờn', 'gờn', 'giờn', 'hờn', 'lờn', 'mờn', 'nhờn', 'rờn', 'sờn', 'tờn', 'trờn',
				'ởn', 'bởn', 'cởn', 'chởn', 'đởn', 'gởn', 'giởn', 'hởn', 'lởn', 'mởn', 'nhởn', 'rởn', 'sởn', 'tởn', 'trởn', 'ỡn', 'bỡn', 'cỡn', 'chỡn', 'đỡn', 'gỡn', 'giỡn', 'hỡn', 'lỡn', 'mỡn', 'nhỡn', 'rỡn', 'sỡn', 'tỡn', 'trỡn',
				'ớn', 'bớn', 'cớn', 'chớn', 'đớn', 'gớn', 'giớn', 'hớn', 'lớn', 'mớn', 'nhớn', 'rớn', 'sớn', 'tớn', 'trớn', 'ợn', 'bợn', 'cợn', 'chợn', 'đợn', 'gợn', 'giợn', 'hợn', 'lợn', 'mợn', 'nhợn', 'rợn', 'sợn', 'tợn', 'trợn',
				'ớp', 'bớp', 'chớp', 'dớp', 'đớp', 'hớp', 'khớp', 'lớp', 'nớp', 'ngớp', 'rớp',
				'ợp', 'bợp', 'chợp', 'dợp', 'đợp', 'hợp', 'khợp', 'lợp', 'nợp', 'ngợp', 'rợp',
				'ơt', 'bơt', 'cơt', 'chơt', 'dơt', 'đơt', 'hơt', 'lơt', 'ngơt', 'nhơt', 'phơt', 'quơt', 'rơt', 'sơt', 'thơt', 'vơt',
				'ờt', 'bờt', 'cờt', 'chờt', 'dờt', 'đờt', 'hờt', 'lờt', 'ngờt', 'nhờt', 'phờt', 'quờt', 'rờt', 'sờt', 'thờt', 'vờt',
				'ởt', 'bởt', 'cởt', 'chởt', 'dởt', 'đởt', 'hởt', 'lởt', 'ngởt', 'nhởt', 'phởt', 'quởt', 'rởt', 'sởt', 'thởt', 'vởt',
				'ỡt', 'bỡt', 'cỡt', 'chỡt', 'dỡt', 'đỡt', 'hỡt', 'lỡt', 'ngỡt', 'nhỡt', 'phỡt', 'quỡt', 'rỡt', 'sỡt', 'thỡt', 'vỡt',
				'ớt', 'bớt', 'cớt', 'chớt', 'dớt', 'đớt', 'hớt', 'lớt', 'ngớt', 'nhớt', 'phớt', 'quớt', 'rớt', 'sớt', 'thớt', 'vớt',
				'ợt', 'bợt', 'cợt', 'chợt', 'dợt', 'đợt', 'hợt', 'lợt', 'ngợt', 'nhợt', 'phợt', 'quợt', 'rợt', 'sợt', 'thợt', 'vợt',
				'ua', 'bua', 'cua', 'chua', 'dua', 'đua', 'hua', 'khua', 'lua', 'mua', 'nua', 'nhua', 'rua', 'sua', 'tua', 'thua', 'vua', 'xua',
				'ùa', 'bùa', 'cùa', 'chùa', 'dùa', 'đùa', 'hùa', 'khùa', 'lùa', 'mùa', 'nùa', 'nhùa', 'rùa', 'sùa', 'tùa', 'thùa', 'vùa', 'xùa',
				'ủa', 'bủa', 'của', 'chủa', 'dủa', 'đủa', 'hủa', 'khủa', 'lủa', 'mủa', 'nủa', 'nhủa', 'rủa', 'sủa', 'tủa', 'thủa', 'vủa', 'xủa',
				'ũa', 'bũa', 'cũa', 'chũa', 'dũa', 'đũa', 'hũa', 'khũa', 'lũa', 'mũa', 'nũa', 'nhũa', 'rũa', 'sũa', 'tũa', 'thũa', 'vũa', 'xũa',
				'úa', 'búa', 'cúa', 'chúa', 'dúa', 'đúa', 'húa', 'khúa', 'lúa', 'múa', 'núa', 'nhúa', 'rúa', 'súa', 'túa', 'thúa', 'vúa', 'xúa',
				'ụa', 'bụa', 'cụa', 'chụa', 'dụa', 'đụa', 'hụa', 'khụa', 'lụa', 'mụa', 'nụa', 'nhụa', 'rụa', 'sụa', 'tụa', 'thụa', 'vụa', 'xụa',
				'uc', 'buc', 'cuc', 'chuc', 'duc', 'đuc', 'guc', 'giuc', 'huc', 'khuc', 'luc', 'muc', 'nuc', 'nguc', 'nhuc', 'phuc', 'ruc', 'suc', 'tuc', 'thuc', 'truc', 'xuc',
				'ùc', 'bùc', 'cùc', 'chùc', 'dùc', 'đùc', 'gùc', 'giùc', 'hùc', 'khùc', 'lùc', 'mùc', 'nùc', 'ngùc', 'nhùc', 'phùc', 'rùc', 'sùc', 'tùc', 'thùc', 'trùc', 'xùc',
				'ủc', 'bủc', 'củc', 'chủc', 'dủc', 'đủc', 'gủc', 'giủc', 'hủc', 'khủc', 'lủc', 'mủc', 'nủc', 'ngủc', 'nhủc', 'phủc', 'rủc', 'sủc', 'tủc', 'thủc', 'trủc', 'xủc',
				'ũc', 'bũc', 'cũc', 'chũc', 'dũc', 'đũc', 'gũc', 'giũc', 'hũc', 'khũc', 'lũc', 'mũc', 'nũc', 'ngũc', 'nhũc', 'phũc', 'rũc', 'sũc', 'tũc', 'thũc', 'trũc', 'xũc',
				'úc', 'búc', 'cúc', 'chúc', 'dúc', 'đúc', 'gúc', 'giúc', 'húc', 'khúc', 'lúc', 'múc', 'núc', 'ngúc', 'nhúc', 'phúc', 'rúc', 'súc', 'túc', 'thúc', 'trúc', 'xúc',
				'ục', 'bục', 'cục', 'chục', 'dục', 'đục', 'gục', 'giục', 'hục', 'khục', 'lục', 'mục', 'nục', 'ngục', 'nhục', 'phục', 'rục', 'sục', 'tục', 'thục', 'trục', 'xục',
				'uê', 'duê', 'huê', 'khuê', 'tuê', 'thuê', 'vuê', 'xuê', 'uề', 'duề', 'huề', 'khuề', 'tuề', 'thuề', 'vuề', 'xuề',
				'uể', 'duể', 'huể', 'khuể', 'tuể', 'thuể', 'vuể', 'xuể', 'uễ', 'duễ', 'huễ', 'khuễ', 'tuễ', 'thuễ', 'vuễ', 'xuễ',
				'uế', 'duế', 'huế', 'khuế', 'tuế', 'thuế', 'vuế', 'xuế', 'uệ', 'duệ', 'huệ', 'khuệ', 'tuệ', 'thuệ', 'vuệ', 'xuệ',
				'ui', 'bui', 'cui', 'chui', 'dui', 'đui', 'gui', 'hui', 'khui', 'lui', 'mui', 'nui', 'nhui', 'phui', 'rui', 'sui', 'tui', 'thui', 'trui', 'vui', 'xui',
				'ùi', 'bùi', 'cùi', 'chùi', 'dùi', 'đùi', 'gùi', 'hùi', 'khùi', 'lùi', 'mùi', 'nùi', 'nhùi', 'phùi', 'rùi', 'sùi', 'tùi', 'thùi', 'trùi', 'vùi', 'xùi',
				'ủi', 'bủi', 'củi', 'chủi', 'dủi', 'đủi', 'gủi', 'hủi', 'khủi', 'lủi', 'mủi', 'nủi', 'nhủi', 'phủi', 'rủi', 'sủi', 'tủi', 'thủi', 'trủi', 'vủi', 'xủi',
				'ũi', 'bũi', 'cũi', 'chũi', 'dũi', 'đũi', 'gũi', 'hũi', 'khũi', 'lũi', 'mũi', 'nũi', 'nhũi', 'phũi', 'rũi', 'sũi', 'tũi', 'thũi', 'trũi', 'vũi', 'xũi',
				'úi', 'búi', 'cúi', 'chúi', 'dúi', 'đúi', 'gúi', 'húi', 'khúi', 'lúi', 'múi', 'núi', 'nhúi', 'phúi', 'rúi', 'súi', 'túi', 'thúi', 'trúi', 'vúi', 'xúi',
				'ụi', 'bụi', 'cụi', 'chụi', 'dụi', 'đụi', 'gụi', 'hụi', 'khụi', 'lụi', 'mụi', 'nụi', 'nhụi', 'phụi', 'rụi', 'sụi', 'tụi', 'thụi', 'trụi', 'vụi', 'xụi',
				'um', 'bum', 'cum', 'chum', 'dum', 'đum', 'gium', 'khum', 'lum', 'mum', 'num', 'ngum', 'nhum', 'sum', 'tum', 'trum', 'xum',
				'ùm', 'bùm', 'cùm', 'chùm', 'dùm', 'đùm', 'giùm', 'khùm', 'lùm', 'mùm', 'nùm', 'ngùm', 'nhùm', 'sùm', 'tùm', 'trùm', 'xùm',
				'ủm', 'bủm', 'củm', 'chủm', 'dủm', 'đủm', 'giủm', 'khủm', 'lủm', 'mủm', 'nủm', 'ngủm', 'nhủm', 'sủm', 'tủm', 'trủm', 'xủm',
				'ũm', 'bũm', 'cũm', 'chũm', 'dũm', 'đũm', 'giũm', 'khũm', 'lũm', 'mũm', 'nũm', 'ngũm', 'nhũm', 'sũm', 'tũm', 'trũm', 'xũm',
				'úm', 'búm', 'cúm', 'chúm', 'dúm', 'đúm', 'giúm', 'khúm', 'lúm', 'múm', 'núm', 'ngúm', 'nhúm', 'súm', 'túm', 'trúm', 'xúm',
				'ụm', 'bụm', 'cụm', 'chụm', 'dụm', 'đụm', 'giụm', 'khụm', 'lụm', 'mụm', 'nụm', 'ngụm', 'nhụm', 'sụm', 'tụm', 'trụm', 'xụm',
				'un', 'bun', 'cun', 'chun', 'đun', 'giun', 'hun', 'lun', 'mun', 'ngun', 'nhun', 'phun', 'run', 'sun', 'tun', 'thun', 'vun',
				'ùn', 'bùn', 'cùn', 'chùn', 'đùn', 'giùn', 'hùn', 'lùn', 'mùn', 'ngùn', 'nhùn', 'phùn', 'rùn', 'sùn', 'tùn', 'thùn', 'vùn',
				'ủn', 'bủn', 'củn', 'chủn', 'đủn', 'giủn', 'hủn', 'lủn', 'mủn', 'ngủn', 'nhủn', 'phủn', 'rủn', 'sủn', 'tủn', 'thủn', 'vủn',
				'ũn', 'bũn', 'cũn', 'chũn', 'đũn', 'giũn', 'hũn', 'lũn', 'mũn', 'ngũn', 'nhũn', 'phũn', 'rũn', 'sũn', 'tũn', 'thũn', 'vũn',
				'ún', 'bún', 'cún', 'chún', 'đún', 'giún', 'hún', 'lún', 'mún', 'ngún', 'nhún', 'phún', 'rún', 'sún', 'tún', 'thún', 'vún',
				'ụn', 'bụn', 'cụn', 'chụn', 'đụn', 'giụn', 'hụn', 'lụn', 'mụn', 'ngụn', 'nhụn', 'phụn', 'rụn', 'sụn', 'tụn', 'thụn', 'vụn',
				'up', 'bup', 'cup', 'chup', 'giup', 'hup', 'lup', 'mup', 'nup', 'ngup', 'rup', 'sup', 'tup', 'thup',
				'ùp', 'bùp', 'cùp', 'chùp', 'giùp', 'hùp', 'lùp', 'mùp', 'nùp', 'ngùp', 'rùp', 'sùp', 'tùp', 'thùp',
				'ủp', 'bủp', 'củp', 'chủp', 'giủp', 'hủp', 'lủp', 'mủp', 'nủp', 'ngủp', 'rủp', 'sủp', 'tủp', 'thủp',
				'ũp', 'bũp', 'cũp', 'chũp', 'giũp', 'hũp', 'lũp', 'mũp', 'nũp', 'ngũp', 'rũp', 'sũp', 'tũp', 'thũp',
				'úp', 'búp', 'cúp', 'chúp', 'giúp', 'húp', 'lúp', 'múp', 'núp', 'ngúp', 'rúp', 'súp', 'túp', 'thúp',
				'ụp', 'bụp', 'cụp', 'chụp', 'giụp', 'hụp', 'lụp', 'mụp', 'nụp', 'ngụp', 'rụp', 'sụp', 'tụp', 'thụp',
				'uơ', 'thuơ', 'uờ', 'thuờ', 'uở', 'thuở', 'uỡ', 'thuỡ', 'uớ', 'thuớ', 'uợ', 'thuợ',
				'ut', 'but', 'cut', 'chut', 'hut', 'lut', 'mut', 'nut', 'ngut', 'phut', 'rut', 'sut', 'tut', 'thut', 'trut', 'vut',
				'ùt', 'bùt', 'cùt', 'chùt', 'hùt', 'lùt', 'mùt', 'nùt', 'ngùt', 'phùt', 'rùt', 'sùt', 'tùt', 'thùt', 'trùt', 'vùt',
				'ủt', 'bủt', 'củt', 'chủt', 'hủt', 'lủt', 'mủt', 'nủt', 'ngủt', 'phủt', 'rủt', 'sủt', 'tủt', 'thủt', 'trủt', 'vủt',
				'ũt', 'bũt', 'cũt', 'chũt', 'hũt', 'lũt', 'mũt', 'nũt', 'ngũt', 'phũt', 'rũt', 'sũt', 'tũt', 'thũt', 'trũt', 'vũt',
				'út', 'bút', 'cút', 'chút', 'hút', 'lút', 'mút', 'nút', 'ngút', 'phút', 'rút', 'sút', 'tút', 'thút', 'trút', 'vút',
				'ụt', 'bụt', 'cụt', 'chụt', 'hụt', 'lụt', 'mụt', 'nụt', 'ngụt', 'phụt', 'rụt', 'sụt', 'tụt', 'thụt', 'trụt', 'vụt',
				'uy', 'duy', 'huy', 'khuy', 'luy', 'nguy', 'nhuy', 'phuy', 'suy', 'tuy', 'thuy', 'truy', 'xuy',
				'ùy', 'dùy', 'hùy', 'khùy', 'lùy', 'ngùy', 'nhùy', 'phùy', 'sùy', 'tùy', 'thùy', 'trùy', 'xùy',
				'ủy', 'dủy', 'hủy', 'khủy', 'lủy', 'ngủy', 'nhủy', 'phủy', 'sủy', 'tủy', 'thủy', 'trủy', 'xủy',
				'ũy', 'dũy', 'hũy', 'khũy', 'lũy', 'ngũy', 'nhũy', 'phũy', 'sũy', 'tũy', 'thũy', 'trũy', 'xũy',
				'úy', 'dúy', 'húy', 'khúy', 'lúy', 'ngúy', 'nhúy', 'phúy', 'súy', 'túy', 'thúy', 'trúy', 'xúy',
				'ụy', 'dụy', 'hụy', 'khụy', 'lụy', 'ngụy', 'nhụy', 'phụy', 'sụy', 'tụy', 'thụy', 'trụy', 'xụy',
				'ưa', 'bưa', 'cưa', 'chưa', 'dưa', 'đưa', 'giưa', 'hưa', 'khưa', 'lưa', 'mưa', 'nưa', 'ngưa', 'nhưa', 'rưa', 'sưa', 'tưa', 'thưa', 'trưa', 'vưa', 'xưa',
				'ừa', 'bừa', 'cừa', 'chừa', 'dừa', 'đừa', 'giừa', 'hừa', 'khừa', 'lừa', 'mừa', 'nừa', 'ngừa', 'nhừa', 'rừa', 'sừa', 'từa', 'thừa', 'trừa', 'vừa', 'xừa',
				'ửa', 'bửa', 'cửa', 'chửa', 'dửa', 'đửa', 'giửa', 'hửa', 'khửa', 'lửa', 'mửa', 'nửa', 'ngửa', 'nhửa', 'rửa', 'sửa', 'tửa', 'thửa', 'trửa', 'vửa', 'xửa',
				'ữa', 'bữa', 'cữa', 'chữa', 'dữa', 'đữa', 'giữa', 'hữa', 'khữa', 'lữa', 'mữa', 'nữa', 'ngữa', 'nhữa', 'rữa', 'sữa', 'tữa', 'thữa', 'trữa', 'vữa', 'xữa',
				'ứa', 'bứa', 'cứa', 'chứa', 'dứa', 'đứa', 'giứa', 'hứa', 'khứa', 'lứa', 'mứa', 'nứa', 'ngứa', 'nhứa', 'rứa', 'sứa', 'tứa', 'thứa', 'trứa', 'vứa', 'xứa',
				'ựa', 'bựa', 'cựa', 'chựa', 'dựa', 'đựa', 'giựa', 'hựa', 'khựa', 'lựa', 'mựa', 'nựa', 'ngựa', 'nhựa', 'rựa', 'sựa', 'tựa', 'thựa', 'trựa', 'vựa', 'xựa',
				'ưc', 'bưc', 'cưc', 'chưc', 'đưc', 'hưc', 'lưc', 'mưc', 'nưc', 'ngưc', 'nhưc', 'phưc', 'rưc', 'sưc', 'tưc', 'thưc', 'trưc', 'vưc', 'xưc',
				'ừc', 'bừc', 'cừc', 'chừc', 'đừc', 'hừc', 'lừc', 'mừc', 'nừc', 'ngừc', 'nhừc', 'phừc', 'rừc', 'sừc', 'từc', 'thừc', 'trừc', 'vừc', 'xừc',
				'ửc', 'bửc', 'cửc', 'chửc', 'đửc', 'hửc', 'lửc', 'mửc', 'nửc', 'ngửc', 'nhửc', 'phửc', 'rửc', 'sửc', 'tửc', 'thửc', 'trửc', 'vửc', 'xửc',
				'ữc', 'bữc', 'cữc', 'chữc', 'đữc', 'hữc', 'lữc', 'mữc', 'nữc', 'ngữc', 'nhữc', 'phữc', 'rữc', 'sữc', 'tữc', 'thữc', 'trữc', 'vữc', 'xữc',
				'ức', 'bức', 'cức', 'chức', 'đức', 'hức', 'lức', 'mức', 'nức', 'ngức', 'nhức', 'phức', 'rức', 'sức', 'tức', 'thức', 'trức', 'vức', 'xức',
				'ực', 'bực', 'cực', 'chực', 'đực', 'hực', 'lực', 'mực', 'nực', 'ngực', 'nhực', 'phực', 'rực', 'sực', 'tực', 'thực', 'trực', 'vực', 'xực',
				'ưi', 'cưi', 'chưi', 'gưi', 'ngưi', 'ừi', 'cừi', 'chừi', 'gừi', 'ngừi',
				'ửi', 'cửi', 'chửi', 'gửi', 'ngửi', 'ữi', 'cữi', 'chữi', 'gữi', 'ngữi',
				'ứi', 'cứi', 'chứi', 'gứi', 'ngứi', 'ựi', 'cựi', 'chựi', 'gựi', 'ngựi',
				'ưm', 'hưm', 'ngưm', 'ừm', 'hừm', 'ngừm', 'ửm', 'hửm', 'ngửm',
				'ữm', 'hữm', 'ngữm', 'ứm', 'hứm', 'ngứm', 'ựm', 'hựm', 'ngựm',
				'ưt', 'bưt', 'cưt', 'dưt', 'đưt', 'giưt', 'mưt', 'nưt', 'nhưt', 'sưt', 'vưt', 'xưt',
				'ừt', 'bừt', 'cừt', 'dừt', 'đừt', 'giừt', 'mừt', 'nừt', 'nhừt', 'sừt', 'vừt', 'xừt',
				'ửt', 'bửt', 'cửt', 'dửt', 'đửt', 'giửt', 'mửt', 'nửt', 'nhửt', 'sửt', 'vửt', 'xửt',
				'ữt', 'bữt', 'cữt', 'dữt', 'đữt', 'giữt', 'mữt', 'nữt', 'nhữt', 'sữt', 'vữt', 'xữt',
				'ứt', 'bứt', 'cứt', 'dứt', 'đứt', 'giứt', 'mứt', 'nứt', 'nhứt', 'sứt', 'vứt', 'xứt',
				'ựt', 'bựt', 'cựt', 'dựt', 'đựt', 'giựt', 'mựt', 'nựt', 'nhựt', 'sựt', 'vựt', 'xựt',
				'ưu', 'bưu', 'cưu', 'hưu', 'khưu', 'lưu', 'mưu', 'ngưu', 'sưu', 'tưu', 'trưu',
				'ừu', 'bừu', 'cừu', 'hừu', 'khừu', 'lừu', 'mừu', 'ngừu', 'sừu', 'từu', 'trừu',
				'ửu', 'bửu', 'cửu', 'hửu', 'khửu', 'lửu', 'mửu', 'ngửu', 'sửu', 'tửu', 'trửu',
				'ữu', 'bữu', 'cữu', 'hữu', 'khữu', 'lữu', 'mữu', 'ngữu', 'sữu', 'tữu', 'trữu',
				'ứu', 'bứu', 'cứu', 'hứu', 'khứu', 'lứu', 'mứu', 'ngứu', 'sứu', 'tứu', 'trứu',
				'ựu', 'bựu', 'cựu', 'hựu', 'khựu', 'lựu', 'mựu', 'ngựu', 'sựu', 'tựu', 'trựu',
				'yt', 'quyt',
				'ỳt', 'quỳt',
				'ỷt', 'quỷt',
				'ỹt', 'quỹt',
				'ýt', 'quýt',
				'ỵt', 'quỵt',

				'ach', 'bach', 'cach', 'chach', 'dach', 'đach', 'gach', 'hach', 'khach', 'lach', 'mach', 'nach', 'ngach', 'phach', 'quach', 'rach', 'sach', 'tach', 'thach', 'trach', 'vach', 'xach',
				'àch', 'bàch', 'càch', 'chàch', 'dàch', 'đàch', 'gàch', 'hàch', 'khàch', 'làch', 'màch', 'nàch', 'ngàch', 'phàch', 'quàch', 'ràch', 'sàch', 'tàch', 'thàch', 'tràch', 'vàch', 'xàch',
				'ảch', 'bảch', 'cảch', 'chảch', 'dảch', 'đảch', 'gảch', 'hảch', 'khảch', 'lảch', 'mảch', 'nảch', 'ngảch', 'phảch', 'quảch', 'rảch', 'sảch', 'tảch', 'thảch', 'trảch', 'vảch', 'xảch',
				'ãch', 'bãch', 'cãch', 'chãch', 'dãch', 'đãch', 'gãch', 'hãch', 'khãch', 'lãch', 'mãch', 'nãch', 'ngãch', 'phãch', 'quãch', 'rãch', 'sãch', 'tãch', 'thãch', 'trãch', 'vãch', 'xãch',
				'ách', 'bách', 'cách', 'chách', 'dách', 'đách', 'gách', 'hách', 'khách', 'lách', 'mách', 'nách', 'ngách', 'phách', 'quách', 'rách', 'sách', 'tách', 'thách', 'trách', 'vách', 'xách',
				'ạch', 'bạch', 'cạch', 'chạch', 'dạch', 'đạch', 'gạch', 'hạch', 'khạch', 'lạch', 'mạch', 'nạch', 'ngạch', 'phạch', 'quạch', 'rạch', 'sạch', 'tạch', 'thạch', 'trạch', 'vạch', 'xạch',
				'ang', 'bang', 'cang', 'chang', 'dang', 'đang', 'gang', 'giang', 'hang', 'khang', 'lang', 'mang', 'nang', 'ngang', 'nhang', 'phang', 'quang', 'rang', 'sang', 'tang', 'thang', 'trang', 'vang', 'xang',
				'àng', 'bàng', 'càng', 'chàng', 'dàng', 'đàng', 'gàng', 'giàng', 'hàng', 'khàng', 'làng', 'màng', 'nàng', 'ngàng', 'nhàng', 'phàng', 'quàng', 'ràng', 'sàng', 'tàng', 'thàng', 'tràng', 'vàng', 'xàng',
				'ảng', 'bảng', 'cảng', 'chảng', 'dảng', 'đảng', 'gảng', 'giảng', 'hảng', 'khảng', 'lảng', 'mảng', 'nảng', 'ngảng', 'nhảng', 'phảng', 'quảng', 'rảng', 'sảng', 'tảng', 'thảng', 'trảng', 'vảng', 'xảng',
				'ãng', 'bãng', 'cãng', 'chãng', 'dãng', 'đãng', 'gãng', 'giãng', 'hãng', 'khãng', 'lãng', 'mãng', 'nãng', 'ngãng', 'nhãng', 'phãng', 'quãng', 'rãng', 'sãng', 'tãng', 'thãng', 'trãng', 'vãng', 'xãng',
				'áng', 'báng', 'cáng', 'cháng', 'dáng', 'đáng', 'gáng', 'giáng', 'háng', 'kháng', 'láng', 'máng', 'náng', 'ngáng', 'nháng', 'pháng', 'quáng', 'ráng', 'sáng', 'táng', 'tháng', 'tráng', 'váng', 'xáng',
				'ạng', 'bạng', 'cạng', 'chạng', 'dạng', 'đạng', 'gạng', 'giạng', 'hạng', 'khạng', 'lạng', 'mạng', 'nạng', 'ngạng', 'nhạng', 'phạng', 'quạng', 'rạng', 'sạng', 'tạng', 'thạng', 'trạng', 'vạng', 'xạng',
				'anh', 'banh', 'canh', 'chanh', 'danh', 'đanh', 'ganh', 'gianh', 'hanh', 'khanh', 'lanh', 'manh', 'nanh', 'nganh', 'nhanh', 'phanh', 'quanh', 'ranh', 'sanh', 'tanh', 'thanh', 'tranh', 'vanh', 'xanh',
				'ành', 'bành', 'cành', 'chành', 'dành', 'đành', 'gành', 'giành', 'hành', 'khành', 'lành', 'mành', 'nành', 'ngành', 'nhành', 'phành', 'quành', 'rành', 'sành', 'tành', 'thành', 'trành', 'vành', 'xành',
				'ảnh', 'bảnh', 'cảnh', 'chảnh', 'dảnh', 'đảnh', 'gảnh', 'giảnh', 'hảnh', 'khảnh', 'lảnh', 'mảnh', 'nảnh', 'ngảnh', 'nhảnh', 'phảnh', 'quảnh', 'rảnh', 'sảnh', 'tảnh', 'thảnh', 'trảnh', 'vảnh', 'xảnh',
				'ãnh', 'bãnh', 'cãnh', 'chãnh', 'dãnh', 'đãnh', 'gãnh', 'giãnh', 'hãnh', 'khãnh', 'lãnh', 'mãnh', 'nãnh', 'ngãnh', 'nhãnh', 'phãnh', 'quãnh', 'rãnh', 'sãnh', 'tãnh', 'thãnh', 'trãnh', 'vãnh', 'xãnh',
				'ánh', 'bánh', 'cánh', 'chánh', 'dánh', 'đánh', 'gánh', 'giánh', 'hánh', 'khánh', 'lánh', 'mánh', 'nánh', 'ngánh', 'nhánh', 'phánh', 'quánh', 'ránh', 'sánh', 'tánh', 'thánh', 'tránh', 'vánh', 'xánh',
				'ạnh', 'bạnh', 'cạnh', 'chạnh', 'dạnh', 'đạnh', 'gạnh', 'giạnh', 'hạnh', 'khạnh', 'lạnh', 'mạnh', 'nạnh', 'ngạnh', 'nhạnh', 'phạnh', 'quạnh', 'rạnh', 'sạnh', 'tạnh', 'thạnh', 'trạnh', 'vạnh', 'xạnh',
				'ăng', 'băng', 'căng', 'chăng', 'dăng', 'đăng', 'găng', 'giăng', 'hăng', 'khăng', 'lăng', 'măng', 'năng', 'ngăng', 'nhăng', 'phăng', 'quăng', 'răng', 'săng', 'tăng', 'thăng', 'trăng', 'văng', 'xăng',
				'ằng', 'bằng', 'cằng', 'chằng', 'dằng', 'đằng', 'gằng', 'giằng', 'hằng', 'khằng', 'lằng', 'mằng', 'nằng', 'ngằng', 'nhằng', 'phằng', 'quằng', 'rằng', 'sằng', 'tằng', 'thằng', 'trằng', 'vằng', 'xằng',
				'ẳng', 'bẳng', 'cẳng', 'chẳng', 'dẳng', 'đẳng', 'gẳng', 'giẳng', 'hẳng', 'khẳng', 'lẳng', 'mẳng', 'nẳng', 'ngẳng', 'nhẳng', 'phẳng', 'quẳng', 'rẳng', 'sẳng', 'tẳng', 'thẳng', 'trẳng', 'vẳng', 'xẳng',
				'ẵng', 'bẵng', 'cẵng', 'chẵng', 'dẵng', 'đẵng', 'gẵng', 'giẵng', 'hẵng', 'khẵng', 'lẵng', 'mẵng', 'nẵng', 'ngẵng', 'nhẵng', 'phẵng', 'quẵng', 'rẵng', 'sẵng', 'tẵng', 'thẵng', 'trẵng', 'vẵng', 'xẵng',
				'ắng', 'bắng', 'cắng', 'chắng', 'dắng', 'đắng', 'gắng', 'giắng', 'hắng', 'khắng', 'lắng', 'mắng', 'nắng', 'ngắng', 'nhắng', 'phắng', 'quắng', 'rắng', 'sắng', 'tắng', 'thắng', 'trắng', 'vắng', 'xắng',
				'ặng', 'bặng', 'cặng', 'chặng', 'dặng', 'đặng', 'gặng', 'giặng', 'hặng', 'khặng', 'lặng', 'mặng', 'nặng', 'ngặng', 'nhặng', 'phặng', 'quặng', 'rặng', 'sặng', 'tặng', 'thặng', 'trặng', 'vặng', 'xặng',
				'âng', 'bâng', 'dâng', 'lâng', 'nâng', 'tâng', 'vâng', 'ầng', 'bầng', 'dầng', 'lầng', 'nầng', 'tầng', 'vầng', 'ẩng', 'bẩng', 'dẩng', 'lẩng', 'nẩng', 'tẩng', 'vẩng',
				'ẫng', 'bẫng', 'dẫng', 'lẫng', 'nẫng', 'tẫng', 'vẫng', 'ấng', 'bấng', 'dấng', 'lấng', 'nấng', 'tấng', 'vấng', 'ậng', 'bậng', 'dậng', 'lậng', 'nậng', 'tậng', 'vậng',
				'eng', 'beng', 'keng', 'leng', 'xeng', 'èng', 'bèng', 'kèng', 'lèng', 'xèng',
				'ẻng', 'bẻng', 'kẻng', 'lẻng', 'xẻng', 'ẽng', 'bẽng', 'kẽng', 'lẽng', 'xẽng',
				'éng', 'béng', 'kéng', 'léng', 'xéng', 'ẹng', 'bẹng', 'kẹng', 'lẹng', 'xẹng',
				'ếch', 'chếch', 'kếch', 'lếch', 'ngếch', 'nhếch', 'phếch', 'thếch', 'xếch',
				'ệch', 'chệch', 'kệch', 'lệch', 'ngệch', 'nhệch', 'phệch', 'thệch', 'xệch',
				'ênh', 'bênh', 'chênh', 'dênh', 'đênh', 'ghênh', 'hênh', 'kênh', 'lênh', 'mênh', 'nghênh', 'tênh', 'thênh', 'vênh',
				'ềnh', 'bềnh', 'chềnh', 'dềnh', 'đềnh', 'ghềnh', 'hềnh', 'kềnh', 'lềnh', 'mềnh', 'nghềnh', 'tềnh', 'thềnh', 'vềnh',
				'ểnh', 'bểnh', 'chểnh', 'dểnh', 'đểnh', 'ghểnh', 'hểnh', 'kểnh', 'lểnh', 'mểnh', 'nghểnh', 'tểnh', 'thểnh', 'vểnh',
				'ễnh', 'bễnh', 'chễnh', 'dễnh', 'đễnh', 'ghễnh', 'hễnh', 'kễnh', 'lễnh', 'mễnh', 'nghễnh', 'tễnh', 'thễnh', 'vễnh',
				'ếnh', 'bếnh', 'chếnh', 'dếnh', 'đếnh', 'ghếnh', 'hếnh', 'kếnh', 'lếnh', 'mếnh', 'nghếnh', 'tếnh', 'thếnh', 'vếnh',
				'ệnh', 'bệnh', 'chệnh', 'dệnh', 'đệnh', 'ghệnh', 'hệnh', 'kệnh', 'lệnh', 'mệnh', 'nghệnh', 'tệnh', 'thệnh', 'vệnh',
				'ích', 'bích', 'chích', 'dích', 'đích', 'hích', 'kích', 'khích', 'lích', 'mích', 'ních', 'nghích', 'nhích', 'phích', 'rích', 'tích', 'thích', 'trích', 'xích',
				'ịch', 'bịch', 'chịch', 'dịch', 'địch', 'hịch', 'kịch', 'khịch', 'lịch', 'mịch', 'nịch', 'nghịch', 'nhịch', 'phịch', 'rịch', 'tịch', 'thịch', 'trịch', 'xịch',
				'iêc', 'biêc', 'chiêc', 'diêc', 'điêc', 'ghiêc', 'liêc', 'nhiêc', 'tiêc', 'thiêc', 'viêc', 'xiêc',
				'iềc', 'biềc', 'chiềc', 'diềc', 'điềc', 'ghiềc', 'liềc', 'nhiềc', 'tiềc', 'thiềc', 'viềc', 'xiềc',
				'iểc', 'biểc', 'chiểc', 'diểc', 'điểc', 'ghiểc', 'liểc', 'nhiểc', 'tiểc', 'thiểc', 'viểc', 'xiểc',
				'iễc', 'biễc', 'chiễc', 'diễc', 'điễc', 'ghiễc', 'liễc', 'nhiễc', 'tiễc', 'thiễc', 'viễc', 'xiễc',
				'iếc', 'biếc', 'chiếc', 'diếc', 'điếc', 'ghiếc', 'liếc', 'nhiếc', 'tiếc', 'thiếc', 'viếc', 'xiếc',
				'iệc', 'biệc', 'chiệc', 'diệc', 'điệc', 'ghiệc', 'liệc', 'nhiệc', 'tiệc', 'thiệc', 'việc', 'xiệc',
				'iêm', 'biêm', 'chiêm', 'diêm', 'điêm', 'hiêm', 'kiêm', 'khiêm', 'liêm', 'niêm', 'nghiêm', 'nhiêm', 'phiêm', 'tiêm', 'thiêm', 'viêm', 'xiêm',
				'iềm', 'biềm', 'chiềm', 'diềm', 'điềm', 'hiềm', 'kiềm', 'khiềm', 'liềm', 'niềm', 'nghiềm', 'nhiềm', 'phiềm', 'tiềm', 'thiềm', 'viềm', 'xiềm',
				'iểm', 'biểm', 'chiểm', 'diểm', 'điểm', 'hiểm', 'kiểm', 'khiểm', 'liểm', 'niểm', 'nghiểm', 'nhiểm', 'phiểm', 'tiểm', 'thiểm', 'viểm', 'xiểm',
				'iễm', 'biễm', 'chiễm', 'diễm', 'điễm', 'hiễm', 'kiễm', 'khiễm', 'liễm', 'niễm', 'nghiễm', 'nhiễm', 'phiễm', 'tiễm', 'thiễm', 'viễm', 'xiễm',
				'iếm', 'biếm', 'chiếm', 'diếm', 'điếm', 'hiếm', 'kiếm', 'khiếm', 'liếm', 'niếm', 'nghiếm', 'nhiếm', 'phiếm', 'tiếm', 'thiếm', 'viếm', 'xiếm',
				'iệm', 'biệm', 'chiệm', 'diệm', 'điệm', 'hiệm', 'kiệm', 'khiệm', 'liệm', 'niệm', 'nghiệm', 'nhiệm', 'phiệm', 'tiệm', 'thiệm', 'việm', 'xiệm',
				'iên', 'biên', 'chiên', 'diên', 'điên', 'ghiên', 'hiên', 'kiên', 'khiên', 'liên', 'miên', 'niên', 'nghiên', 'nhiên', 'phiên', 'tiên', 'thiên', 'triên', 'viên', 'xiên',
				'iền', 'biền', 'chiền', 'diền', 'điền', 'ghiền', 'hiền', 'kiền', 'khiền', 'liền', 'miền', 'niền', 'nghiền', 'nhiền', 'phiền', 'tiền', 'thiền', 'triền', 'viền', 'xiền',
				'iển', 'biển', 'chiển', 'diển', 'điển', 'ghiển', 'hiển', 'kiển', 'khiển', 'liển', 'miển', 'niển', 'nghiển', 'nhiển', 'phiển', 'tiển', 'thiển', 'triển', 'viển', 'xiển',
				'iễn', 'biễn', 'chiễn', 'diễn', 'điễn', 'ghiễn', 'hiễn', 'kiễn', 'khiễn', 'liễn', 'miễn', 'niễn', 'nghiễn', 'nhiễn', 'phiễn', 'tiễn', 'thiễn', 'triễn', 'viễn', 'xiễn',
				'iến', 'biến', 'chiến', 'diến', 'điến', 'ghiến', 'hiến', 'kiến', 'khiến', 'liến', 'miến', 'niến', 'nghiến', 'nhiến', 'phiến', 'tiến', 'thiến', 'triến', 'viến', 'xiến',
				'iện', 'biện', 'chiện', 'diện', 'điện', 'ghiện', 'hiện', 'kiện', 'khiện', 'liện', 'miện', 'niện', 'nghiện', 'nhiện', 'phiện', 'tiện', 'thiện', 'triện', 'viện', 'xiện',
				'iêp', 'diêp', 'điêp', 'hiêp', 'nghiêp', 'nhiêp', 'tiêp', 'thiêp',
				'iềp', 'diềp', 'điềp', 'hiềp', 'nghiềp', 'nhiềp', 'tiềp', 'thiềp',
				'iểp', 'diểp', 'điểp', 'hiểp', 'nghiểp', 'nhiểp', 'tiểp', 'thiểp',
				'iễp', 'diễp', 'điễp', 'hiễp', 'nghiễp', 'nhiễp', 'tiễp', 'thiễp',
				'iếp', 'diếp', 'điếp', 'hiếp', 'nghiếp', 'nhiếp', 'tiếp', 'thiếp',
				'iệp', 'diệp', 'điệp', 'hiệp', 'nghiệp', 'nhiệp', 'tiệp', 'thiệp',
				'iết', 'biết', 'chiết', 'diết', 'giết', 'kiết', 'khiết', 'liết', 'miết', 'niết', 'nghiết', 'nhiết', 'phiết', 'riết', 'siết', 'tiết', 'thiết', 'triết', 'viết', 'xiết',
				'iệt', 'biệt', 'chiệt', 'diệt', 'giệt', 'kiệt', 'khiệt', 'liệt', 'miệt', 'niệt', 'nghiệt', 'nhiệt', 'phiệt', 'riệt', 'siệt', 'tiệt', 'thiệt', 'triệt', 'việt', 'xiệt',
				'iêu', 'biêu', 'chiêu', 'diêu', 'điêu', 'hiêu', 'kiêu', 'khiêu', 'liêu', 'miêu', 'niêu', 'nhiêu', 'phiêu', 'riêu', 'siêu', 'tiêu', 'thiêu', 'triêu', 'xiêu',
				'iều', 'biều', 'chiều', 'diều', 'điều', 'hiều', 'kiều', 'khiều', 'liều', 'miều', 'niều', 'nhiều', 'phiều', 'riều', 'siều', 'tiều', 'thiều', 'triều', 'xiều',
				'iểu', 'biểu', 'chiểu', 'diểu', 'điểu', 'hiểu', 'kiểu', 'khiểu', 'liểu', 'miểu', 'niểu', 'nhiểu', 'phiểu', 'riểu', 'siểu', 'tiểu', 'thiểu', 'triểu', 'xiểu',
				'iễu', 'biễu', 'chiễu', 'diễu', 'điễu', 'hiễu', 'kiễu', 'khiễu', 'liễu', 'miễu', 'niễu', 'nhiễu', 'phiễu', 'riễu', 'siễu', 'tiễu', 'thiễu', 'triễu', 'xiễu',
				'iếu', 'biếu', 'chiếu', 'diếu', 'điếu', 'hiếu', 'kiếu', 'khiếu', 'liếu', 'miếu', 'niếu', 'nhiếu', 'phiếu', 'riếu', 'siếu', 'tiếu', 'thiếu', 'triếu', 'xiếu',
				'iệu', 'biệu', 'chiệu', 'diệu', 'điệu', 'hiệu', 'kiệu', 'khiệu', 'liệu', 'miệu', 'niệu', 'nhiệu', 'phiệu', 'riệu', 'siệu', 'tiệu', 'thiệu', 'triệu', 'xiệu',
				'inh', 'binh', 'chinh', 'dinh', 'đinh', 'hinh', 'kinh', 'khinh', 'linh', 'minh', 'ninh', 'nghinh', 'nhinh', 'phinh', 'rinh', 'sinh', 'tinh', 'thinh', 'trinh', 'vinh', 'xinh',
				'ình', 'bình', 'chình', 'dình', 'đình', 'hình', 'kình', 'khình', 'lình', 'mình', 'nình', 'nghình', 'nhình', 'phình', 'rình', 'sình', 'tình', 'thình', 'trình', 'vình', 'xình',
				'ỉnh', 'bỉnh', 'chỉnh', 'dỉnh', 'đỉnh', 'hỉnh', 'kỉnh', 'khỉnh', 'lỉnh', 'mỉnh', 'nỉnh', 'nghỉnh', 'nhỉnh', 'phỉnh', 'rỉnh', 'sỉnh', 'tỉnh', 'thỉnh', 'trỉnh', 'vỉnh', 'xỉnh',
				'ĩnh', 'bĩnh', 'chĩnh', 'dĩnh', 'đĩnh', 'hĩnh', 'kĩnh', 'khĩnh', 'lĩnh', 'mĩnh', 'nĩnh', 'nghĩnh', 'nhĩnh', 'phĩnh', 'rĩnh', 'sĩnh', 'tĩnh', 'thĩnh', 'trĩnh', 'vĩnh', 'xĩnh',
				'ính', 'bính', 'chính', 'dính', 'đính', 'hính', 'kính', 'khính', 'lính', 'mính', 'nính', 'nghính', 'nhính', 'phính', 'rính', 'sính', 'tính', 'thính', 'trính', 'vính', 'xính',
				'ịnh', 'bịnh', 'chịnh', 'dịnh', 'định', 'hịnh', 'kịnh', 'khịnh', 'lịnh', 'mịnh', 'nịnh', 'nghịnh', 'nhịnh', 'phịnh', 'rịnh', 'sịnh', 'tịnh', 'thịnh', 'trịnh', 'vịnh', 'xịnh',
				'oác', 'choác', 'khoác', 'ngoác',
				'oạc', 'choạc', 'khoạc', 'ngoạc',
				'oai', 'choai', 'đoai', 'hoai', 'khoai', 'loai', 'ngoai', 'nhoai', 'soai', 'toai', 'thoai', 'xoai',
				'oài', 'choài', 'đoài', 'hoài', 'khoài', 'loài', 'ngoài', 'nhoài', 'soài', 'toài', 'thoài', 'xoài',
				'oải', 'choải', 'đoải', 'hoải', 'khoải', 'loải', 'ngoải', 'nhoải', 'soải', 'toải', 'thoải', 'xoải',
				'oãi', 'choãi', 'đoãi', 'hoãi', 'khoãi', 'loãi', 'ngoãi', 'nhoãi', 'soãi', 'toãi', 'thoãi', 'xoãi',
				'oái', 'choái', 'đoái', 'hoái', 'khoái', 'loái', 'ngoái', 'nhoái', 'soái', 'toái', 'thoái', 'xoái',
				'oại', 'choại', 'đoại', 'hoại', 'khoại', 'loại', 'ngoại', 'nhoại', 'soại', 'toại', 'thoại', 'xoại',
				'oan', 'doan', 'đoan', 'hoan', 'khoan', 'loan', 'ngoan', 'soan', 'toan', 'xoan',
				'oàn', 'doàn', 'đoàn', 'hoàn', 'khoàn', 'loàn', 'ngoàn', 'soàn', 'toàn', 'xoàn',
				'oản', 'doản', 'đoản', 'hoản', 'khoản', 'loản', 'ngoản', 'soản', 'toản', 'xoản',
				'oãn', 'doãn', 'đoãn', 'hoãn', 'khoãn', 'loãn', 'ngoãn', 'soãn', 'toãn', 'xoãn',
				'oán', 'doán', 'đoán', 'hoán', 'khoán', 'loán', 'ngoán', 'soán', 'toán', 'xoán',
				'oạn', 'doạn', 'đoạn', 'hoạn', 'khoạn', 'loạn', 'ngoạn', 'soạn', 'toạn', 'xoạn',
				'oát', 'đoát', 'hoát', 'khoát', 'loát', 'soát', 'toát', 'thoát',
				'oạt', 'đoạt', 'hoạt', 'khoạt', 'loạt', 'soạt', 'toạt', 'thoạt',
				'oay', 'hoay', 'khoay', 'loay', 'ngoay', 'xoay',
				'oày', 'hoày', 'khoày', 'loày', 'ngoày', 'xoày',
				'oảy', 'hoảy', 'khoảy', 'loảy', 'ngoảy', 'xoảy',
				'oãy', 'hoãy', 'khoãy', 'loãy', 'ngoãy', 'xoãy',
				'oáy', 'hoáy', 'khoáy', 'loáy', 'ngoáy', 'xoáy',
				'oạy', 'hoạy', 'khoạy', 'loạy', 'ngoạy', 'xoạy',
				'oăc', 'hoăc', 'ngoăc',
				'oằc', 'hoằc', 'ngoằc',
				'oẳc', 'hoẳc', 'ngoẳc',
				'oẵc', 'hoẵc', 'ngoẵc',
				'oắc', 'hoắc', 'ngoắc',
				'oặc', 'hoặc', 'ngoặc',
				'oăn', 'thoăn', 'xoăn',
				'oằn', 'thoằn', 'xoằn',
				'oẳn', 'thoẳn', 'xoẳn',
				'oẵn', 'thoẵn', 'xoẵn',
				'oắn', 'thoắn', 'xoắn',
				'oặn', 'thoặn', 'xoặn',
				'oăt', 'hoăt', 'thoăt',
				'oằt', 'hoằt', 'thoằt',
				'oẳt', 'hoẳt', 'thoẳt',
				'oẵt', 'hoẵt', 'thoẵt',
				'oắt', 'hoắt', 'thoắt',
				'oặt', 'hoặt', 'thoặt',
				'oen', 'hoen', 'khoen',
				'oèn', 'hoèn', 'khoèn',
				'oẻn', 'hoẻn', 'khoẻn',
				'oẽn', 'hoẽn', 'khoẽn',
				'oén', 'hoén', 'khoén',
				'oẹn', 'hoẹn', 'khoẹn',
				'oeo', 'ngoeo',
				'oèo', 'ngoèo',
				'oẻo', 'ngoẻo',
				'oẽo', 'ngoẽo',
				'oéo', 'ngoéo',
				'oẹo', 'ngoẹo',
				'oet', 'loet', 'khoet', 'toet',
				'oèt', 'loèt', 'khoèt', 'toèt',
				'oẻt', 'loẻt', 'khoẻt', 'toẻt',
				'oẽt', 'loẽt', 'khoẽt', 'toẽt',
				'oét', 'loét', 'khoét', 'toét',
				'oẹt', 'loẹt', 'khoẹt', 'toẹt',
				'ong', 'bong', 'cong', 'chong', 'dong', 'đong', 'giong', 'hong', 'long', 'mong', 'nong', 'ngong', 'phong', 'rong', 'song', 'tong', 'thong', 'trong', 'vong', 'xong',
				'òng', 'bòng', 'còng', 'chòng', 'dòng', 'đòng', 'giòng', 'hòng', 'lòng', 'mòng', 'nòng', 'ngòng', 'phòng', 'ròng', 'sòng', 'tòng', 'thòng', 'tròng', 'vòng', 'xòng',
				'ỏng', 'bỏng', 'cỏng', 'chỏng', 'dỏng', 'đỏng', 'giỏng', 'hỏng', 'lỏng', 'mỏng', 'nỏng', 'ngỏng', 'phỏng', 'rỏng', 'sỏng', 'tỏng', 'thỏng', 'trỏng', 'vỏng', 'xỏng',
				'õng', 'bõng', 'cõng', 'chõng', 'dõng', 'đõng', 'giõng', 'hõng', 'lõng', 'mõng', 'nõng', 'ngõng', 'phõng', 'rõng', 'sõng', 'tõng', 'thõng', 'trõng', 'võng', 'xõng',
				'óng', 'bóng', 'cóng', 'chóng', 'dóng', 'đóng', 'gióng', 'hóng', 'lóng', 'móng', 'nóng', 'ngóng', 'phóng', 'róng', 'sóng', 'tóng', 'thóng', 'tróng', 'vóng', 'xóng',
				'ọng', 'bọng', 'cọng', 'chọng', 'dọng', 'đọng', 'giọng', 'họng', 'lọng', 'mọng', 'nọng', 'ngọng', 'phọng', 'rọng', 'sọng', 'tọng', 'thọng', 'trọng', 'vọng', 'xọng',
				'ông', 'bông', 'công', 'chông', 'dông', 'đông', 'gông', 'ghông', 'giông', 'hông', 'không', 'lông', 'mông', 'nông', 'ngông', 'nhông', 'phông', 'rông', 'sông', 'tông', 'thông', 'trông', 'vông', 'xông',
				'ồng', 'bồng', 'cồng', 'chồng', 'dồng', 'đồng', 'gồng', 'ghồng', 'giồng', 'hồng', 'khồng', 'lồng', 'mồng', 'nồng', 'ngồng', 'nhồng', 'phồng', 'rồng', 'sồng', 'tồng', 'thồng', 'trồng', 'vồng', 'xồng',
				'ổng', 'bổng', 'cổng', 'chổng', 'dổng', 'đổng', 'gổng', 'ghổng', 'giổng', 'hổng', 'khổng', 'lổng', 'mổng', 'nổng', 'ngổng', 'nhổng', 'phổng', 'rổng', 'sổng', 'tổng', 'thổng', 'trổng', 'vổng', 'xổng',
				'ỗng', 'bỗng', 'cỗng', 'chỗng', 'dỗng', 'đỗng', 'gỗng', 'ghỗng', 'giỗng', 'hỗng', 'khỗng', 'lỗng', 'mỗng', 'nỗng', 'ngỗng', 'nhỗng', 'phỗng', 'rỗng', 'sỗng', 'tỗng', 'thỗng', 'trỗng', 'vỗng', 'xỗng',
				'ống', 'bống', 'cống', 'chống', 'dống', 'đống', 'gống', 'ghống', 'giống', 'hống', 'khống', 'lống', 'mống', 'nống', 'ngống', 'nhống', 'phống', 'rống', 'sống', 'tống', 'thống', 'trống', 'vống', 'xống',
				'ộng', 'bộng', 'cộng', 'chộng', 'dộng', 'động', 'gộng', 'ghộng', 'giộng', 'hộng', 'khộng', 'lộng', 'mộng', 'nộng', 'ngộng', 'nhộng', 'phộng', 'rộng', 'sộng', 'tộng', 'thộng', 'trộng', 'vộng', 'xộng',
				'uân', 'chuân', 'duân', 'huân', 'khuân', 'luân', 'nhuân', 'tuân', 'thuân', 'xuân',
				'uần', 'chuần', 'duần', 'huần', 'khuần', 'luần', 'nhuần', 'tuần', 'thuần', 'xuần',
				'uẩn', 'chuẩn', 'duẩn', 'huẩn', 'khuẩn', 'luẩn', 'nhuẩn', 'tuẩn', 'thuẩn', 'xuẩn',
				'uẫn', 'chuẫn', 'duẫn', 'huẫn', 'khuẫn', 'luẫn', 'nhuẫn', 'tuẫn', 'thuẫn', 'xuẫn',
				'uấn', 'chuấn', 'duấn', 'huấn', 'khuấn', 'luấn', 'nhuấn', 'tuấn', 'thuấn', 'xuấn',
				'uận', 'chuận', 'duận', 'huận', 'khuận', 'luận', 'nhuận', 'tuận', 'thuận', 'xuận',
				'uât', 'duât', 'khuât', 'luât', 'suât', 'tuât', 'thuât', 'truât', 'xuât',
				'uất', 'duất', 'khuất', 'luất', 'suất', 'tuất', 'thuất', 'truất', 'xuất',
				'uật', 'duật', 'khuật', 'luật', 'suật', 'tuật', 'thuật', 'truật', 'xuật', 'uây', 'khuây', 'nguây',
				'uầy', 'khuầy', 'nguầy', 'uẩy', 'khuẩy', 'nguẩy', 'uẫy', 'khuẫy', 'nguẫy', 'uấy', 'khuấy', 'nguấy',
				'uậy', 'khuậy', 'nguậy',
				'ung', 'bung', 'cung', 'chung', 'dung', 'đung', 'hung', 'khung', 'lung', 'mung', 'nung', 'nhung', 'phung', 'rung', 'sung', 'tung', 'thung', 'trung', 'vung', 'xung',
				'ùng', 'bùng', 'cùng', 'chùng', 'dùng', 'đùng', 'hùng', 'khùng', 'lùng', 'mùng', 'nùng', 'nhùng', 'phùng', 'rùng', 'sùng', 'tùng', 'thùng', 'trùng', 'vùng', 'xùng',
				'ủng', 'bủng', 'củng', 'chủng', 'dủng', 'đủng', 'hủng', 'khủng', 'lủng', 'mủng', 'nủng', 'nhủng', 'phủng', 'rủng', 'sủng', 'tủng', 'thủng', 'trủng', 'vủng', 'xủng',
				'ũng', 'bũng', 'cũng', 'chũng', 'dũng', 'đũng', 'hũng', 'khũng', 'lũng', 'mũng', 'nũng', 'nhũng', 'phũng', 'rũng', 'sũng', 'tũng', 'thũng', 'trũng', 'vũng', 'xũng',
				'úng', 'búng', 'cúng', 'chúng', 'dúng', 'đúng', 'húng', 'khúng', 'lúng', 'múng', 'núng', 'nhúng', 'phúng', 'rúng', 'súng', 'túng', 'thúng', 'trúng', 'vúng', 'xúng',
				'ụng', 'bụng', 'cụng', 'chụng', 'dụng', 'đụng', 'hụng', 'khụng', 'lụng', 'mụng', 'nụng', 'nhụng', 'phụng', 'rụng', 'sụng', 'tụng', 'thụng', 'trụng', 'vụng', 'xụng',
				'uốc', 'buốc', 'cuốc', 'chuốc', 'đuốc', 'guốc', 'giuốc', 'luốc', 'nhuốc', 'ruốc', 'tuốc', 'thuốc', 'uộc', 'buộc', 'cuộc', 'chuộc', 'đuộc', 'guộc', 'giuộc', 'luộc', 'nhuộc', 'ruộc', 'tuộc', 'thuộc',
				'uôi', 'cuôi', 'chuôi', 'duôi', 'đuôi', 'muôi', 'nuôi', 'nguôi', 'suôi', 'xuôi',
				'uổi', 'cuổi', 'chuổi', 'duổi', 'đuổi', 'muổi', 'nuổi', 'nguổi', 'suổi', 'xuổi',
				'uỗi', 'cuỗi', 'chuỗi', 'duỗi', 'đuỗi', 'muỗi', 'nuỗi', 'nguỗi', 'suỗi', 'xuỗi',
				'uối', 'cuối', 'chuối', 'duối', 'đuối', 'muối', 'nuối', 'nguối', 'suối', 'xuối',
				'uội', 'cuội', 'chuội', 'duội', 'đuội', 'muội', 'nuội', 'nguội', 'suội', 'xuội',
				'uôm', 'buôm', 'cuôm', 'nhuôm', 'thuôm', 'uồm', 'buồm', 'cuồm', 'nhuồm', 'thuồm',
				'uổm', 'buổm', 'cuổm', 'nhuổm', 'thuổm', 'uỗm', 'buỗm', 'cuỗm', 'nhuỗm', 'thuỗm',
				'uốm', 'buốm', 'cuốm', 'nhuốm', 'thuốm', 'uộm', 'buộm', 'cuộm', 'nhuộm', 'thuộm',
				'uôn', 'buôn', 'cuôn', 'chuôn', 'khuôn', 'luôn', 'muôn', 'nguôn', 'ruôn', 'suôn', 'tuôn', 'thuôn',
				'uồn', 'buồn', 'cuồn', 'chuồn', 'khuồn', 'luồn', 'muồn', 'nguồn', 'ruồn', 'suồn', 'tuồn', 'thuồn',
				'uổn', 'buổn', 'cuổn', 'chuổn', 'khuổn', 'luổn', 'muổn', 'nguổn', 'ruổn', 'suổn', 'tuổn', 'thuổn',
				'uỗn', 'buỗn', 'cuỗn', 'chuỗn', 'khuỗn', 'luỗn', 'muỗn', 'nguỗn', 'ruỗn', 'suỗn', 'tuỗn', 'thuỗn',
				'uốn', 'buốn', 'cuốn', 'chuốn', 'khuốn', 'luốn', 'muốn', 'nguốn', 'ruốn', 'suốn', 'tuốn', 'thuốn',
				'uộn', 'buộn', 'cuộn', 'chuộn', 'khuộn', 'luộn', 'muộn', 'nguộn', 'ruộn', 'suộn', 'tuộn', 'thuộn',
				'uốt', 'buốt', 'chuốt', 'ruốt', 'tuốt', 'thuốt', 'truốt',
				'uột', 'buột', 'chuột', 'ruột', 'tuột', 'thuột', 'truột',
				'uya', 'khuya', 'uỳa', 'khuỳa',
				'uỷa', 'khuỷa', 'uỹa', 'khuỹa',
				'uýa', 'khuýa', 'uỵa', 'khuỵa',
				'uyt', 'huyt', 'suyt', 'tuyt',
				'uỳt', 'huỳt', 'suỳt', 'tuỳt',
				'uỷt', 'huỷt', 'suỷt', 'tuỷt',
				'uỹt', 'huỹt', 'suỹt', 'tuỹt',
				'uýt', 'huýt', 'suýt', 'tuýt',
				'uỵt', 'huỵt', 'suỵt', 'tuỵt',
				'uyu', 'khuyu',
				'uỳu', 'khuỳu',
				'uỷu', 'khuỷu',
				'uỹu', 'khuỹu',
				'uýu', 'khuýu',
				'uỵu', 'khuỵu',
				'ưng', 'bưng', 'cưng', 'chưng', 'dưng', 'đưng', 'gưng', 'hưng', 'khưng', 'lưng', 'mưng', 'nưng', 'ngưng', 'nhưng', 'rưng', 'sưng', 'tưng', 'thưng', 'trưng', 'vưng', 'xưng',
				'ừng', 'bừng', 'cừng', 'chừng', 'dừng', 'đừng', 'gừng', 'hừng', 'khừng', 'lừng', 'mừng', 'nừng', 'ngừng', 'nhừng', 'rừng', 'sừng', 'từng', 'thừng', 'trừng', 'vừng', 'xừng',
				'ửng', 'bửng', 'cửng', 'chửng', 'dửng', 'đửng', 'gửng', 'hửng', 'khửng', 'lửng', 'mửng', 'nửng', 'ngửng', 'nhửng', 'rửng', 'sửng', 'tửng', 'thửng', 'trửng', 'vửng', 'xửng',
				'ững', 'bững', 'cững', 'chững', 'dững', 'đững', 'gững', 'hững', 'khững', 'lững', 'mững', 'nững', 'ngững', 'những', 'rững', 'sững', 'tững', 'thững', 'trững', 'vững', 'xững',
				'ứng', 'bứng', 'cứng', 'chứng', 'dứng', 'đứng', 'gứng', 'hứng', 'khứng', 'lứng', 'mứng', 'nứng', 'ngứng', 'nhứng', 'rứng', 'sứng', 'tứng', 'thứng', 'trứng', 'vứng', 'xứng',
				'ựng', 'bựng', 'cựng', 'chựng', 'dựng', 'đựng', 'gựng', 'hựng', 'khựng', 'lựng', 'mựng', 'nựng', 'ngựng', 'nhựng', 'rựng', 'sựng', 'tựng', 'thựng', 'trựng', 'vựng', 'xựng',
				'ươc', 'bươc', 'cươc', 'chươc', 'dươc', 'đươc', 'khươc', 'lươc', 'ngươc', 'nhươc', 'phươc', 'rươc', 'tươc', 'thươc', 'trươc', 'xươc',
				'ườc', 'bườc', 'cườc', 'chườc', 'dườc', 'đườc', 'khườc', 'lườc', 'ngườc', 'nhườc', 'phườc', 'rườc', 'tườc', 'thườc', 'trườc', 'xườc',
				'ưởc', 'bưởc', 'cưởc', 'chưởc', 'dưởc', 'đưởc', 'khưởc', 'lưởc', 'ngưởc', 'nhưởc', 'phưởc', 'rưởc', 'tưởc', 'thưởc', 'trưởc', 'xưởc',
				'ưỡc', 'bưỡc', 'cưỡc', 'chưỡc', 'dưỡc', 'đưỡc', 'khưỡc', 'lưỡc', 'ngưỡc', 'nhưỡc', 'phưỡc', 'rưỡc', 'tưỡc', 'thưỡc', 'trưỡc', 'xưỡc',
				'ước', 'bước', 'cước', 'chước', 'dước', 'đước', 'khước', 'lước', 'ngước', 'nhước', 'phước', 'rước', 'tước', 'thước', 'trước', 'xước',
				'ược', 'bược', 'cược', 'chược', 'dược', 'được', 'khược', 'lược', 'ngược', 'nhược', 'phược', 'rược', 'tược', 'thược', 'trược', 'xược',
				'ươi', 'bươi', 'cươi', 'dươi', 'đươi', 'khươi', 'lươi', 'mươi', 'ngươi', 'rươi', 'sươi', 'tươi',
				'ười', 'bười', 'cười', 'dười', 'đười', 'khười', 'lười', 'mười', 'người', 'rười', 'sười', 'tười',
				'ưởi', 'bưởi', 'cưởi', 'dưởi', 'đưởi', 'khưởi', 'lưởi', 'mưởi', 'ngưởi', 'rưởi', 'sưởi', 'tưởi',
				'ưỡi', 'bưỡi', 'cưỡi', 'dưỡi', 'đưỡi', 'khưỡi', 'lưỡi', 'mưỡi', 'ngưỡi', 'rưỡi', 'sưỡi', 'tưỡi',
				'ưới', 'bưới', 'cưới', 'dưới', 'đưới', 'khưới', 'lưới', 'mưới', 'ngưới', 'rưới', 'sưới', 'tưới',
				'ượi', 'bượi', 'cượi', 'dượi', 'đượi', 'khượi', 'lượi', 'mượi', 'ngượi', 'rượi', 'sượi', 'tượi',
				'ươm', 'chươm', 'gươm', 'lươm', 'tươm',
				'ườm', 'chườm', 'gườm', 'lườm', 'tườm',
				'ưởm', 'chưởm', 'gưởm', 'lưởm', 'tưởm',
				'ưỡm', 'chưỡm', 'gưỡm', 'lưỡm', 'tưỡm',
				'ướm', 'chướm', 'gướm', 'lướm', 'tướm',
				'ượm', 'chượm', 'gượm', 'lượm', 'tượm',
				'ươn', 'bươn', 'lươn', 'trươn', 'vươn',
				'ườn', 'bườn', 'lườn', 'trườn', 'vườn',
				'ưởn', 'bưởn', 'lưởn', 'trưởn', 'vưởn',
				'ưỡn', 'bưỡn', 'lưỡn', 'trưỡn', 'vưỡn',
				'ướn', 'bướn', 'lướn', 'trướn', 'vướn',
				'ượn', 'bượn', 'lượn', 'trượn', 'vượn',
				'ươp', 'cươp', 'mươp', 'ườp', 'cườp', 'mườp',
				'ưởp', 'cưởp', 'mưởp', 'ưỡp', 'cưỡp', 'mưỡp',
				'ướp', 'cướp', 'mướp',
				'ượp', 'cượp', 'mượp',
				'ươt', 'lươt', 'mươt', 'phươt', 'rươt', 'sươt', 'tươt', 'thươt', 'trươt', 'vươt',
				'ườt', 'lườt', 'mườt', 'phườt', 'rườt', 'sườt', 'tườt', 'thườt', 'trườt', 'vườt',
				'ưởt', 'lưởt', 'mưởt', 'phưởt', 'rưởt', 'sưởt', 'tưởt', 'thưởt', 'trưởt', 'vưởt',
				'ưỡt', 'lưỡt', 'mưỡt', 'phưỡt', 'rưỡt', 'sưỡt', 'tưỡt', 'thưỡt', 'trưỡt', 'vưỡt',
				'ướt', 'lướt', 'mướt', 'phướt', 'rướt', 'sướt', 'tướt', 'thướt', 'trướt', 'vướt',
				'ượt', 'lượt', 'mượt', 'phượt', 'rượt', 'sượt', 'tượt', 'thượt', 'trượt', 'vượt',
				'ươu', 'bươu', 'hươu', 'khươu', 'rươu', 'ườu', 'bườu', 'hườu', 'khườu', 'rườu',
				'ưởu', 'bưởu', 'hưởu', 'khưởu', 'rưởu', 'ưỡu', 'bưỡu', 'hưỡu', 'khưỡu', 'rưỡu',
				'ướu', 'bướu', 'hướu', 'khướu', 'rướu', 'ượu', 'bượu', 'hượu', 'khượu', 'rượu',
				'yên', 'quyên', 'yền', 'quyền', 'yển', 'quyển', 'yễn', 'quyễn', 'yến', 'quyến',
				'yện', 'quyện', 'yêt', 'quyêt', 'yềt', 'quyềt', 'yểt', 'quyểt', 'yễt', 'quyễt',
				'yết', 'quyết', 'yệt', 'quyệt',
				'yêu', 'yều', 'yểu', 'yễu', 'yếu', 'yệu',
				'ynh', 'quynh',
				'ỳnh', 'quỳnh',
				'ỷnh', 'quỷnh',
				'ỹnh', 'quỹnh',
				'ýnh', 'quýnh',
				'ỵnh', 'quỵnh',

				'iêng', 'biêng', 'chiêng', 'điêng', 'giêng', 'kiêng', 'khiêng', 'liêng', 'nghiêng', 'riêng', 'siêng', 'tiêng', 'thiêng', 'viêng',
				'iềng', 'biềng', 'chiềng', 'điềng', 'giêng', 'kiềng', 'khiềng', 'liềng', 'nghiềng', 'riềng', 'siềng', 'tiềng', 'thiềng', 'viềng',
				'iểng', 'biểng', 'chiểng', 'điểng', 'giêng', 'kiểng', 'khiểng', 'liểng', 'nghiểng', 'riểng', 'siểng', 'tiểng', 'thiểng', 'viểng',
				'iễng', 'biễng', 'chiễng', 'điễng', 'giêng', 'kiễng', 'khiễng', 'liễng', 'nghiễng', 'riễng', 'siễng', 'tiễng', 'thiễng', 'viễng',
				'iếng', 'biếng', 'chiếng', 'điếng', 'giêng', 'kiếng', 'khiếng', 'liếng', 'nghiếng', 'riếng', 'siếng', 'tiếng', 'thiếng', 'viếng',
				'iệng', 'biệng', 'chiệng', 'điệng', 'giêng', 'kiệng', 'khiệng', 'liệng', 'nghiệng', 'riệng', 'siệng', 'tiệng', 'thiệng', 'việng',
				'oang', 'choang', 'đoang', 'hoang', 'khoang', 'loang', 'nhoang', 'thoang', 'xoang',
				'oàng', 'choàng', 'đoàng', 'hoàng', 'khoàng', 'loàng', 'nhoàng', 'thoàng', 'xoàng',
				'oảng', 'choảng', 'đoảng', 'hoảng', 'khoảng', 'loảng', 'nhoảng', 'thoảng', 'xoảng',
				'oãng', 'choãng', 'đoãng', 'hoãng', 'khoãng', 'loãng', 'nhoãng', 'thoãng', 'xoãng',
				'oáng', 'choáng', 'đoáng', 'hoáng', 'khoáng', 'loáng', 'nhoáng', 'thoáng', 'xoáng',
				'oạng', 'choạng', 'đoạng', 'hoạng', 'khoạng', 'loạng', 'nhoạng', 'thoạng', 'xoạng',
				'oanh', 'doanh', 'hoanh', 'loanh', 'toanh', 'oành', 'doành', 'hoành', 'loành', 'toành',
				'oảnh', 'doảnh', 'hoảnh', 'loảnh', 'toảnh', 'oãnh', 'doãnh', 'hoãnh', 'loãnh', 'toãnh',
				'oánh', 'doánh', 'hoánh', 'loánh', 'toánh', 'oạnh', 'doạnh', 'hoạnh', 'loạnh', 'toạnh',
				'oăng', 'gioăng', 'hoăng', 'oằng', 'gioằng', 'hoằng', 'oẳng', 'gioẳng', 'hoẳng',
				'oẵng', 'gioẵng', 'hoẵng', 'oắng', 'gioắng', 'hoắng', 'oặng', 'gioặng', 'hoặng',
				'oong', 'boong', 'coong', 'đoong', 'koong', 'xoong',
				'òòng', 'bòòng', 'còòng', 'đòòng', 'kòòng', 'xòòng',
				'ỏỏng', 'bỏỏng', 'cỏỏng', 'đỏỏng', 'kỏỏng', 'xỏỏng',
				'õõng', 'bõõng', 'cõõng', 'đõõng', 'kõõng', 'xõõng',
				'óóng', 'bóóng', 'cóóng', 'đóóng', 'kóóng', 'xóóng',
				'ọọng', 'bọọng', 'cọọng', 'đọọng', 'kọọng', 'xọọng',
				'uâng', 'khuâng',
				'uầng', 'khuầng',
				'uẩng', 'khuẩng',
				'uẫng', 'khuẫng',
				'uấng', 'khuấng',
				'uậng', 'khuậng',
				'uêch', 'khuêch',
				'uềch', 'khuềch',
				'uểch', 'khuểch',
				'uễch', 'khuễch',
				'uếch', 'khuếch',
				'uệch', 'khuệch',
				'uênh', 'huênh',
				'uềnh', 'huềnh',
				'uểnh', 'huểnh',
				'uễnh', 'huễnh',
				'uếnh', 'huếnh',
				'uệnh', 'huệnh',
				'uông', 'buông', 'cuông', 'chuông', 'đuông', 'huông', 'khuông', 'luông', 'muông', 'nuông', 'tuông', 'thuông', 'truông', 'vuông', 'xuông',
				'uồng', 'buồng', 'cuồng', 'chuồng', 'đuồng', 'huồng', 'khuồng', 'luồng', 'muồng', 'nuồng', 'tuồng', 'thuồng', 'truồng', 'vuồng', 'xuồng',
				'uổng', 'buổng', 'cuổng', 'chuổng', 'đuổng', 'huổng', 'khuổng', 'luổng', 'muổng', 'nuổng', 'tuổng', 'thuổng', 'truổng', 'vuổng', 'xuổng',
				'uỗng', 'buỗng', 'cuỗng', 'chuỗng', 'đuỗng', 'huỗng', 'khuỗng', 'luỗng', 'muỗng', 'nuỗng', 'tuỗng', 'thuỗng', 'truỗng', 'vuỗng', 'xuỗng',
				'uống', 'buống', 'cuống', 'chuống', 'đuống', 'huống', 'khuống', 'luống', 'muống', 'nuống', 'tuống', 'thuống', 'truống', 'vuống', 'xuống',
				'uộng', 'buộng', 'cuộng', 'chuộng', 'đuộng', 'huộng', 'khuộng', 'luộng', 'muộng', 'nuộng', 'tuộng', 'thuộng', 'truộng', 'vuộng', 'xuộng',
				'uyên', 'chuyên', 'duyên', 'huyên', 'khuyên', 'luyên', 'nguyên', 'nhuyên', 'suyên', 'tuyên', 'thuyên', 'truyên', 'xuyên', 'uyền', 'chuyền', 'duyền', 'huyền', 'khuyền', 'luyền', 'nguyền', 'nhuyền', 'suyền', 'tuyền', 'thuyền', 'truyền', 'xuyền',
				'uyển', 'chuyển', 'duyển', 'huyển', 'khuyển', 'luyển', 'nguyển', 'nhuyển', 'suyển', 'tuyển', 'thuyển', 'truyển', 'xuyển', 'uyễn', 'chuyễn', 'duyễn', 'huyễn', 'khuyễn', 'luyễn', 'nguyễn', 'nhuyễn', 'suyễn', 'tuyễn', 'thuyễn', 'truyễn', 'xuyễn',
				'uyến', 'chuyến', 'duyến', 'huyến', 'khuyến', 'luyến', 'nguyến', 'nhuyến', 'suyến', 'tuyến', 'thuyến', 'truyến', 'xuyến', 'uyện', 'chuyện', 'duyện', 'huyện', 'khuyện', 'luyện', 'nguyện', 'nhuyện', 'suyện', 'tuyện', 'thuyện', 'truyện', 'xuyện',
				'uyêt', 'duyêt', 'huyêt', 'khuyêt', 'nguyêt', 'tuyêt', 'thuyêt', 'xuyêt',
				'uyềt', 'duyềt', 'huyềt', 'khuyềt', 'nguyềt', 'tuyềt', 'thuyềt', 'xuyềt',
				'uyểt', 'duyểt', 'huyểt', 'khuyểt', 'nguyểt', 'tuyểt', 'thuyểt', 'xuyểt',
				'uyễt', 'duyễt', 'huyễt', 'khuyễt', 'nguyễt', 'tuyễt', 'thuyễt', 'xuyễt',
				'uyết', 'duyết', 'huyết', 'khuyết', 'nguyết', 'tuyết', 'thuyết', 'xuyết',
				'uyệt', 'duyệt', 'huyệt', 'khuyệt', 'nguyệt', 'tuyệt', 'thuyệt', 'xuyệt',
				'uynh', 'huynh', 'khuynh', 'uỳnh', 'huỳnh', 'khuỳnh', 'uỷnh', 'huỷnh', 'khuỷnh',
				'uỹnh', 'huỹnh', 'khuỹnh', 'uýnh', 'huýnh', 'khuýnh', 'uỵnh', 'huỵnh', 'khuỵnh',
				'ương', 'bương', 'cương', 'chương', 'dương', 'đương', 'gương', 'giương', 'hương', 'khương', 'lương', 'mương', 'nương', 'ngương', 'nhương', 'phương', 'rương', 'sương', 'tương', 'thương', 'trương', 'vương', 'xương',
				'ường', 'bường', 'cường', 'chường', 'dường', 'đường', 'gường', 'giường', 'hường', 'khường', 'lường', 'mường', 'nường', 'ngường', 'nhường', 'phường', 'rường', 'sường', 'tường', 'thường', 'trường', 'vường', 'xường',
				'ưởng', 'bưởng', 'cưởng', 'chưởng', 'dưởng', 'đưởng', 'gưởng', 'giưởng', 'hưởng', 'khưởng', 'lưởng', 'mưởng', 'nưởng', 'ngưởng', 'nhưởng', 'phưởng', 'rưởng', 'sưởng', 'tưởng', 'thưởng', 'trưởng', 'vưởng', 'xưởng',
				'ưỡng', 'bưỡng', 'cưỡng', 'chưỡng', 'dưỡng', 'đưỡng', 'gưỡng', 'giưỡng', 'hưỡng', 'khưỡng', 'lưỡng', 'mưỡng', 'nưỡng', 'ngưỡng', 'nhưỡng', 'phưỡng', 'rưỡng', 'sưỡng', 'tưỡng', 'thưỡng', 'trưỡng', 'vưỡng', 'xưỡng',
				'ướng', 'bướng', 'cướng', 'chướng', 'dướng', 'đướng', 'gướng', 'giướng', 'hướng', 'khướng', 'lướng', 'mướng', 'nướng', 'ngướng', 'nhướng', 'phướng', 'rướng', 'sướng', 'tướng', 'thướng', 'trướng', 'vướng', 'xướng',
				'ượng', 'bượng', 'cượng', 'chượng', 'dượng', 'đượng', 'gượng', 'giượng', 'hượng', 'khượng', 'lượng', 'mượng', 'nượng', 'ngượng', 'nhượng', 'phượng', 'rượng', 'sượng', 'tượng', 'thượng', 'trượng', 'vượng', 'xượng'
			],
			// List of wrong accent placements which need to fix
			'accent_placements'	=> [
				'aì'	=> ['ài', 'Aì', 'Ài', 'AÌ', 'ÀI'],
				'aỉ'	=> ['ải', 'Aỉ', 'Ải', 'AỈ', 'ẢI'],
				'aĩ'	=> ['ãi', 'Aĩ', 'Ãi', 'AĨ', 'ÃI'],
				'aí'	=> ['ái', 'Aí', 'Ái', 'AÍ', 'ÁI'],
				'aị'	=> ['ại', 'Aị', 'Ại', 'AỊ', 'ẠI'],
				'aò'	=> ['ào', 'Aò', 'Ào', 'AÒ', 'ÀO'],
				'aỏ'	=> ['ảo', 'Aỏ', 'Ảo', 'AỎ', 'ẢO'],
				'aõ'	=> ['ão', 'Aõ', 'Ão', 'AÕ', 'ÃO'],
				'aó'	=> ['áo', 'Aó', 'Áo', 'AÓ', 'ÁO'],
				'aọ'	=> ['ạo', 'Aọ', 'Ạo', 'AỌ', 'ẠO'],
				'aù'	=> ['àu', 'Aù', 'Àu', 'AÙ', 'ÀU'],
				'aủ'	=> ['ảu', 'Aủ', 'Ảu', 'AỦ', 'ẢU'],
				'aũ'	=> ['ãu', 'Aũ', 'Ãu', 'AŨ', 'ÃU'],
				'aú'	=> ['áu', 'Aú', 'Áu', 'AÚ', 'ÁU'],
				'aụ'	=> ['ạu', 'Aụ', 'Ạu', 'AỤ', 'ẠU'],
				'aỳ'	=> ['ày', 'Aỳ', 'Ày', 'AỲ', 'ÀY'],
				'aỷ'	=> ['ảy', 'Aỷ', 'Ảy', 'AỶ', 'ẢY'],
				'aỹ'	=> ['ãy', 'Aỹ', 'Ãy', 'AỸ', 'ÃY'],
				'aý'	=> ['áy', 'Aý', 'Áy', 'AÝ', 'ÁY'],
				'aỵ'	=> ['ạy', 'Aỵ', 'Ạy', 'AỴ', 'ẠY'],
				'âù'	=> ['ầu', 'Âù', 'Ầu', 'ÂÙ', 'ẦU'],
				'âủ'	=> ['ẩu', 'Âủ', 'Ẩu', 'ÂỦ', 'ẨU'],
				'âũ'	=> ['ẫu', 'Âũ', 'Ẫu', 'ÂŨ', 'ẪU'],
				'âú'	=> ['ấu', 'Âú', 'Ấu', 'ÂÚ', 'ẤU'],
				'âụ'	=> ['ậu', 'Âụ', 'Ậu', 'ÂỤ', 'ẬU'],
				'âỳ'	=> ['ầy', 'Âỳ', 'Ầy', 'ÂỲ', 'ẦY'],
				'âỷ'	=> ['ẩy', 'Âỷ', 'Ẩy', 'ÂỶ', 'ẨY'],
				'âỹ'	=> ['ẫy', 'Âỹ', 'Ẫy', 'ÂỸ', 'ẪY'],
				'âý'	=> ['ấy', 'Âý', 'Ấy', 'ÂÝ', 'ẤY'],
				'âỵ'	=> ['ậy', 'Âỵ', 'Ậy', 'ÂỴ', 'ẬY'],
				'eò'	=> ['èo', 'Eò', 'Èo', 'EÒ', 'ÈO'],
				'eỏ'	=> ['ẻo', 'Eỏ', 'Ẻo', 'EỎ', 'ẺO'],
				'eõ'	=> ['ẽo', 'Eõ', 'Ẽo', 'EÕ', 'ẼO'],
				'eó'	=> ['éo', 'Eó', 'Éo', 'EÓ', 'ÉO'],
				'eọ'	=> ['ẹo', 'Eọ', 'Ẹo', 'EỌ', 'ẸO'],
				'êù'	=> ['ều', 'Êù', 'Ều', 'ÊÙ', 'ỀU'],
				'êủ'	=> ['ểu', 'Êủ', 'Ểu', 'ÊỦ', 'ỂU'],
				'êũ'	=> ['ễu', 'Êũ', 'Ễu', 'ÊŨ', 'ỄU'],
				'êú'	=> ['ếu', 'Êú', 'Ếu', 'ÊÚ', 'ẾU'],
				'êụ'	=> ['ệu', 'Êụ', 'Ệu', 'ÊỤ', 'ỆU'],
				'ià'	=> ['ìa', 'Ià', 'Ìa', 'IÀ', 'ÌA'],
				'iả'	=> ['ỉa', 'Iả', 'Ỉa', 'IẢ', 'ỈA'],
				'iã'	=> ['ĩa', 'Iã', 'Ĩa', 'IÃ', 'ĨA'],
				'iá'	=> ['ía', 'Iá', 'Ía', 'IÁ', 'ÍA'],
				'iạ'	=> ['ịa', 'Iạ', 'Ịa', 'IẠ', 'ỊA'],
				'iù'	=> ['ìu', 'Iù', 'Ìu', 'IÙ', 'ÌU'],
				'iủ'	=> ['ỉu', 'Iủ', 'Ỉu', 'IỦ', 'ỈU'],
				'iũ'	=> ['ĩu', 'Iũ', 'Ĩu', 'IŨ', 'ĨU'],
				'iú'	=> ['íu', 'Iú', 'Íu', 'IÚ', 'ÍU'],
				'iụ'	=> ['ịu', 'Iụ', 'Ịu', 'IỤ', 'ỊU'],
				'oà'	=> ['òa', 'Oà', 'Òa', 'OÀ', 'ÒA'],
				'oả'	=> ['ỏa', 'Oả', 'Ỏa', 'OẢ', 'ỎA'],
				'oã'	=> ['õa', 'Oã', 'Õa', 'OÃ', 'ÕA'],
				'oá'	=> ['óa', 'Oá', 'Óa', 'OÁ', 'ÓA'],
				'oạ'	=> ['ọa', 'Oạ', 'Ọa', 'OẠ', 'ỌA'],
				'oè'	=> ['òe', 'Oè', 'Òe', 'OÈ', 'ÒE'],
				'oẻ'	=> ['ỏe', 'Oẻ', 'Ỏe', 'OẺ', 'ỎE'],
				'oẽ'	=> ['õe', 'Oẽ', 'Õe', 'OẼ', 'ÕE'],
				'oé'	=> ['óe', 'Oé', 'Óe', 'OÉ', 'ÓE'],
				'oẹ'	=> ['ọe', 'Oẹ', 'Ọe', 'OẸ', 'ỌE'],
				'oì'	=> ['òi', 'Oì', 'Òi', 'OÌ', 'ÒI'],
				'oỉ'	=> ['ỏi', 'Oỉ', 'Ỏi', 'OỈ', 'ỎI'],
				'oĩ'	=> ['õi', 'Oĩ', 'Õi', 'OĨ', 'ÕI'],
				'oí'	=> ['ói', 'Oí', 'Ói', 'OÍ', 'ÓI'],
				'oị'	=> ['ọi', 'Oị', 'Ọi', 'OỊ', 'ỌI'],
				'ôì'	=> ['ồi', 'Ôì', 'Ồi', 'ÔÌ', 'ỒI'],
				'ôỉ'	=> ['ổi', 'Ôỉ', 'Ổi', 'ÔỈ', 'ỔI'],
				'ôĩ'	=> ['ỗi', 'Ôĩ', 'Ỗi', 'ÔĨ', 'ỖI'],
				'ôí'	=> ['ối', 'Ôí', 'Ối', 'ÔÍ', 'ỐI'],
				'ôị'	=> ['ội', 'Ôị', 'Ội', 'ÔỊ', 'ỘI'],
				'ơì'	=> ['ời', 'Ơì', 'Ời', 'ƠÌ', 'ỜI'],
				'ơỉ'	=> ['ởi', 'Ơỉ', 'Ởi', 'ƠỈ', 'ỞI'],
				'ơĩ'	=> ['ỡi', 'Ơĩ', 'Ỡi', 'ƠĨ', 'ỠI'],
				'ơí'	=> ['ới', 'Ơí', 'Ới', 'ƠÍ', 'ỚI'],
				'ơị'	=> ['ợi', 'Ơị', 'Ợi', 'ƠỊ', 'ỢI'],
				'uà'	=> ['ùa', 'Uà', 'Ùa', 'UÀ', 'ÙA'],
				'uả'	=> ['ủa', 'Uả', 'Ủa', 'UẢ', 'ỦA'],
				'uã'	=> ['ũa', 'Uã', 'Ũa', 'UÃ', 'ŨA'],
				'uá'	=> ['úa', 'Uá', 'Úa', 'UÁ', 'ÚA'],
				'uạ'	=> ['ụa', 'Uạ', 'Ụa', 'UẠ', 'ỤA'],
				'ùê'	=> ['uề', 'Ùê', 'Uề', 'ÙÊ', 'UỀ'],
				'ủê'	=> ['uể', 'Ủê', 'Uể', 'ỦÊ', 'UỂ'],
				'ũê'	=> ['uễ', 'Ũê', 'Uễ', 'ŨÊ', 'UỄ'],
				'úê'	=> ['uế', 'Úê', 'Uế', 'ÚÊ', 'UẾ'],
				'ụê'	=> ['uệ', 'Ụê', 'Uệ', 'ỤÊ', 'UỆ'],
				'uì'	=> ['ùi', 'Uì', 'Ùi', 'UÌ', 'ÙI'],
				'uỉ'	=> ['ủi', 'Uỉ', 'Ủi', 'UỈ', 'ỦI'],
				'uĩ'	=> ['ũi', 'Uĩ', 'Ũi', 'UĨ', 'ŨI'],
				'uí'	=> ['úi', 'Uí', 'Úi', 'UÍ', 'ÚI'],
				'uị'	=> ['ụi', 'Uị', 'Ụi', 'UỊ', 'ỤI'],
				'ùơ'	=> ['uờ', 'Ùơ', 'Uờ', 'ÙƠ', 'UỜ'],
				'ủơ'	=> ['uở', 'Ủơ', 'Uở', 'ỦƠ', 'UỞ'],
				'ũơ'	=> ['uỡ', 'Ũơ', 'Uỡ', 'ŨƠ', 'UỠ'],
				'úơ'	=> ['uớ', 'Úơ', 'Uớ', 'ÚƠ', 'UỚ'],
				'ụơ'	=> ['uợ', 'Ụơ', 'Uợ', 'ỤƠ', 'UỢ'],
				'uỳ'	=> ['ùy', 'Uỳ', 'Ùy', 'UỲ', 'ÙY'],
				'uỷ'	=> ['ủy', 'Uỷ', 'Ủy', 'UỶ', 'ỦY'],
				'uỹ'	=> ['ũy', 'Uỹ', 'Ũy', 'UỸ', 'ŨY'],
				'uý'	=> ['úy', 'Uý', 'Úy', 'UÝ', 'ÚY'],
				'uỵ'	=> ['ụy', 'Uỵ', 'Ụy', 'UỴ', 'ỤY'],
				'ưà'	=> ['ừa', 'Ưà', 'Ừa', 'ƯÀ', 'ỪA'],
				'ưả'	=> ['ửa', 'Ưả', 'Ửa', 'ƯẢ', 'ỬA'],
				'ưã'	=> ['ữa', 'Ưã', 'Ữa', 'ƯÃ', 'ỮA'],
				'ưá'	=> ['ứa', 'Ưá', 'Ứa', 'ƯÁ', 'ỨA'],
				'ưạ'	=> ['ựa', 'Ưạ', 'Ựa', 'ƯẠ', 'ỰA'],
				'ưì'	=> ['ừi', 'Ưì', 'Ừi', 'ƯÌ', 'ỪI'],
				'ưỉ'	=> ['ửi', 'Ưỉ', 'Ửi', 'ƯỈ', 'ỬI'],
				'ưĩ'	=> ['ữi', 'Ưĩ', 'Ữi', 'ƯĨ', 'ỮI'],
				'ưí'	=> ['ứi', 'Ưí', 'Ứi', 'ƯÍ', 'ỨI'],
				'ưị'	=> ['ựi', 'Ưị', 'Ựi', 'ƯỊ', 'ỰI'],
				'ưù'	=> ['ừu', 'Ưù', 'Ừu', 'ƯÙ', 'ỪU'],
				'ưủ'	=> ['ửu', 'Ưủ', 'Ửu', 'ƯỦ', 'ỬU'],
				'ưũ'	=> ['ữu', 'Ưũ', 'Ữu', 'ƯŨ', 'ỮU'],
				'ưú'	=> ['ứu', 'Ưú', 'Ứu', 'ƯÚ', 'ỨU'],
				'ưụ'	=> ['ựu', 'Ưụ', 'Ựu', 'ƯỤ', 'ỰU'],
				'ìêc'	=> ['iềc', 'Ìêc', 'Iềc', 'ÌÊC', 'IỀC'],
				'ỉêc'	=> ['iểc', 'Ỉêc', 'Iểc', 'ỈÊC', 'IỂC'],
				'ĩêc'	=> ['iễc', 'Ĩêc', 'Iễc', 'ĨÊC', 'IỄC'],
				'íêc'	=> ['iếc', 'Íêc', 'Iếc', 'ÍÊC', 'IẾC'],
				'ịêc'	=> ['iệc', 'Ịêc', 'Iệc', 'ỊÊC', 'IỆC'],
				'ìêm'	=> ['iềm', 'Ìêm', 'Iềm', 'ÌÊM', 'IỀM'],
				'ỉêm'	=> ['iểm', 'Ỉêm', 'Iểm', 'ỈÊM', 'IỂM'],
				'ĩêm'	=> ['iễm', 'Ĩêm', 'Iễm', 'ĨÊM', 'IỄM'],
				'íêm'	=> ['iếm', 'Íêm', 'Iếm', 'ÍÊM', 'IẾM'],
				'ịêm'	=> ['iệm', 'Ịêm', 'Iệm', 'ỊÊM', 'IỆM'],
				'ìên'	=> ['iền', 'Ìên', 'Iền', 'ÌÊN', 'IỀN'],
				'ỉên'	=> ['iển', 'Ỉên', 'Iển', 'ỈÊN', 'IỂN'],
				'ĩên'	=> ['iễn', 'Ĩên', 'Iễn', 'ĨÊN', 'IỄN'],
				'íên'	=> ['iến', 'Íên', 'Iến', 'ÍÊN', 'IẾN'],
				'ịên'	=> ['iện', 'Ịên', 'Iện', 'ỊÊN', 'IỆN'],
				'ìêp'	=> ['iềp', 'Ìêp', 'Iềp', 'ÌÊP', 'IỀP'],
				'ỉêp'	=> ['iểp', 'Ỉêp', 'Iểp', 'ỈÊP', 'IỂP'],
				'ĩêp'	=> ['iễp', 'Ĩêp', 'Iễp', 'ĨÊP', 'IỄP'],
				'íêp'	=> ['iếp', 'Íêp', 'Iếp', 'ÍÊP', 'IẾP'],
				'ịêp'	=> ['iệp', 'Ịêp', 'Iệp', 'ỊÊP', 'IỆP'],
				'ìêt'	=> ['iềt', 'Ìêt', 'Iềt', 'ÌÊT', 'IỀT'],
				'ỉêt'	=> ['iểt', 'Ỉêt', 'Iểt', 'ỈÊT', 'IỂT'],
				'ĩêt'	=> ['iễt', 'Ĩêt', 'Iễt', 'ĨÊT', 'IỄT'],
				'íêt'	=> ['iết', 'Íêt', 'Iết', 'ÍÊT', 'IẾT'],
				'ịêt'	=> ['iệt', 'Ịêt', 'Iệt', 'ỊÊT', 'IỆT'],
				'ìêu'	=> ['iều', 'Ìêu', 'Iều', 'ÌÊU', 'IỀU'],
				'ỉêu'	=> ['iểu', 'Ỉêu', 'Iểu', 'ỈÊU', 'IỂU'],
				'ĩêu'	=> ['iễu', 'Ĩêu', 'Iễu', 'ĨÊU', 'IỄU'],
				'íêu'	=> ['iếu', 'Íêu', 'Iếu', 'ÍÊU', 'IẾU'],
				'ịêu'	=> ['iệu', 'Ịêu', 'Iệu', 'ỊÊU', 'IỆU'],
				'iêù'	=> ['iều', 'Iêù', 'Iều', 'IÊÙ', 'IỀU'],
				'iêủ'	=> ['iểu', 'Iêủ', 'Iểu', 'IÊỦ', 'IỂU'],
				'iêũ'	=> ['iễu', 'Iêũ', 'Iễu', 'IÊŨ', 'IỄU'],
				'iêú'	=> ['iếu', 'Iêú', 'Iếu', 'IÊÚ', 'IẾU'],
				'iêụ'	=> ['iệu', 'Iêụ', 'Iệu', 'IÊỤ', 'IỆU'],
				'òac'	=> ['oàc', 'Òac', 'Oàc', 'ÒAC', 'OÀC'],
				'ỏac'	=> ['oảc', 'Ỏac', 'Oảc', 'ỎAC', 'OẢC'],
				'õac'	=> ['oãc', 'Õac', 'Oãc', 'ÕAC', 'OÃC'],
				'óac'	=> ['oác', 'Óac', 'Oác', 'ÓAC', 'OÁC'],
				'ọac'	=> ['oạc', 'Ọac', 'Oạc', 'ỌAC', 'OẠC'],
				'òai'	=> ['oài', 'Òai', 'Oài', 'ÒAI', 'OÀI'],
				'ỏai'	=> ['oải', 'Ỏai', 'Oải', 'ỎAI', 'OẢI'],
				'õai'	=> ['oãi', 'Õai', 'Oãi', 'ÕAI', 'OÃI'],
				'óai'	=> ['oái', 'Óai', 'Oái', 'ÓAI', 'OÁI'],
				'ọai'	=> ['oại', 'Ọai', 'Oại', 'ỌAI', 'OẠI'],
				'oaì'	=> ['oài', 'Oaì', 'Oài', 'OAÌ', 'OÀI'],
				'oaỉ'	=> ['oải', 'Oaỉ', 'Oải', 'OAỈ', 'OẢI'],
				'oaĩ'	=> ['oãi', 'Oaĩ', 'Oãi', 'OAĨ', 'OÃI'],
				'oaí'	=> ['oái', 'Oaí', 'Oái', 'OAÍ', 'OÁI'],
				'oaị'	=> ['oại', 'Oaị', 'Oại', 'OAỊ', 'OẠI'],
				'òan'	=> ['oàn', 'Òan', 'Oàn', 'ÒAN', 'OÀN'],
				'ỏan'	=> ['oản', 'Ỏan', 'Oản', 'ỎAN', 'OẢN'],
				'õan'	=> ['oãn', 'Õan', 'Oãn', 'ÕAN', 'OÃN'],
				'óan'	=> ['oán', 'Óan', 'Oán', 'ÓAN', 'OÁN'],
				'ọan'	=> ['oạn', 'Ọan', 'Oạn', 'ỌAN', 'OẠN'],
				'òat'	=> ['oàt', 'Òat', 'Oàt', 'ÒAT', 'OÀT'],
				'ỏat'	=> ['oảt', 'Ỏat', 'Oảt', 'ỎAT', 'OẢT'],
				'õat'	=> ['oãt', 'Õat', 'Oãt', 'ÕAT', 'OÃT'],
				'óat'	=> ['oát', 'Óat', 'Oát', 'ÓAT', 'OÁT'],
				'ọat'	=> ['oạt', 'Ọat', 'Oạt', 'ỌAT', 'OẠT'],
				'òay'	=> ['oày', 'Òay', 'Oày', 'ÒAY', 'OÀY'],
				'ỏay'	=> ['oảy', 'Ỏay', 'Oảy', 'ỎAY', 'OẢY'],
				'õay'	=> ['oãy', 'Õay', 'Oãy', 'ÕAY', 'OÃY'],
				'óay'	=> ['oáy', 'Óay', 'Oáy', 'ÓAY', 'OÁY'],
				'ọay'	=> ['oạy', 'Ọay', 'Oạy', 'ỌAY', 'OẠY'],
				'oaỳ'	=> ['oày', 'Oaỳ', 'Oày', 'OAỲ', 'OÀY'],
				'oaỷ'	=> ['oảy', 'Oaỷ', 'Oảy', 'OAỶ', 'OẢY'],
				'oaỹ'	=> ['oãy', 'Oaỹ', 'Oãy', 'OAỸ', 'OÃY'],
				'oaý'	=> ['oáy', 'Oaý', 'Oáy', 'OAÝ', 'OÁY'],
				'oaỵ'	=> ['oạy', 'Oaỵ', 'Oạy', 'OAỴ', 'OẠY'],
				'òăc'	=> ['oằc', 'Òăc', 'Oằc', 'ÒĂC', 'OẰC'],
				'ỏăc'	=> ['oẳc', 'Ỏăc', 'Oẳc', 'ỎĂC', 'OẲC'],
				'õăc'	=> ['oẵc', 'Õăc', 'Oẵc', 'ÕĂC', 'OẴC'],
				'óăc'	=> ['oắc', 'Óăc', 'Oắc', 'ÓĂC', 'OẮC'],
				'ọăc'	=> ['oặc', 'Ọăc', 'Oặc', 'ỌĂC', 'OẶC'],
				'òăn'	=> ['oằn', 'Òăn', 'Oằn', 'ÒĂN', 'OẰN'],
				'ỏăn'	=> ['oẳn', 'Ỏăn', 'Oẳn', 'ỎĂN', 'OẲN'],
				'õăn'	=> ['oẵn', 'Õăn', 'Oẵn', 'ÕĂN', 'OẴN'],
				'óăn'	=> ['oắn', 'Óăn', 'Oắn', 'ÓĂN', 'OẮN'],
				'ọăn'	=> ['oặn', 'Ọăn', 'Oặn', 'ỌĂN', 'OẶN'],
				'òăt'	=> ['oằt', 'Òăt', 'Oằt', 'ÒĂT', 'OẰT'],
				'ỏăt'	=> ['oẳt', 'Ỏăt', 'Oẳt', 'ỎĂT', 'OẲT'],
				'õăt'	=> ['oẵt', 'Õăt', 'Oẵt', 'ÕĂT', 'OẴT'],
				'óăt'	=> ['oắt', 'Óăt', 'Oắt', 'ÓĂT', 'OẮT'],
				'ọăt'	=> ['oặt', 'Ọăt', 'Oặt', 'ỌĂT', 'OẶT'],
				'òen'	=> ['oèn', 'Òen', 'Oèn', 'ÒEN', 'OÈN'],
				'ỏen'	=> ['oẻn', 'Ỏen', 'Oẻn', 'ỎEN', 'OẺN'],
				'õen'	=> ['oẽn', 'Õen', 'Oẽn', 'ÕEN', 'OẼN'],
				'óen'	=> ['oén', 'Óen', 'Oén', 'ÓEN', 'OÉN'],
				'ọen'	=> ['oẹn', 'Ọen', 'Oẹn', 'ỌEN', 'OẸN'],
				'ùân'	=> ['uần', 'Ùân', 'Uần', 'ÙÂN', 'UẦN'],
				'ủân'	=> ['uẩn', 'Ủân', 'Uẩn', 'ỦÂN', 'UẨN'],
				'ũân'	=> ['uẫn', 'Ũân', 'Uẫn', 'ŨÂN', 'UẪN'],
				'úân'	=> ['uấn', 'Úân', 'Uấn', 'ÚÂN', 'UẤN'],
				'ụân'	=> ['uận', 'Ụân', 'Uận', 'ỤÂN', 'UẬN'],
				'ùât'	=> ['uầt', 'Ùât', 'Uầt', 'ÙÂT', 'UẦT'],
				'ủât'	=> ['uẩt', 'Ủât', 'Uẩt', 'ỦÂT', 'UẨT'],
				'ũât'	=> ['uẫt', 'Ũât', 'Uẫt', 'ŨÂT', 'UẪT'],
				'úât'	=> ['uất', 'Úât', 'Uất', 'ÚÂT', 'UẤT'],
				'ụât'	=> ['uật', 'Ụât', 'Uật', 'ỤÂT', 'UẬT'],
				'ùây'	=> ['uầy', 'Ùây', 'Uầy', 'ÙÂY', 'UẦY'],
				'ủây'	=> ['uẩy', 'Ủây', 'Uẩy', 'ỦÂY', 'UẨY'],
				'ũây'	=> ['uẫy', 'Ũây', 'Uẫy', 'ŨÂY', 'UẪY'],
				'úây'	=> ['uấy', 'Úây', 'Uấy', 'ÚÂY', 'UẤY'],
				'ụây'	=> ['uậy', 'Ụây', 'Uậy', 'ỤÂY', 'UẬY'],
				'uâỳ'	=> ['uầy', 'Uâỳ', 'Uầy', 'UÂỲ', 'UẦY'],
				'uâỷ'	=> ['uẩy', 'Uâỷ', 'Uẩy', 'UÂỶ', 'UẨY'],
				'uâỹ'	=> ['uẫy', 'Uâỹ', 'Uẫy', 'UÂỸ', 'UẪY'],
				'uâý'	=> ['uấy', 'Uâý', 'Uấy', 'UÂÝ', 'UẤY'],
				'uâỵ'	=> ['uậy', 'Uâỵ', 'Uậy', 'UÂỴ', 'UẬY'],
				'ùôc'	=> ['uồc', 'Ùôc', 'Uồc', 'ÙÔC', 'UỒC'],
				'ủôc'	=> ['uổc', 'Ủôc', 'Uổc', 'ỦÔC', 'UỔC'],
				'ũôc'	=> ['uỗc', 'Ũôc', 'Uỗc', 'ŨÔC', 'UỖC'],
				'úôc'	=> ['uốc', 'Úôc', 'Uốc', 'ÚÔC', 'UỐC'],
				'ụôc'	=> ['uộc', 'Ụôc', 'Uộc', 'ỤÔC', 'UỘC'],
				'ùôi'	=> ['uồi', 'Ùôi', 'Uồi', 'ÙÔI', 'UỒI'],
				'ủôi'	=> ['uổi', 'Ủôi', 'Uổi', 'ỦÔI', 'UỔI'],
				'ũôi'	=> ['uỗi', 'Ũôi', 'Uỗi', 'ŨÔI', 'UỖI'],
				'úôi'	=> ['uối', 'Úôi', 'Uối', 'ÚÔI', 'UỐI'],
				'ụôi'	=> ['uội', 'Ụôi', 'Uội', 'ỤÔI', 'UỘI'],
				'uôì'	=> ['uồi', 'Uôì', 'Uồi', 'UÔÌ', 'UỒI'],
				'uôỉ'	=> ['uổi', 'Uôỉ', 'Uổi', 'UÔỈ', 'UỔI'],
				'uôĩ'	=> ['uỗi', 'Uôĩ', 'Uỗi', 'UÔĨ', 'UỖI'],
				'uôí'	=> ['uối', 'Uôí', 'Uối', 'UÔÍ', 'UỐI'],
				'uôị'	=> ['uội', 'Uôị', 'Uội', 'UÔỊ', 'UỘI'],
				'ùôm'	=> ['uồm', 'Ùôm', 'Uồm', 'ÙÔM', 'UỒM'],
				'ủôm'	=> ['uổm', 'Ủôm', 'Uổm', 'ỦÔM', 'UỔM'],
				'ũôm'	=> ['uỗm', 'Ũôm', 'Uỗm', 'ŨÔM', 'UỖM'],
				'úôm'	=> ['uốm', 'Úôm', 'Uốm', 'ÚÔM', 'UỐM'],
				'ụôm'	=> ['uộm', 'Ụôm', 'Uộm', 'ỤÔM', 'UỘM'],
				'ùôn'	=> ['uồn', 'Ùôn', 'Uồn', 'ÙÔN', 'UỒN'],
				'ủôn'	=> ['uổn', 'Ủôn', 'Uổn', 'ỦÔN', 'UỔN'],
				'ũôn'	=> ['uỗn', 'Ũôn', 'Uỗn', 'ŨÔN', 'UỖN'],
				'úôn'	=> ['uốn', 'Úôn', 'Uốn', 'ÚÔN', 'UỐN'],
				'ụôn'	=> ['uộn', 'Ụôn', 'Uộn', 'ỤÔN', 'UỘN'],
				'ùôt'	=> ['uồt', 'Ùôt', 'Uồt', 'ÙÔT', 'UỒT'],
				'ủôt'	=> ['uổt', 'Ủôt', 'Uổt', 'ỦÔT', 'UỔT'],
				'ũôt'	=> ['uỗt', 'Ũôt', 'Uỗt', 'ŨÔT', 'UỖT'],
				'úôt'	=> ['uốt', 'Úôt', 'Uốt', 'ÚÔT', 'UỐT'],
				'ụôt'	=> ['uột', 'Ụôt', 'Uột', 'ỤÔT', 'UỘT'],
				'ùya'	=> ['uỳa', 'Ùya', 'Uỳa', 'ÙYA', 'UỲA'],
				'ủya'	=> ['uỷa', 'Ủya', 'Uỷa', 'ỦYA', 'UỶA'],
				'ũya'	=> ['uỹa', 'Ũya', 'Uỹa', 'ŨYA', 'UỸA'],
				'úya'	=> ['uýa', 'Úya', 'Uýa', 'ÚYA', 'UÝA'],
				'ụya'	=> ['uỵa', 'Ụya', 'Uỵa', 'ỤYA', 'UỴA'],
				'uyà'	=> ['uỳa', 'Uyà', 'Uỳa', 'UYÀ', 'UỲA'],
				'uyả'	=> ['uỷa', 'Uyả', 'Uỷa', 'UYẢ', 'UỶA'],
				'uyã'	=> ['uỹa', 'Uyã', 'Uỹa', 'UYÃ', 'UỸA'],
				'uyá'	=> ['uýa', 'Uyá', 'Uýa', 'UYÁ', 'UÝA'],
				'uyạ'	=> ['uỵa', 'Uyạ', 'Uỵa', 'UYẠ', 'UỴA'],
				'ùyt'	=> ['uỳt', 'Ùyt', 'Uỳt', 'ÙYT', 'UỲT'],
				'ủyt'	=> ['uỷt', 'Ủyt', 'Uỷt', 'ỦYT', 'UỶT'],
				'ũyt'	=> ['uỹt', 'Ũyt', 'Uỹt', 'ŨYT', 'UỸT'],
				'úyt'	=> ['uýt', 'Úyt', 'Uýt', 'ÚYT', 'UÝT'],
				'ụyt'	=> ['uỵt', 'Ụyt', 'Uỵt', 'ỤYT', 'UỴT'],
				'ùyu'	=> ['uỳu', 'Ùyu', 'Uỳu', 'ÙYU', 'UỲU'],
				'ủyu'	=> ['uỷu', 'Ủyu', 'Uỷu', 'ỦYU', 'UỶU'],
				'ũyu'	=> ['uỹu', 'Ũyu', 'Uỹu', 'ŨYU', 'UỸU'],
				'úyu'	=> ['uýu', 'Úyu', 'Uýu', 'ÚYU', 'UÝU'],
				'ụyu'	=> ['uỵu', 'Ụyu', 'Uỵu', 'ỤYU', 'UỴU'],
				'uyù'	=> ['uỳu', 'Uyù', 'Uỳu', 'UYÙ', 'UỲU'],
				'uyủ'	=> ['uỷu', 'Uyủ', 'Uỷu', 'UYỦ', 'UỶU'],
				'uyũ'	=> ['uỹu', 'Uyũ', 'Uỹu', 'UYŨ', 'UỸU'],
				'uyú'	=> ['uýu', 'Uyú', 'Uýu', 'UYÚ', 'UÝU'],
				'uyụ'	=> ['uỵu', 'Uyụ', 'Uỵu', 'UYỤ', 'UỴU'],
				'ừơc'	=> ['ườc', 'Ừơc', 'Ườc', 'ỪƠC', 'ƯỜC'],
				'ửơc'	=> ['ưởc', 'Ửơc', 'Ưởc', 'ỬƠC', 'ƯỞC'],
				'ữơc'	=> ['ưỡc', 'Ữơc', 'Ưỡc', 'ỮƠC', 'ƯỠC'],
				'ứơc'	=> ['ước', 'Ứơc', 'Ước', 'ỨƠC', 'ƯỚC'],
				'ựơc'	=> ['ược', 'Ựơc', 'Ược', 'ỰƠC', 'ƯỢC'],
				'ừơi'	=> ['ười', 'Ừơi', 'Ười', 'ỪƠI', 'ƯỜI'],
				'ửơi'	=> ['ưởi', 'Ửơi', 'Ưởi', 'ỬƠI', 'ƯỞI'],
				'ữơi'	=> ['ưỡi', 'Ữơi', 'Ưỡi', 'ỮƠI', 'ƯỠI'],
				'ứơi'	=> ['ưới', 'Ứơi', 'Ưới', 'ỨƠI', 'ƯỚI'],
				'ựơi'	=> ['ượi', 'Ựơi', 'Ượi', 'ỰƠI', 'ƯỢI'],
				'ươì'	=> ['ười', 'Ươì', 'Ười', 'ƯƠÌ', 'ƯỜI'],
				'ươỉ'	=> ['ưởi', 'Ươỉ', 'Ưởi', 'ƯƠỈ', 'ƯỞI'],
				'ươĩ'	=> ['ưỡi', 'Ươĩ', 'Ưỡi', 'ƯƠĨ', 'ƯỠI'],
				'ươí'	=> ['ưới', 'Ươí', 'Ưới', 'ƯƠÍ', 'ƯỚI'],
				'ươị'	=> ['ượi', 'Ươị', 'Ượi', 'ƯƠỊ', 'ƯỢI'],
				'ừơm'	=> ['ườm', 'Ừơm', 'Ườm', 'ỪƠM', 'ƯỜM'],
				'ửơm'	=> ['ưởm', 'Ửơm', 'Ưởm', 'ỬƠM', 'ƯỞM'],
				'ữơm'	=> ['ưỡm', 'Ữơm', 'Ưỡm', 'ỮƠM', 'ƯỠM'],
				'ứơm'	=> ['ướm', 'Ứơm', 'Ướm', 'ỨƠM', 'ƯỚM'],
				'ựơm'	=> ['ượm', 'Ựơm', 'Ượm', 'ỰƠM', 'ƯỢM'],
				'ừơn'	=> ['ườn', 'Ừơn', 'Ườn', 'ỪƠN', 'ƯỜN'],
				'ửơn'	=> ['ưởn', 'Ửơn', 'Ưởn', 'ỬƠN', 'ƯỞN'],
				'ữơn'	=> ['ưỡn', 'Ữơn', 'Ưỡn', 'ỮƠN', 'ƯỠN'],
				'ứơn'	=> ['ướn', 'Ứơn', 'Ướn', 'ỨƠN', 'ƯỚN'],
				'ựơn'	=> ['ượn', 'Ựơn', 'Ượn', 'ỰƠN', 'ƯỢN'],
				'ừơp'	=> ['ườp', 'Ừơp', 'Ườp', 'ỪƠP', 'ƯỜP'],
				'ửơp'	=> ['ưởp', 'Ửơp', 'Ưởp', 'ỬƠP', 'ƯỞP'],
				'ữơp'	=> ['ưỡp', 'Ữơp', 'Ưỡp', 'ỮƠP', 'ƯỠP'],
				'ứơp'	=> ['ướp', 'Ứơp', 'Ướp', 'ỨƠP', 'ƯỚP'],
				'ựơp'	=> ['ượp', 'Ựơp', 'Ượp', 'ỰƠP', 'ƯỢP'],
				'ừơt'	=> ['ườt', 'Ừơt', 'Ườt', 'ỪƠT', 'ƯỜT'],
				'ửơt'	=> ['ưởt', 'Ửơt', 'Ưởt', 'ỬƠT', 'ƯỞT'],
				'ữơt'	=> ['ưỡt', 'Ữơt', 'Ưỡt', 'ỮƠT', 'ƯỠT'],
				'ứơt'	=> ['ướt', 'Ứơt', 'Ướt', 'ỨƠT', 'ƯỚT'],
				'ựơt'	=> ['ượt', 'Ựơt', 'Ượt', 'ỰƠT', 'ƯỢT'],
				'ừơu'	=> ['ườu', 'Ừơu', 'Ườu', 'ỪƠU', 'ƯỜU'],
				'ửơu'	=> ['ưởu', 'Ửơu', 'Ưởu', 'ỬƠU', 'ƯỞU'],
				'ữơu'	=> ['ưỡu', 'Ữơu', 'Ưỡu', 'ỮƠU', 'ƯỠU'],
				'ứơu'	=> ['ướu', 'Ứơu', 'Ướu', 'ỨƠU', 'ƯỚU'],
				'ựơu'	=> ['ượu', 'Ựơu', 'Ượu', 'ỰƠU', 'ƯỢU'],
				'ươù'	=> ['ườu', 'Ươù', 'Ườu', 'ƯƠÙ', 'ƯỜU'],
				'ươủ'	=> ['ưởu', 'Ươủ', 'Ưởu', 'ƯƠỦ', 'ƯỞU'],
				'ươũ'	=> ['ưỡu', 'Ươũ', 'Ưỡu', 'ƯƠŨ', 'ƯỠU'],
				'ươú'	=> ['ướu', 'Ươú', 'Ướu', 'ƯƠÚ', 'ƯỚU'],
				'ươụ'	=> ['ượu', 'Ươụ', 'Ượu', 'ƯƠỤ', 'ƯỢU'],
				'ỳên'	=> ['yền', 'Ỳên', 'Yền', 'ỲÊN', 'YỀN'],
				'ỷên'	=> ['yển', 'Ỷên', 'Yển', 'ỶÊN', 'YỂN'],
				'ỹên'	=> ['yễn', 'Ỹên', 'Yễn', 'ỸÊN', 'YỄN'],
				'ýên'	=> ['yến', 'Ýên', 'Yến', 'ÝÊN', 'YẾN'],
				'ỵên'	=> ['yện', 'Ỵên', 'Yện', 'ỴÊN', 'YỆN'],
				'ỳêt'	=> ['yềt', 'Ỳêt', 'Yềt', 'ỲÊT', 'YỀT'],
				'ỷêt'	=> ['yểt', 'Ỷêt', 'Yểt', 'ỶÊT', 'YỂT'],
				'ỹêt'	=> ['yễt', 'Ỹêt', 'Yễt', 'ỸÊT', 'YỄT'],
				'ýêt'	=> ['yết', 'Ýêt', 'Yết', 'ÝÊT', 'YẾT'],
				'ỵêt'	=> ['yệt', 'Ỵêt', 'Yệt', 'ỴÊT', 'YỆT'],
				'ỳêu'	=> ['yều', 'Ỳêu', 'Yều', 'ỲÊU', 'YỀU'],
				'ỷêu'	=> ['yểu', 'Ỷêu', 'Yểu', 'ỶÊU', 'YỂU'],
				'ỹêu'	=> ['yễu', 'Ỹêu', 'Yễu', 'ỸÊU', 'YỄU'],
				'ýêu'	=> ['yếu', 'Ýêu', 'Yếu', 'ÝÊU', 'YẾU'],
				'ỵêu'	=> ['yệu', 'Ỵêu', 'Yệu', 'ỴÊU', 'YỆU'],
				'yêù'	=> ['yều', 'Yêù', 'Yều', 'YÊÙ', 'YỀU'],
				'yêủ'	=> ['yểu', 'Yêủ', 'Yểu', 'YÊỦ', 'YỂU'],
				'yêũ'	=> ['yễu', 'Yêũ', 'Yễu', 'YÊŨ', 'YỄU'],
				'yêú'	=> ['yếu', 'Yêú', 'Yếu', 'YÊÚ', 'YẾU'],
				'yêụ'	=> ['yệu', 'Yêụ', 'Yệu', 'YÊỤ', 'YỆU'],
				'ìêng'	=> ['iềng', 'Ìêng', 'Iềng', 'ÌÊNG', 'IỀNG'],
				'ỉêng'	=> ['iểng', 'Ỉêng', 'Iểng', 'ỈÊNG', 'IỂNG'],
				'ĩêng'	=> ['iễng', 'Ĩêng', 'Iễng', 'ĨÊNG', 'IỄNG'],
				'íêng'	=> ['iếng', 'Íêng', 'Iếng', 'ÍÊNG', 'IẾNG'],
				'ịêng'	=> ['iệng', 'Ịêng', 'Iệng', 'ỊÊNG', 'IỆNG'],
				'òang'	=> ['oàng', 'Òang', 'Oàng', 'ÒANG', 'OÀNG'],
				'ỏang'	=> ['oảng', 'Ỏang', 'Oảng', 'ỎANG', 'OẢNG'],
				'õang'	=> ['oãng', 'Õang', 'Oãng', 'ÕANG', 'OÃNG'],
				'óang'	=> ['oáng', 'Óang', 'Oáng', 'ÓANG', 'OÁNG'],
				'ọang'	=> ['oạng', 'Ọang', 'Oạng', 'ỌANG', 'OẠNG'],
				'òanh'	=> ['oành', 'Òanh', 'Oành', 'ÒANH', 'OÀNH'],
				'ỏanh'	=> ['oảnh', 'Ỏanh', 'Oảnh', 'ỎANH', 'OẢNH'],
				'õanh'	=> ['oãnh', 'Õanh', 'Oãnh', 'ÕANH', 'OÃNH'],
				'óanh'	=> ['oánh', 'Óanh', 'Oánh', 'ÓANH', 'OÁNH'],
				'ọanh'	=> ['oạnh', 'Ọanh', 'Oạnh', 'ỌANH', 'OẠNH'],
				'òăng'	=> ['oằng', 'Òăng', 'Oằng', 'ÒĂNG', 'OẰNG'],
				'ỏăng'	=> ['oẳng', 'Ỏăng', 'Oẳng', 'ỎĂNG', 'OẲNG'],
				'õăng'	=> ['oẵng', 'Õăng', 'Oẵng', 'ÕĂNG', 'OẴNG'],
				'óăng'	=> ['oắng', 'Óăng', 'Oắng', 'ÓĂNG', 'OẮNG'],
				'ọăng'	=> ['oặng', 'Ọăng', 'Oặng', 'ỌĂNG', 'OẶNG'],
				'òong'	=> ['oòng', 'Òong', 'Oòng', 'ÒONG', 'OÒNG'],
				'ỏong'	=> ['oỏng', 'Ỏong', 'Oỏng', 'ỎONG', 'OỎNG'],
				'õong'	=> ['oõng', 'Õong', 'Oõng', 'ÕONG', 'OÕNG'],
				'óong'	=> ['oóng', 'Óong', 'Oóng', 'ÓONG', 'OÓNG'],
				'ọong'	=> ['oọng', 'Ọong', 'Oọng', 'ỌONG', 'OỌNG'],
				'ùâng'	=> ['uầng', 'Ùâng', 'Uầng', 'ÙÂNG', 'UẦNG'],
				'ủâng'	=> ['uẩng', 'Ủâng', 'Uẩng', 'ỦÂNG', 'UẨNG'],
				'ũâng'	=> ['uẫng', 'Ũâng', 'Uẫng', 'ŨÂNG', 'UẪNG'],
				'úâng'	=> ['uấng', 'Úâng', 'Uấng', 'ÚÂNG', 'UẤNG'],
				'ụâng'	=> ['uậng', 'Ụâng', 'Uậng', 'ỤÂNG', 'UẬNG'],
				'ùông'	=> ['uồng', 'Ùông', 'Uồng', 'ÙÔNG', 'UỒNG'],
				'ủông'	=> ['uổng', 'Ủông', 'Uổng', 'ỦÔNG', 'UỔNG'],
				'ũông'	=> ['uỗng', 'Ũông', 'Uỗng', 'ŨÔNG', 'UỖNG'],
				'úông'	=> ['uống', 'Úông', 'Uống', 'ÚÔNG', 'UỐNG'],
				'ụông'	=> ['uộng', 'Ụông', 'Uộng', 'ỤÔNG', 'UỘNG'],
				'ùyên'	=> ['uyền', 'Ùyên', 'Uyền', 'ÙYÊN', 'UYỀN'],
				'ủyên'	=> ['uyển', 'Ủyên', 'Uyển', 'ỦYÊN', 'UYỂN'],
				'ũyên'	=> ['uyễn', 'Ũyên', 'Uyễn', 'ŨYÊN', 'UYỄN'],
				'úyên'	=> ['uyến', 'Úyên', 'Uyến', 'ÚYÊN', 'UYẾN'],
				'ụyên'	=> ['uyện', 'Ụyên', 'Uyện', 'ỤYÊN', 'UYỆN'],
				'uỳên'	=> ['uyền', 'Uỳên', 'Uyền', 'UỲÊN', 'UYỀN'],
				'uỷên'	=> ['uyển', 'Uỷên', 'Uyển', 'UỶÊN', 'UYỂN'],
				'uỹên'	=> ['uyễn', 'Uỹên', 'Uyễn', 'UỸÊN', 'UYỄN'],
				'uýên'	=> ['uyến', 'Uýên', 'Uyến', 'UÝÊN', 'UYẾN'],
				'uỵên'	=> ['uyện', 'Uỵên', 'Uyện', 'UỴÊN', 'UYỆN'],
				'ùyêt'	=> ['uyềt', 'Ùyêt', 'Uyềt', 'ÙYÊT', 'UYỀT'],
				'ủyêt'	=> ['uyểt', 'Ủyêt', 'Uyểt', 'ỦYÊT', 'UYỂT'],
				'ũyêt'	=> ['uyễt', 'Ũyêt', 'Uyễt', 'ŨYÊT', 'UYỄT'],
				'úyêt'	=> ['uyết', 'Úyêt', 'Uyết', 'ÚYÊT', 'UYẾT'],
				'ụyêt'	=> ['uyệt', 'Ụyêt', 'Uyệt', 'ỤYÊT', 'UYỆT'],
				'uỳêt'	=> ['uyềt', 'Uỳêt', 'Uyềt', 'UỲÊT', 'UYỀT'],
				'uỷêt'	=> ['uyểt', 'Uỷêt', 'Uyểt', 'UỶÊT', 'UYỂT'],
				'uỹêt'	=> ['uyễt', 'Uỹêt', 'Uyễt', 'UỸÊT', 'UYỄT'],
				'uýêt'	=> ['uyết', 'Uýêt', 'Uyết', 'UÝÊT', 'UYẾT'],
				'uỵêt'	=> ['uyệt', 'Uỵêt', 'Uyệt', 'UỴÊT', 'UYỆT'],
				'ùynh'	=> ['uỳnh', 'Ùynh', 'Uỳnh', 'ÙYNH', 'UỲNH'],
				'ủynh'	=> ['uỷnh', 'Ủynh', 'Uỷnh', 'ỦYNH', 'UỶNH'],
				'ũynh'	=> ['uỹnh', 'Ũynh', 'Uỹnh', 'ŨYNH', 'UỸNH'],
				'úynh'	=> ['uýnh', 'Úynh', 'Uýnh', 'ÚYNH', 'UÝNH'],
				'ụynh'	=> ['uỵnh', 'Ụynh', 'Uỵnh', 'ỤYNH', 'UỴNH'],
				'ừơng'	=> ['ường', 'Ừơng', 'Ường', 'ỪƠNG', 'ƯỜNG'],
				'ửơng'	=> ['ưởng', 'Ửơng', 'Ưởng', 'ỬƠNG', 'ƯỞNG'],
				'ữơng'	=> ['ưỡng', 'Ữơng', 'Ưỡng', 'ỮƠNG', 'ƯỠNG'],
				'ứơng'	=> ['ướng', 'Ứơng', 'Ướng', 'ỨƠNG', 'ƯỚNG'],
				'ựơng'	=> ['ượng', 'Ựơng', 'Ượng', 'ỰƠNG', 'ƯỢNG']
			],
			/**
			 * What is the rule?
			 * Please view details in the iVN.docx file.
			 *
			 * Indexes:
			 *	0 -> Replace text in lowercase
			 *	1 -> Find text in upper first char and lower remain chars
			 *	2 -> Replace text in upper first char and lower remain chars
			 *	3 -> Find text in uppercase
			 *	4 -> Replace text in uppercase
			 *	5 -> true: can within another word (ex: 'hì' within 'hình'), use preg_replace()
			 *		false: never within another word, safe to use str_replace()
			 */
			'i_or_y'	=> [
				'i'	=> [
					'only'	=> [
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
					'major'	=> [
						'my'	=> ['mi', 'My', 'Mi', 'MY', 'MI', false],
						'mỳ'	=> ['mì', 'Mỳ', 'Mì', 'MỲ', 'MÌ', false],
						'tý'	=> ['tí', 'Tý', 'Tí', 'TÝ', 'TÍ', false],
						'tỵ'	=> ['tị', 'Tỵ', 'Tị', 'TỴ', 'TỊ', false]
					],
					'fix'	=> [
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
				'y'	=> [
					'only'	=> [
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
					'major'	=> [
						'hi'	=> ['hy', 'Hi', 'Hy', 'HI', 'HY', true],// Ex: Him Lam
						'hỉ'	=> ['hỷ', 'Hỉ', 'Hỷ', 'HỈ', 'HỶ', true],// Ex: thỉnh cầu
						'hí'	=> ['hý', 'Hí', 'Hý', 'HÍ', 'HÝ', true],// Ex: hít hà
						'kì'	=> ['kỳ', 'Kì', 'Kỳ', 'KÌ', 'KỲ', true],// Ex: kình ngư
						'kĩ'	=> ['kỹ', 'Kĩ', 'Kỹ', 'KĨ', 'KỸ', false],
						'kí'	=> ['ký', 'Kí', 'Ký', 'KÍ', 'KÝ', true],// Ex: kín đáo
						'kị'	=> ['kỵ', 'Kị', 'Kỵ', 'KỊ', 'KỴ', true],// Ex: đen kịt
						'li'	=> ['ly', 'Li', 'Ly', 'LI', 'LY', true],// Ex: thần linh
						'lí'	=> ['lý', 'Lí', 'Lý', 'LÍ', 'LÝ', true],// Ex: quân lính
						'lị'	=> ['lỵ', 'Lị', 'Lỵ', 'LỊ', 'LỴ', true],// Ex: lia lịa
						'mị'	=> ['mỵ', 'Mị', 'Mỵ', 'MỊ', 'MỴ', true],// Ex: mịn màng
						'si'	=> ['sy', 'Si', 'Sy', 'SI', 'SY', true],// Ex: sinh đẻ
						'sỉ'	=> ['sỷ', 'Sỉ', 'Sỷ', 'SỈ', 'SỶ', false],
						'ti'	=> ['ty', 'Ti', 'Ty', 'TI', 'TY', true],// Ex: tinh thần
						'tì'	=> ['tỳ', 'Tì', 'Tỳ', 'TÌ', 'TỲ', true],// Ex: tình yêu
						'tỉ'	=> ['tỷ', 'Tỉ', 'Tỷ', 'TỈ', 'TỶ', true],// Ex: tỉnh thành
						'tị'	=> ['tỵ', 'Tị', 'Tỵ', 'TỊ', 'TỴ', true]// Ex: tịt ngòi
					],
					'fix'	=> [
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
			 * Alternate Unicode index for Vietnamese letters with accents
			 *
			 * Sorting order:
			 *	[1] Number first, letter last
			 *	[2] lower first, UPPER last
			 *	[3] Accent order: a à ả ã á ạ
			 */
			'sort_index'	=> [
				'a' => 'aa',
				'à'	=> 'ab',
				'ả'	=> 'ac',
				'ã'	=> 'ad',
				'á'	=> 'ae',
				'ạ'	=> 'af',
				'ă'	=> 'ag',
				'ằ'	=> 'ah',
				'ẳ'	=> 'ai',
				'ẵ'	=> 'aj',
				'ắ'	=> 'ak',
				'ặ'	=> 'al',
				'â'	=> 'am',
				'ầ'	=> 'an',
				'ẩ'	=> 'ao',
				'ẫ'	=> 'ap',
				'ấ'	=> 'aq',
				'ậ'	=> 'ar',
				'd'	=> 'da',
				'đ'	=> 'db',
				'e'	=> 'ea',
				'è'	=> 'eb',
				'ẻ'	=> 'ec',
				'ẽ'	=> 'ed',
				'é'	=> 'ee',
				'ẹ'	=> 'ef',
				'ê'	=> 'eg',
				'ề'	=> 'eh',
				'ể'	=> 'ei',
				'ễ'	=> 'ej',
				'ế'	=> 'ek',
				'ệ'	=> 'el',
				'i'	=> 'ia',
				'ì'	=> 'ib',
				'ỉ'	=> 'ic',
				'ĩ'	=> 'id',
				'í'	=> 'ie',
				'ị'	=> 'if',
				'o'	=> 'oa',
				'ò'	=> 'ob',
				'ỏ'	=> 'oc',
				'õ'	=> 'od',
				'ó'	=> 'oe',
				'ọ'	=> 'of',
				'ô'	=> 'og',
				'ồ'	=> 'oh',
				'ổ'	=> 'oi',
				'ỗ'	=> 'oj',
				'ố'	=> 'ok',
				'ộ'	=> 'ol',
				'ơ'	=> 'om',
				'ờ'	=> 'on',
				'ở'	=> 'oo',
				'ỡ'	=> 'op',
				'ớ'	=> 'oq',
				'ợ'	=> 'or',
				'u'	=> 'ua',
				'ù'	=> 'ub',
				'ủ'	=> 'uc',
				'ũ'	=> 'ud',
				'ú'	=> 'ue',
				'ụ'	=> 'uf',
				'ư'	=> 'ug',
				'ừ'	=> 'uh',
				'ử'	=> 'ui',
				'ữ'	=> 'uj',
				'ứ'	=> 'uk',
				'ự'	=> 'ul',
				'y'	=> 'ya',
				'ỳ'	=> 'yb',
				'ỷ'	=> 'yc',
				'ỹ'	=> 'yd',
				'ý'	=> 'ye',
				'ỵ'	=> 'yf',
				'A'	=> 'AA',
				'À'	=> 'AB',
				'Ả'	=> 'AC',
				'Ã'	=> 'AD',
				'Á'	=> 'AE',
				'Ạ'	=> 'AF',
				'Ă'	=> 'AG',
				'Ằ'	=> 'AH',
				'Ẳ'	=> 'AI',
				'Ẵ'	=> 'AJ',
				'Ắ'	=> 'AK',
				'Ặ'	=> 'AL',
				'Â'	=> 'AM',
				'Ầ'	=> 'AN',
				'Ẩ'	=> 'AO',
				'Ẫ'	=> 'AP',
				'Ấ'	=> 'AQ',
				'Ậ'	=> 'AR',
				'D'	=> 'DA',
				'Đ'	=> 'DB',
				'E'	=> 'EA',
				'È'	=> 'EB',
				'Ẻ'	=> 'EC',
				'Ẽ'	=> 'ED',
				'É'	=> 'EE',
				'Ẹ'	=> 'EF',
				'Ê'	=> 'EG',
				'Ề'	=> 'EH',
				'Ể'	=> 'EI',
				'Ễ'	=> 'EJ',
				'Ế'	=> 'EK',
				'Ệ'	=> 'EL',
				'I'	=> 'IA',
				'Ì'	=> 'IB',
				'Ỉ'	=> 'IC',
				'Ĩ'	=> 'ID',
				'Í'	=> 'IE',
				'Ị'	=> 'IF',
				'O'	=> 'OA',
				'Ò'	=> 'OB',
				'Ỏ'	=> 'OC',
				'Õ'	=> 'OD',
				'Ó'	=> 'OE',
				'Ọ'	=> 'OF',
				'Ô'	=> 'OG',
				'Ồ'	=> 'OH',
				'Ổ'	=> 'OI',
				'Ỗ'	=> 'OJ',
				'Ố'	=> 'OK',
				'Ộ'	=> 'OL',
				'Ơ'	=> 'OM',
				'Ờ'	=> 'ON',
				'Ở'	=> 'OO',
				'Ỡ'	=> 'OP',
				'Ớ'	=> 'OQ',
				'Ợ'	=> 'OR',
				'U'	=> 'UA',
				'Ù'	=> 'UB',
				'Ủ'	=> 'UC',
				'Ũ'	=> 'UD',
				'Ú'	=> 'UE',
				'Ụ'	=> 'UF',
				'Ư'	=> 'UG',
				'Ừ'	=> 'UH',
				'Ử'	=> 'UI',
				'Ữ'	=> 'UJ',
				'Ứ'	=> 'UK',
				'Ự'	=> 'UL',
				'Y'	=> 'YA',
				'Ỳ'	=> 'YB',
				'Ỷ'	=> 'YC',
				'Ỹ'	=> 'YD',
				'Ý'	=> 'YE',
				'Ỵ'	=> 'YF'
			],
			// Vietnamese number format strings
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
				'B'			=> '%s tỷ'
			]
		];
	}

	/**
	 * Upper the first character of each single word, lower remain characters
	 * Used for Vietnamese people names, administrative unit names...
	 *
	 * @param string $text Input text
	 * @return string Result text
	 */
	public function format_people_name(string $text = ''): string
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
	 * Remove all accents or convert them to something
	 * Used for SEO, out-dated browsers...
	 *
	 * @param string $text Input text
	 * @param string $mode Mode:
	 *		'remove': Remove all accents and convert special letters into English letters
	 *		'remove_keep_alphabet': Remove only accents, keep Vietnamese letters in the alphabet
	 *		'ncr_decimal': Convert accents into NCR Decimal
	 * @return string Result text
	 */
	public function convert_accent(string $text = '', string $mode = 'remove'): string
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

				case 'remove_keep_alphabet';
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
	 * Correct accent placements
	 *
	 * We have 2 types of problems:
	 *	[1]: Differences between the new method and the classic one
	 *	[2]: Wrong placement of accents, they are really errors
	 *
	 * @param string $text Input text
	 * @return string Result text
	 */
	public function fix_accent(string $text = ''): string
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
	 * Fix wrong cases of i and y
	 *
	 *	ZERO-CASE: NOT I, NOT Y (Skipped)
	 *	LEFT-CASE: ONLY I, NOT Y
	 *	RIGHT-CASE: NOT I, ONLY Y
	 *	MAJOR-I-CASE: MAJOR I, MINORITY Y
	 *		> Replace all *Y to *I
	 *		> Fix specified Y cases
	 *	MAJOR-Y-CASE: MINORITY I, MAJOR Y
	 *		> Replace all *I to *Y
	 *		> Fix specified I cases
	 *
	 * @param string $text Input text
	 * @return string Result text
	 */
	public function fix_i_or_y(string $text = ''): string
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
	 * Binary safe case-insensitive string comparison
	 * Internal use for $this->sort_word()
	 *
	 * @param string $a	First string
	 * @param string $b	Second string
	 * @return int Returns: < 0 if $a < $b; > 0 if $a > $b; 0 if $a == $b
	 */
	protected function cmp(string $a, string $b): int
	{
		$a = str_replace(array_keys($this->data['sort_index']), array_values($this->data['sort_index']), $a);
		$b = str_replace(array_keys($this->data['sort_index']), array_values($this->data['sort_index']), $b);

		return strcasecmp($a, $b);
	}

	/**
	 * Sorting Vietnamese words
	 *
	 * @param array $data Input array
	 * @param bool $sort_keys Sort by array keys (true) or array values (false)
	 * @return array Result array
	 */
	public function sort_word(array $data = [], bool $sort_keys = false): array
	{
		if (count($data))
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
	 * Sorting Vietnamese people names
	 *
	 * Sorting order:
	 *	> First name.
	 *	> Last name: surname + middle name
	 *
	 * @param array $data Input array
	 * @return array Result array
	 */
	public function sort_people_name(array $data = []): array
	{
		$new_names = [];

		if (count($data))
		{
			$names = [];

			foreach ($data as $name)
			{
				$name = $this->format_people_name($name);
				$name_ary = explode(' ', $name);
				$firstname = array_pop($name_ary);
				$lastname = implode(' ', $name_ary);
				$names[$firstname][] = $lastname;
			}

			// Sorting
			$names = $this->sort_word($names, true);

			foreach ($names as $firstname => $rows)
			{
				$names[$firstname] = $this->sort_word($rows);
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
	 * A Vietnamese character is an alphabet or a character with accents
	 *
	 * @param string $char Input character
	 * @return bool true/false; if input is empty or more than 1 character, return false
	 */
	public function check_char(string $char = ''): bool
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
	 * Place an accent into a non-accent single word
	 *
	 * @param string $word Input word
	 * @param int $accent Accent code
	 *							1: [à]
	 *							2: [ả]
	 *							3: [ã]
	 *							4: [á]
	 *							5: [ạ]
	 * @return string Final word with new accent
	 */
	public function place_accent(string $word = '', int $accent = 0): string
	{
		// Max length of a Vietnamese single word is 7 characters: "nghiêng"
		$word_len = mb_strlen($word);

		if ($word_len > 0 && $word_len < 8 && $accent > 0 && $accent < 6)
		{
			$consonant = $syllable_consonant = '';

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

			// Max length of a vowel within syllable is 3 characters
			$syllable_vowel_len = mb_strlen($syllable_vowel);

			if ($syllable_vowel_len > 0 && $syllable_vowel_len < 4 && isset($this->data['syllable_to_consonants'][mb_strtolower($syllable)]))
			{
				// Always place accent at "ê/ơ" first, regardless of length
				if (str_contains($syllable_vowel, 'ê'))
				{
					$syllable_vowel = str_replace('ê', $this->data['accent_to_vowels']['ê'][$accent][0], $syllable_vowel);
				}
				elseif (str_contains($syllable_vowel, 'Ê'))
				{
					$syllable_vowel = str_replace('Ê', $this->data['accent_to_vowels']['ê'][$accent][1], $syllable_vowel);
				}
				elseif (str_contains($syllable_vowel, 'ơ'))
				{
					$syllable_vowel = str_replace('ơ', $this->data['accent_to_vowels']['ơ'][$accent][0], $syllable_vowel);
				}
				elseif (str_contains($syllable_vowel, 'Ơ'))
				{
					$syllable_vowel = str_replace('Ơ', $this->data['accent_to_vowels']['ơ'][$accent][1], $syllable_vowel);
				}
				else
				{
					/**
					 * If "ê/ơ" do not appear, then...
					 *
					 * Classic method:
					 *	For each syllable, if the number of vowels is...
					 *		[...a...]	-> [...á...]
					 *			(1 -> itself vowel)
					 *		[...oa]		-> [...óa]
					 *			(2, and the last character is VOWEL -> first vowel)
					 *		[...oan]	-> [oán]
					 *			(2, and the last character is CONSONANT -> second vowel)
					 *		[...oai...]	-> [...oái...]
					 *			(3 -> second vowel)
					 *
					 * New method:
					 *	Same as the classic method, but there are 3 exceptions:
					 *		[oa]	->	[oá]	(second vowel, instead of "óa")
					 *		[oe]	->	[oé]	(second vowel, instead of "óe")
					 *		[uy]	->	[uý]	(second vowel, instead of "úy")
					 *
					 * Note: The recommended method and also is required by the iVN document is the classic
					 */
					if ($syllable_vowel_len == 1)
					{
						$pos = 0;
					}
					elseif ($syllable_vowel_len == 2)
					{
						if (!empty($syllable_consonant))
						{
							$pos = 1;
						}
						else
						{
							$pos = 0;
						}
					}
					else
					{
						$pos = 1;
					}

					$lower = mb_strtolower(mb_substr($syllable_vowel, $pos, 1));
					$upper = mb_strtoupper($lower);
					$syllable_vowel = str_replace([$lower, $upper], [$this->data['accent_to_vowels'][$lower][$accent][0], $this->data['accent_to_vowels'][$lower][$accent][1]], $syllable_vowel);
				}
			}

			// Return final word
			$word = $consonant . $syllable_vowel . $syllable_consonant;
		}

		return $word;
	}

	/**
	 * Generate all of Vietnamese single words
	 *
	 * @param bool $strict_mode true: Words only in Vietnamese dictionary
	 *							false: All available words in theory
	 * @return array Array of words
	 */
	protected function generate_words(bool $strict_mode = true): array
	{
		$words = [];

		foreach ($this->data['syllable_to_consonants'] as $syllable => $consonants)
		{
			$source = ($strict_mode) ? $consonants : array_keys($this->data['consonants']);

			for ($i = 0; $i < 6; $i++)
			{
				$words[] = $this->place_accent($syllable, $i);

				if (count($source))
				{
					foreach ($source as $consonant)
					{
						$words[] = $this->place_accent($consonant . $syllable, $i);
					}
				}
			}
		}

		return $words;
	}

	/**
	 * Detect incorrect words in Vietnamese
	 *
	 * @param string $text Input text
	 * @param bool $get_incorrect_words true: Return incorrect words
	 *									false: Return correct words
	 * @return array Found words
	 */
	public function scan_words(string $text = '', bool $get_incorrect_words = true): array
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
	 * Print out the way to spell Vietnamese words
	 *
	 * @param string $text Input text
	 * @return string Spell text
	 */
	public function speak(string $text = ''): string
	{
		$read_text = '';

		if (!empty($text))
		{
			// Detect the accent
			$accent_letters = [
				1	=> '',
				2	=> '',
				3	=> '',
				4	=> '',
				5	=> ''
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
					$syllable_vowel_no_accent = $this->convert_accent($syllable_vowel, 'remove_keep_alphabet');

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
							$read_text .= $syllable_vowel_no_accent . $syllable_consonant . ' ' . $this->convert_accent($word, 'remove_keep_alphabet') . " $accent /$word/; ";
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
	 * Convert number/amount into Vietnamese text
	 *
	 * @param float $amount Original amount
	 * @return string Amount in text
	 */
	public function number_to_text(float $amount): string
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
					if ($this->convert_3_numbers($split[$i]) != $this->data['number_format']['0'])
					{
						$text .= ($steps[$i] == '') ? $this->convert_3_numbers($split[$i]) : sprintf($steps[$i], $this->convert_3_numbers($split[$i]));
						$text .= ' ';
					}
				}
			}
			else
			{
				$text .= $this->convert_3_numbers($split[0]);
			}
		}

		return trim($text);
	}

	/**
	 * Convert amount into text for each thousand steps
	 *
	 * @param int $number	Original amount
	 * @return string		Amount in text
	 */
	protected function convert_3_numbers(int $number): string
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
