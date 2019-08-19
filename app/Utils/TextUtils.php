<?php


namespace App\Utils;

/**
 * Class TextUtils
 * @package App\Utils
 */
class TextUtils
{
    /**
     * @param int $division
     * @return mixed
     */
    public static function divisionLetter(int $division)
    {
        $letters = [
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'X',
            'Y',
            'Z',
        ];

        return isset($letters[$division - 1]) ? $letters[$division - 1] : '—';
    }

    /**
     * @param string $time
     * @return string|string[]|null
     */
    public static function protocolTime(string $time = null)
    {
        if (is_null($time)) {
            return '';
        }
        return preg_replace('/^00:/', '', $time, 1);
    }
}
