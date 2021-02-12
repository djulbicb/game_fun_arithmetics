<?php

namespace util;

class Util {
    public static function array_get($array, $index) {
        if ($index < 0 || $index > sizeof($array) - 1) {
            return null;
        }
        return $array[$index];
    }
}
