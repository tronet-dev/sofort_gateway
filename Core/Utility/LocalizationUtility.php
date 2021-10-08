<?php

namespace Tronet\Trosofortueberweisung\Core\Utility;

/**
 * Provides methods for localization actions.
 *
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2020
 * @author        tronet GmbH
 *
 * @since         8.0.9
 * @version       8.0.9
 */
class LocalizationUtility
{
    /**
     * Removes all accents from a string.
     *
     * @param string $sString
     *
     * @return string
     * 
     * @author  tronet GmbH
     * @since   8.0.9
     * @version 8.0.9
     */
    public function troRemoveAccents($sString)
    {
        $aTranslationTable = [
            'ª' => 'a',
            'á' => 'a',
            'Á' => 'A',
            'à' => 'a',
            'À' => 'A',
            'ă' => 'a',
            'Ă' => 'A',
            'ắ' => 'a',
            'Ắ' => 'A',
            'ằ' => 'a',
            'Ằ' => 'A',
            'ẵ' => 'a',
            'Ẵ' => 'A',
            'ẳ' => 'a',
            'Ẳ' => 'A',
            'â' => 'a',
            'Â' => 'A',
            'ấ' => 'a',
            'Ấ' => 'A',
            'ầ' => 'a',
            'Ầ' => 'A',
            'ẫ' => 'a',
            'Ẫ' => 'A',
            'ẩ' => 'a',
            'Ẩ' => 'A',
            'ǎ' => 'a',
            'Ǎ' => 'A',
            'ã' => 'a',
            'Ã' => 'A',
            'ą' => 'a',
            'Ą' => 'A',
            'ā' => 'a',
            'Ā' => 'A',
            'ả' => 'a',
            'Ả' => 'A',
            'ạ' => 'a',
            'Ạ' => 'A',
            'ặ' => 'a',
            'Ặ' => 'A',
            'ậ' => 'a',
            'Ậ' => 'A',
            'å' => 'aa',
            'Å' => 'Aa',
            'ä' => 'ae',
            'Ä' => 'Ae',
            'æ' => 'ae',
            'Æ' => 'Ae',
            'ɑ' => 'a',
            'ć' => 'c',
            'Ć' => 'C',
            'ĉ' => 'c',
            'Ĉ' => 'C',
            'č' => 'c',
            'Č' => 'C',
            'ċ' => 'c',
            'Ċ' => 'C',
            'ç' => 'c',
            'Ç' => 'C',
            'ď' => 'd',
            'Ď' => 'D',
            'ð' => 'd',
            'Ð' => 'D',
            'đ' => 'dj',
            'Đ' => 'DJ',
            'é' => 'e',
            'É' => 'E',
            'è' => 'e',
            'È' => 'E',
            'ĕ' => 'e',
            'Ĕ' => 'E',
            'ê' => 'e',
            'Ê' => 'E',
            'ế' => 'e',
            'Ế' => 'E',
            'ề' => 'e',
            'Ề' => 'E',
            'ễ' => 'e',
            'Ễ' => 'E',
            'ể' => 'e',
            'Ể' => 'E',
            'ě' => 'e',
            'Ě' => 'E',
            'ë' => 'e',
            'Ë' => 'E',
            'ẽ' => 'e',
            'Ẽ' => 'E',
            'ė' => 'e',
            'Ė' => 'E',
            'ę' => 'e',
            'Ę' => 'E',
            'ē' => 'e',
            'Ē' => 'E',
            'ẻ' => 'e',
            'Ẻ' => 'E',
            'ẹ' => 'e',
            'Ẹ' => 'E',
            'ệ' => 'e',
            'Ệ' => 'E',
            'ğ' => 'g',
            'Ğ' => 'G',
            'ĝ' => 'g',
            'Ĝ' => 'G',
            'ġ' => 'g',
            'Ġ' => 'G',
            'ģ' => 'g',
            'Ģ' => 'G',
            'ĥ' => 'h',
            'Ĥ' => 'H',
            'ħ' => 'h',
            'Ħ' => 'H',
            'í' => 'i',
            'Í' => 'I',
            'ì' => 'i',
            'Ì' => 'I',
            'ĭ' => 'i',
            'Ĭ' => 'I',
            'î' => 'i',
            'Î' => 'I',
            'ǐ' => 'i',
            'Ǐ' => 'I',
            'ï' => 'i',
            'Ï' => 'I',
            'ĩ' => 'i',
            'Ĩ' => 'I',
            'İ' => 'I',
            'į' => 'i',
            'Į' => 'I',
            'ī' => 'i',
            'Ī' => 'I',
            'ỉ' => 'i',
            'Ỉ' => 'I',
            'ị' => 'i',
            'Ị' => 'I',
            'ĳ' => 'ij',
            'Ĳ' => 'IJ',
            'ı' => 'i',
            'ĵ' => 'j',
            'Ĵ' => 'J',
            'ķ' => 'k',
            'Ķ' => 'K',
            'ĺ' => 'l',
            'Ĺ' => 'L',
            'ľ' => 'l',
            'Ľ' => 'L',
            'ļ' => 'l',
            'Ļ' => 'L',
            'ł' => 'l',
            'Ł' => 'L',
            'ŀ' => 'l',
            'Ŀ' => 'L',
            'l·l' => 'll',
            'ń' => 'n',
            'Ń' => 'N',
            'ň' => 'n',
            'Ň' => 'N',
            'ñ' => 'n',
            'Ñ' => 'N',
            'ņ' => 'n',
            'Ņ' => 'N',
            'ŋ' => 'n',
            'Ŋ' => 'N',
            'º' => 'o',
            'ó' => 'o',
            'Ó' => 'O',
            'ò' => 'o',
            'Ò' => 'O',
            'ŏ' => 'o',
            'Ŏ' => 'O',
            'ô' => 'o',
            'Ô' => 'O',
            'ố' => 'o',
            'Ố' => 'O',
            'ồ' => 'o',
            'Ồ' => 'O',
            'ỗ' => 'o',
            'Ỗ' => 'O',
            'ổ' => 'o',
            'Ổ' => 'O',
            'ǒ' => 'o',
            'Ǒ' => 'O',
            'ő' => 'o',
            'Ő' => 'O',
            'õ' => 'o',
            'Õ' => 'O',
            'ō' => 'o',
            'Ō' => 'O',
            'ỏ' => 'o',
            'Ỏ' => 'O',
            'ơ' => 'o',
            'Ơ' => 'O',
            'ớ' => 'o',
            'Ớ' => 'O',
            'ờ' => 'o',
            'Ờ' => 'O',
            'ỡ' => 'o',
            'Ỡ' => 'O',
            'ở' => 'o',
            'Ở' => 'O',
            'ợ' => 'o',
            'Ợ' => 'O',
            'ọ' => 'o',
            'Ọ' => 'O',
            'ộ' => 'o',
            'Ộ' => 'O',
            'ö' => 'oe',
            'Ö' => 'Oe',
            'ø' => 'oe',
            'Ø' => 'Oe',
            'œ' => 'oe',
            'Œ' => 'OE',
            'ĸ' => 'k',
            'ŕ' => 'r',
            'Ŕ' => 'R',
            'ř' => 'r',
            'Ř' => 'R',
            'ŗ' => 'r',
            'Ŗ' => 'R',
            'ś' => 's',
            'Ś' => 'S',
            'ŝ' => 's',
            'Ŝ' => 'S',
            'š' => 's',
            'Š' => 'S',
            'ş' => 's',
            'Ş' => 'S',
            'ș' => 's',
            'Ș' => 'S',
            'ſ' => 's',
            'ß' => 's',
            'ß' => 'ss',
            'ť' => 't',
            'Ť' => 'T',
            'ţ' => 't',
            'Ţ' => 'T',
            'ț' => 't',
            'Ț' => 'T',
            'ŧ' => 't',
            'Ŧ' => 'T',
            'ú' => 'u',
            'Ú' => 'U',
            'ù' => 'u',
            'Ù' => 'U',
            'ŭ' => 'u',
            'Ŭ' => 'U',
            'û' => 'u',
            'Û' => 'U',
            'ǔ' => 'u',
            'Ǔ' => 'U',
            'ů' => 'u',
            'Ů' => 'U',
            'ǘ' => 'u',
            'Ǘ' => 'U',
            'ǜ' => 'u',
            'Ǜ' => 'U',
            'ǚ' => 'u',
            'Ǚ' => 'U',
            'ǖ' => 'u',
            'Ǖ' => 'U',
            'ű' => 'u',
            'Ű' => 'U',
            'ũ' => 'u',
            'Ũ' => 'U',
            'ų' => 'u',
            'Ų' => 'U',
            'ū' => 'u',
            'Ū' => 'U',
            'ủ' => 'u',
            'Ủ' => 'U',
            'ư' => 'u',
            'Ư' => 'U',
            'ứ' => 'u',
            'Ứ' => 'U',
            'ừ' => 'u',
            'Ừ' => 'U',
            'ữ' => 'u',
            'Ữ' => 'U',
            'ử' => 'u',
            'Ử' => 'U',
            'ự' => 'u',
            'Ự' => 'U',
            'ụ' => 'u',
            'Ụ' => 'U',
            'ü' => 'ue',
            'Ü' => 'Ue',
            'ŵ' => 'w',
            'Ŵ' => 'W',
            'ý' => 'y',
            'Ý' => 'Y',
            'ỳ' => 'y',
            'Ỳ' => 'Y',
            'ŷ' => 'y',
            'Ŷ' => 'Y',
            'ÿ' => 'y',
            'Ÿ' => 'Y',
            'ỹ' => 'y',
            'Ỹ' => 'Y',
            'ỷ' => 'y',
            'Ỷ' => 'Y',
            'ỵ' => 'y',
            'Ỵ' => 'Y',
            'ź' => 'z',
            'Ź' => 'Z',
            'ž' => 'z',
            'Ž' => 'Z',
            'ż' => 'z',
            'Ż' => 'Z',
            'þ' => 'th',
            'Þ' => 'TH',
            'ŉ' => 'n',
        ];

        return strtr($sString, $aTranslationTable);
    }
}