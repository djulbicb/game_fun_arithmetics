<?php

namespace config;

/**
 * Configuration for \generator\ExpressionGenerator and \solver\ExpressionSolver
 * Specifies how many numbers, which operators... generator will use when creating an expression
 */
class ExpressionConfig {

    public $maxSolveAttempts = 150;             //  Prevents app from doing infinite recursion loop during solving
    public $randomNumberGenerationWeight = 1;   //  Specifies whether randomly generated numbers are in lower or upper range. 1 is neutral. 0 is upper. 1..n is lower
    public $randomMaxGenerationAttemps = 10;    //  Max number of attempts to generate a number that satisfies all conditions
    public $roundDecimalPrecision = 2;          //  Round decimal number to specified precision
    public $availableOperators = ["+", "-"];    //  Expression will contain only specified operators
    public $minTotal = 0;                       //  Minimum range of generated numbers
    public $maxTotal = 50;                      //  Maximum range of generated numbers
    public $maxGenerateDepth = 2;               //  Specifies depth of generated expression. Larger numbers generates longer expressions
    public $useDecimalNumbers = false;          //  If false app will try to use only whole integer numbers
    public $minOperandRange = 1;                //  Offsets range of operands when generating numbers. 1 is default                    
    public $maxOperandRange = 1;                //  Offsets range of operands when generating numbers. 1 is default

    public static function level_1() {
        $config = new ExpressionConfig();
        $config->availableOperators = ["+", "-"];  // , "+", "-", "*", "/"
        $config->minTotal = 0;
        $config->maxTotal = 30;
        $config->maxGenerateDepth = 1;
        $config->isOnlyWholeNumbers = true;
        $config->minOperandRange = -1;
        $config->maxOperandRange = 1;
        return $config;
    }

    public static function level_2() {
        $config = new ExpressionConfig();
        $config->availableOperators = ["+", "-"];  // , "+", "-", "*", "/"
        $config->minTotal = 0;
        $config->maxTotal = 50;
        $config->maxGenerateDepth = 2;
        $config->isOnlyWholeNumbers = true;
        $config->minOperandRange = -1;
        $config->maxOperandRange = 2;
        return $config;
    }

    public static function level_3() {
        $config = new ExpressionConfig();
        $config->availableOperators = ["+", "-", "*", "/"];  // , "+", "-", "*", "/"
        $config->minTotal = 0;
        $config->maxTotal = 50;
        $config->maxGenerateDepth = 2;
        $config->isOnlyWholeNumbers = true;
        $config->minOperandRange = -1;
        $config->maxOperandRange = 2;
        return $config;
    }

    public static function level_4() {
        $config = new ExpressionConfig();
        $config->availableOperators = ["+", "-", "*", "/"];  // , "+", "-", "*", "/"
        $config->minTotal = 0;
        $config->maxTotal = 100;
        $config->maxGenerateDepth = 4;
        $config->isOnlyWholeNumbers = true;
        $config->minOperandRange = -1;
        $config->maxOperandRange = 2;
        return $config;
    }

    public static function level_5() {
        $config = new ExpressionConfig();
        $config->availableOperators = ["+", "-", "*", "/"];  // , "+", "-", "*", "/"
        $config->minTotal = 0;
        $config->maxTotal = 500;
        $config->maxGenerateDepth = 4;
        $config->isOnlyWholeNumbers = true;
        $config->minOperandRange = -1;
        $config->maxOperandRange = 2;
        return $config;
    }
}
