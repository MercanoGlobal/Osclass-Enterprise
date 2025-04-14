<?php
/*
 * Copyright 2022 Osclass Enterprise
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Escape all the values of an array.
 *
 * @param array $array Array used to apply addslashes.
 * @return array $array after apply addslashes.
 */
function add_slashes_extended($array) {
    foreach ((array) $array as $k => $v) {
        if (is_array($v)) {
            $array[$k] = add_slashes_extended($v);
        } else {
            $array[$k] = addslashes($v);
        }
    }
    return $array;
}

function osc_sanitizeString($string) {
    if ($string === '' || $string === null) {
        return '';
    }

    $string = strip_tags($string);

    // Transliterate Cyrillic and other scripts to Latin
    $string = transliterate($string);

    // Remove accents and handle special characters
    $string = remove_accents($string);

    // Clean up HTML entities and replace special characters
    $string = preg_replace('/&.+?;/', '', $string);
    $string = str_replace(array('.', '\'', '--'), '-', $string);
    $string = preg_replace('/\s+/', '-', $string);

    // Use Unicode-friendly regex to preserve letters and numbers
    $string = preg_replace('/[^\p{L}\p{N}\'_\-\.]/u', '', $string);

    // Normalize hyphens and trim
    $string = preg_replace('/-+/', '-', $string);
    $string = trim($string, '-');

    // Lowercase for consistency
    $string = strtolower($string);

    // Fallback to prevent empty slugs
    if ($string === '') {
        $string = 'default-slug';
    }

    return $string;
}

function transliterate($string) {
    $translit = array(
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo',
        'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I', 'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M',
        'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U',
        'Ф' => 'F', 'Х' => 'Kh', 'Ц' => 'Ts', 'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Shch',
        'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo',
        'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm',
        'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u',
        'ф' => 'f', 'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch',
        'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya'
    );

    return strtr($string, $translit);
}

function remove_accents($string) {
    if (!preg_match('/[\x80-\xff]/', $string)) {
        return $string;
    }

    if (is_utf8($string)) {
        $chars = array(
            // Decompositions for Latin-1 Supplement
            'ª' => 'a', 'º' => 'o', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A',
            'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O',
            'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U',
            'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'TH', 'ß' => 's', 'à' => 'a', 'á' => 'a', 'â' => 'a',
            'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
            'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'd',
            'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o',
            'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ý' => 'y', 'þ' => 'th', 'ÿ' => 'y',
            'Ø' => 'O',
            // Decompositions for Latin Extended-A
            'Ā' => 'A', 'ā' => 'a', 'Ă' => 'A', 'ă' => 'a', 'Ą' => 'A', 'ą' => 'a', 'Ć' => 'C',
            'ć' => 'c', 'Ĉ' => 'C', 'ĉ' => 'c', 'Ċ' => 'C', 'ċ' => 'c', 'Č' => 'C', 'č' => 'c',
            'Ď' => 'D', 'ď' => 'd', 'Đ' => 'D', 'đ' => 'd', 'Ē' => 'E', 'ē' => 'e', 'Ĕ' => 'E',
            'ĕ' => 'e', 'Ė' => 'E', 'ė' => 'e', 'Ę' => 'E', 'ę' => 'e', 'Ě' => 'E', 'ě' => 'e',
            'Ĝ' => 'G', 'ĝ' => 'g', 'Ğ' => 'G', 'ğ' => 'g', 'Ġ' => 'G', 'ġ' => 'g', 'Ģ' => 'G',
            'ģ' => 'g', 'Ĥ' => 'H', 'ĥ' => 'h', 'Ħ' => 'H', 'ħ' => 'h', 'Ĩ' => 'I', 'ĩ' => 'i',
            'Ī' => 'I', 'ī' => 'i', 'Ĭ' => 'I', 'ĭ' => 'i', 'Į' => 'I', 'į' => 'i', 'İ' => 'I',
            'ı' => 'i', 'Ĳ' => 'IJ', 'ĳ' => 'ij', 'Ĵ' => 'J', 'ĵ' => 'j', 'Ķ' => 'K', 'ķ' => 'k',
            'ĸ' => 'k', 'Ĺ' => 'L', 'ĺ' => 'l', 'Ļ' => 'L', 'ļ' => 'l', 'Ľ' => 'L', 'ľ' => 'l',
            'Ŀ' => 'L', 'ŀ' => 'l', 'Ł' => 'L', 'ł' => 'l', 'Ń' => 'N', 'ń' => 'n', 'Ņ' => 'N',
            'ņ' => 'n', 'Ň' => 'N', 'ň' => 'n', 'ŉ' => 'n', 'Ŋ' => 'N', 'ŋ' => 'n', 'Ō' => 'O',
            'ō' => 'o', 'Ŏ' => 'O', 'ŏ' => 'o', 'Ő' => 'O', 'ő' => 'o', 'Œ' => 'OE', 'œ' => 'oe',
            'Ŕ' => 'R', 'ŕ' => 'r', 'Ŗ' => 'R', 'ŗ' => 'r', 'Ř' => 'R', 'ř' => 'r', 'Ś' => 'S',
            'ś' => 's', 'Ŝ' => 'S', 'ŝ' => 's', 'Ş' => 'S', 'ş' => 's', 'Š' => 'S', 'š' => 's',
            'Ţ' => 'T', 'ţ' => 't', 'Ť' => 'T', 'ť' => 't', 'Ŧ' => 'T', 'ŧ' => 't', 'Ũ' => 'U',
            'ũ' => 'u', 'Ū' => 'U', 'ū' => 'u', 'Ŭ' => 'U', 'ŭ' => 'u', 'Ů' => 'U', 'ů' => 'u',
            'Ű' => 'U', 'ű' => 'u', 'Ų' => 'U', 'ų' => 'u', 'Ŵ' => 'W', 'ŵ' => 'w', 'Ŷ' => 'Y',
            'ŷ' => 'y', 'Ÿ' => 'Y', 'Ź' => 'Z', 'ź' => 'z', 'Ż' => 'Z', 'ż' => 'z', 'Ž' => 'Z',
            'ž' => 'z', 'ſ' => 's',
            // Decompositions for Latin Extended-B
            'Ș' => 'S', 'ș' => 's', 'Ț' => 'T', 'ț' => 't',
            // Euro Sign
            '€' => 'E',
            // GBP (Pound) Sign
            '£' => '',
            // Vowels with diacritic (Vietnamese)
            'Ơ' => 'O', 'ơ' => 'o', 'Ư' => 'U', 'ư' => 'u',
            'Ầ' => 'A', 'ầ' => 'a', 'Ằ' => 'A', 'ằ' => 'a', 'Ề' => 'E', 'ề' => 'e',
            'Ồ' => 'O', 'ồ' => 'o', 'Ờ' => 'O', 'ờ' => 'o', 'Ừ' => 'U', 'ừ' => 'u',
            'Ỳ' => 'Y', 'ỳ' => 'y', 'Ả' => 'A', 'ả' => 'a', 'Ẩ' => 'A', 'ẩ' => 'a',
            'Ẳ' => 'A', 'ẳ' => 'a', 'Ẻ' => 'E', 'ẻ' => 'e', 'Ể' => 'E', 'ể' => 'e',
            'Ỉ' => 'I', 'ỉ' => 'i', 'Ỏ' => 'O', 'ỏ' => 'o', 'Ổ' => 'O', 'ổ' => 'o',
            'Ở' => 'O', 'ở' => 'o', 'Ủ' => 'U', 'ủ' => 'u', 'Ử' => 'U', 'ử' => 'u',
            'Ỷ' => 'Y', 'ỷ' => 'y', 'Ẫ' => 'A', 'ẫ' => 'a', 'Ẵ' => 'A', 'ẵ' => 'a',
            'Ẽ' => 'E', 'ẽ' => 'e', 'Ễ' => 'E', 'ễ' => 'e', 'Ỗ' => 'O', 'ỗ' => 'o',
            'Ỡ' => 'O', 'ỡ' => 'o', 'Ữ' => 'U', 'ữ' => 'u', 'Ỹ' => 'Y', 'ỹ' => 'y',
            'Ấ' => 'A', 'ấ' => 'a', 'Ắ' => 'A', 'ắ' => 'a', 'Ế' => 'E', 'ế' => 'e',
            'Ố' => 'O', 'ố' => 'o', 'Ớ' => 'O', 'ớ' => 'o', 'Ứ' => 'U', 'ứ' => 'u',
            'Ạ' => 'A', 'ạ' => 'a', 'Ậ' => 'A', 'ậ' => 'a', 'Ặ' => 'A', 'ặ' => 'a',
            'Ẹ' => 'E', 'ẹ' => 'e', 'Ệ' => 'E', 'ệ' => 'e', 'Ị' => 'I', 'ị' => 'i',
            'Ọ' => 'O', 'ọ' => 'o', 'Ộ' => 'O', 'ộ' => 'o', 'Ợ' => 'O', 'ợ' => 'o',
            'Ụ' => 'U', 'ụ' => 'u', 'Ự' => 'U', 'ự' => 'u', 'Ỵ' => 'Y', 'ỵ' => 'y',
            // Vowels with diacritic (Chinese, Hanyu Pinyin)
            'ɑ' => 'a', 'Ǖ' => 'U', 'ǖ' => 'u', 'Ǘ' => 'U', 'ǘ' => 'u', 'Ǎ' => 'A',
            'ǎ' => 'a', 'Ǐ' => 'I', 'ǐ' => 'i', 'Ǒ' => 'O', 'ǒ' => 'o', 'Ǔ' => 'U',
            'ǔ' => 'u', 'Ǚ' => 'U', 'ǚ' => 'u', 'Ǜ' => 'U', 'ǜ' => 'u'
        );

        // Greek to Latin
        $expressions = array(
            '/[αάΑΆ]/u' => 'a', '/[βΒ]/u' => 'v', '/[γΓ]/u' => 'g', '/[δΔ]/u' => 'd',
            '/[εέΕΈ]/u' => 'e', '/[ζΖ]/u' => 'z', '/[ηήΗΉ]/u' => 'i', '/[θΘ]/u' => 'th',
            '/[ιίϊΙΊΪ]/u' => 'i', '/[κΚ]/u' => 'k', '/[λΛ]/u' => 'l', '/[μΜ]/u' => 'm',
            '/[νΝ]/u' => 'n', '/[ξΞ]/u' => 'x', '/[οόΟΌ]/u' => 'o', '/[πΠ]/u' => 'p',
            '/[ρΡ]/u' => 'r', '/[σςΣ]/u' => 's', '/[τΤ]/u' => 't', '/[υύϋΥΎΫ]/u' => 'y',
            '/[φΦ]/iu' => 'f', '/[χΧ]/u' => 'ch', '/[ψΨ]/u' => 'ps', '/[ωώ]/iu' => 'o',
            '/[αΑ][ιίΙΊ]/u' => 'e', '/[οΟΕε][ιίΙΊ]/u' => 'i',
            '/[αΑ][υύΥΎ]([θΘκΚξΞπΠσςΣτTφΡχΧψΨ]|\s|$)/u' => 'af$1',
            '/[αΑ][υύΥΎ]/u' => 'av', '/[εΕ][υύΥΎ]([θΘκΚξΞπΠσςΣτTφΡχΧψΨ]|\s|$)/u' => 'ef$1',
            '/[εΕ][υύΥΎ]/u' => 'ev', '/[οΟ][υύΥΎ]/u' => 'ou', '/(^|\s)[μΜ][πΠ]/u' => '$1b',
            '/[μΜ][πΠ](\s|$)/u' => 'b$1', '/[μΜ][πΠ]/u' => 'b', '/[νΝ][τΤ]/u' => 'nt',
            '/[τΤ][σΣ]/u' => 'ts', '/[τΤ][ζΖ]/u' => 'tz', '/[γΓ][γΓ]/u' => 'ng',
            '/[γΓ][κΚ]/u' => 'gk', '/[ηΗ][υΥ]([θΘκΚξΞπΠσςΣτTφΡχΧψΨ]|\s|$)/u' => 'if$1',
            '/[ηΗ][υΥ]/u' => 'iu'
        );
        $string = preg_replace(array_keys($expressions), array_values($expressions), $string);

        // Locale-specific rules
        $locale = osc_current_user_locale();
        if (in_array($locale, ['de_DE', 'de_DE_formal', 'de_CH', 'de_CH_informal'])) {
            $chars['Ä'] = 'Ae'; $chars['ä'] = 'ae'; $chars['Ö'] = 'Oe'; $chars['ö'] = 'oe';
            $chars['Ü'] = 'Ue'; $chars['ü'] = 'ue'; $chars['ß'] = 'ss';
        } elseif ($locale === 'da_DK') {
            $chars['Æ'] = 'Ae'; $chars['æ'] = 'ae'; $chars['Ø'] = 'Oe'; $chars['ø'] = 'oe';
            $chars['Å'] = 'Aa'; $chars['å'] = 'aa';
        } elseif ($locale === 'ca') {
            $chars['l·l'] = 'll';
        } elseif (in_array($locale, ['sr_RS', 'bs_BA'])) {
            $chars['Đ'] = 'DJ'; $chars['đ'] = 'dj';
        }

        $string = strtr($string, $chars);
    } else {
        $chars = array();
        // Assume ISO-8859-1 if not UTF-8
        $chars['in'] = "\x80\x83\x8a\x8e\x9a\x9e\x9f\xa2\xa5\xb5\xc0\xc1\xc2\xc3\xc4\xc5\xc7\xc8\xc9\xca\xcb\xcc\xcd\xce\xcf\xd1\xd2\xd3\xd4\xd5\xd6\xd8\xd9\xda\xdb\xdc\xdd\xe0\xe1\xe2\xe3\xe4\xe5\xe7\xe8\xe9\xea\xeb\xec\xed\xee\xef\xf1\xf2\xf3\xf4\xf5\xf6\xf8\xf9\xfa\xfb\xfc\xfd\xff";
        $chars['out'] = 'EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy';
        $string = strtr($string, $chars['in'], $chars['out']);
        $double_chars = array(
            'in'  => array("\x8c", "\x9c", "\xc6", "\xd0", "\xde", "\xdf", "\xe6", "\xf0", "\xfe"),
            'out' => array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th')
        );
        $string = str_replace($double_chars['in'], $double_chars['out'], $string);
    }

    return $string;
}

function is_utf8($string) {
    return preg_match('%^(?:
          [\x09\x0A\x0D\x20-\x7E]            # ASCII
        | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
        |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
        | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
        |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
        |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
        | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
        |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
    )*$%xs', $string);
}
