<?php

namespace util;

class Util {
     /**
     * Splits string based on delimiter. Supports splitting based on multiple delimiters
     * 
     * @param type $content                 -   Split this string
     * @param type $keepDelimiter           -   Includes delimiters into output array
     * @param ...strings    $delimiters     -   Split string based on these strings
     * @return Array                        -   array of split elements
     */
    function explode_string($content, $keepDelimiter = false, ...$delimiters)
    {
        $length = strlen($content);

        $segment = "";
        $segments = [];
        for ($i = 0;$i < $length;$i++)
        {
            $current = $content[$i];

            if (!in_array($current, $delimiters))
            {

                $segment .= $current;
            }
            else
            {
                if ($segment !== "")
                {
                    $segments[] = $segment;
                }

                $segment = "";

                if ($keepDelimiter)
                {
                    $segments[] = $current;
                }
            }
        }

        $segments[] = $segment; // last segment
        array_filter($segments);
        return $segments;
    }
    
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
