<?php

namespace util;

class Util {
    public static function array_get($array, $index) {
        if ($index < 0 || $index > sizeof($array) - 1) {
            return null;
        }
        return $array[$index];
    }
    
    public function getDivisiblesOfNumber($number)
    {
        $divisibles = array();

        for ($i = 1;$i <= $number;$i++)
        {
            if (($number % $i) === 0)
            {
                $divisibles[] = $i;
            }
        }

        return $divisibles;
    }
    
    function isDecimal( $value ) {
        if (is_float($value) || is_double($value)) {
            return true;
        }
        return false;
//        if ( strpos( $value, "." ) !== false ) {
//            return true;
//        }
//        return false;
    }
}
