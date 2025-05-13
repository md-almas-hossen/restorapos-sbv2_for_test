<?php
if (!function_exists('numberToWord')) {
    function numberToWord($number)
    {


        $CI =& get_instance(); 
        $settings =  $CI->db->select('*')->from('setting')->get()->row();
        $language_field = $settings->language;

        $taka = $CI->db->select($language_field)->from('language')->where('phrase', 'taka')->get()->row();
        $paisa = $CI->db->select($language_field)->from('language')->where('phrase', 'paisa')->get()->row();
        
        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';

        $decimal = ' '.$taka->$language_field.' ';
        $paisa = ' '.$paisa->$language_field.' ';

        $dictionary = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'fourty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety',
            100 => 'hundred',
            1000 => 'thousand',
            100000 => 'lac',
            10000000 => 'crore'
        );
        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error('convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, cE_USER_WARNING);
            return false;
        }

        if ($number < 0) {
            return $negative . numberToWord(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int)($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= ' ' . numberToWord($remainder);
                }
                break;
            case $number < 100000:
                $thousand = $number / 1000;
                $remainder = $number % 1000;
                $string = numberToWord((int)$thousand) . ' ' . $dictionary[1000];
                if ($remainder) {
                    $string .= ' ' . numberToWord($remainder);
                }
                break;
            case $number < 10000000:
                $lac = $number / 100000;
                $remainder = $number % 100000;
                $string = numberToWord((int)$lac) . ' ' . $dictionary[100000];
                if ($remainder) {
                    $string .= ' ' . numberToWord($remainder);
                }
                break;
            case $number > 10000000:
                $crore = $number / 10000000;
                $remainder = $number % 10000000;
                $string = numberToWord((int)$crore) . ' ' . $dictionary[10000000];
                if ($remainder) {
                    $string .= ' ' . numberToWord($remainder);
                }
                break;
            default:
                $baseUnit = pow(10000000, floor(log($number, 10000000)));
                $numBaseUnits = (int)($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = numberToWord($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= numberToWord($remainder);
                }
                break;
        }

        if (is_numeric($fraction)) {
            $string .= $decimal;

            $words = numberToWord((int)$fraction);

            $string .= $conjunction . $words . $paisa;
        }
        return $string; 
    }

}
?>