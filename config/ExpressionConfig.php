<?php

namespace config;

class ExpressionConfig {

	public $randomGenerationWeight = 1;

	public $availableOperators;
	public $minTotal;
	public $maxTotal;
	public $maxNumOfOperands;
	public $isUseDecimalNumbers;
	public $isOnlyPositiveNumbers;
	public $minOperandRange;
	public $maxOperandRange;

	public static function level_1() {
		$config = new ExpressionConfig(); 
		$config->availableOperators = ["+", "-"];  // , "+", "-", "*", "/"
		$config->minTotal = 0;
		$config->maxTotal = 50;
		$config->maxNumOfOperands = 4;
		$config->isOnlyWholeNumbers = true;
		$config->isOnlyPositiveNumbers = true;
		$config->minOperandRange = -1;
		$config->maxOperandRange = 2;
		return $config;
  	} 
}

