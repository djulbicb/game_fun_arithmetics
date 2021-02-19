<?php
namespace random;

/**
 * Generates random numbers for the purpose of Arithmetic Expression Generator.
 * Has some advanced features when generating numbers
 *
 */
class Random {
    // Generates random number withing range. 
    // 
    // Depending of $weight it will favor generating smaller or larger values
    // 
    // $weigth = 1 - neutral
    // $weigth < 1...0 - preferes higher values
    // $weigth > 1...n preferes lower values
    //
    // $min:double - start range
    // $max:double - end range
    // $weight:double - favor higher or lower numbers 0.01...1...n
    public static function getRandDouble($min, $max, $weight=1) {
            $randomDouble = lcg_value();
            $result = $min + ($max + 1 - $min) * (pow($randomDouble, $weight));
            return round($result, 2);
    }

    public static function getRandInt($min, $max, $weight=1) {
            $randomDouble = lcg_value();
            $result = floor($min + ($max + 1 - $min) * (pow($randomDouble, $weight)));
            //  min + (max + 1 - min) * (Math.pow(randomDouble, probabilityPower));
            return $result;
    }
}

