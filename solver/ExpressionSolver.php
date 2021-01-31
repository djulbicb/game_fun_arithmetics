<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace solver;

define('__ROOT__', dirname(dirname(__FILE__))); 
require_once __ROOT__ . '/model/ExpressionSection.php';

class ExpressionSolver
{
	// Solves an arithmetic arexpression.
	// $expressionStr : string - example: ((10+20)*40+50/2)
	public function solve ($expressionStr) {
            // $expression = "(20 + 30 * 5 / 4 + 10 * 5 / 2 - 4 + 3 - 2 /10 * 3 + 5 - 10 * 1 / 5 + 10 + 44 + 2 - 5 - 10 * -10  / -5 * -1)";
            // $expression = "((10+20)*40+50/2 + (10+20 + (440+2550)))*(10+50)*(5+7)-10";
            //$expression = "((10+20)*40+50/2 + (10+20 + (440+2550)))*(10+50)*(5+7)-10";
            //$expression = "((10+20)*40+50/2 + -(10+20 + (440+2550)))*(10+50)*(5+7)-10 / -5 / (20 + 30 * 5 / 4 + 10 * 5 / 2 - 4 + 3 - 2 /10 * 3 + 5 - 10 * 1 / 5 + 10 + 44 + 2 - 5 - 10 * -10  / -5 * -1)";
            
            $expression = $expressionStr;
            \printer\Printer::print_ln($expression);
            $expression = str_replace(" ", "", $expression);
            $expression = str_replace("\t", "", $expression);

            \printer\Printer::print_ln($expression);

            while(strpos($expression, "(") !== false) {
                    \printer\Printer::print_ln("------------------");
                    $expressionSection =  $this->getInner($expression, "(", ")", 0);
                    $prepared = $this->prepare_for_calculate($expressionSection->section);

                    \printer\Printer::print_to_index_table($prepared);

                    \printer\Printer::print_ln($prepared);
                    $total = $this->calculate_numbers($prepared);	

                    \printer\Printer::print_ln($total);
                    $section = $expressionSection->section;
                    $expression = str_replace($section, $total, $expression);
                    \printer\Printer::print_ln($expression);
                    \printer\Printer::print_ln($total);
            }


            $lastCalc = prepare_for_calculate($expression);
            $totalFinal = calculate_numbers($lastCalc);
            \printer\Printer::print_ln($totalFinal);

	}
        
        
        function prepare_for_calculate($calc) {
	$calc = str_replace(")", "", $calc);
	$calc = str_replace("(", "", $calc);

	// echo $calc;
	$segments = $this->explode_string($calc, true, "+", "-", "/", "*");
	// print_to_index_table($segments);

	// convert minus into negative segment
	// example [-, 10, *, 7, -, 11] into [-10, *, 7, +, -11]
	$oper = ["*", "/", "%"];
	while (in_array("-", $segments)) {
		$foundMinusIndex = array_search("-", $segments, true);

		$previous = $segments[$foundMinusIndex - 1];
		$next = floatval($segments[$foundMinusIndex + 1]);
		$nextValue = $next * -1;

		\printer\Printer::print_ln("$previous $foundMinusIndex $nextValue");

		$insertElements = array();

		if ($foundMinusIndex > 0) {
			if (!in_array($previous, $oper)) {
				$insertElements[] = "+";
			}
			$insertElements[] = $nextValue;
		} else {
			$insertElements[] = $nextValue;
		}

		array_splice($segments, $foundMinusIndex, 2, $insertElements);
	}


	\printer\Printer::print_ln("sss");
	\printer\Printer::print_to_index_table($segments);
	return $segments;
}




// 	Calculates numbers inside of string
// 	example: 
//	20 + 30 * 5 / 4 + 10 * 5 / 2 - 4 + 3 - 2 /10 * 3 + 5 - 10 * 1 / 5 + 10 + 44 + 2 - 5 - 10 * -10  / -5 * -1 = 154.9
// 	Its important that this expression doesnt have braces. If you have braces first use getInner() to extract segments 
// 	Supports arithmetic functions: / * % + - 
function calculate_numbers($segments) {
	$operatorPriority = [ ["*", "/", "%"], ["+", "-"]];

	for ($j=0; $j < count($operatorPriority); $j++) { 
		$operatorPriorityLevel = $operatorPriority[$j];

		for ($i=0; $i < count($segments); $i++) { 
			$current = $segments[$i];

			if (in_array($current, $operatorPriorityLevel)) {
				$indexOfOperator = array_search($current, $segments, true);

				$firstOperandIndex = $indexOfOperator - 1;
				$secondOperandIndex = $indexOfOperator + 1;
				$firstOperand = floatval($segments[$firstOperandIndex]);
				$secondOperand = floatval($segments[$secondOperandIndex]);


				\printer\Printer::print_ln("$firstOperand $current $secondOperand");
				switch ($current) {
					case "*":
						$total = $firstOperand * $secondOperand;
						break;
					case "/":
						$total = $firstOperand / $secondOperand;
						$total = number_format((float)$total, 3, '.', '');
						break;
					case "%":
						$total = $firstOperand % $secondOperand;
						break;
					case "+":
						$total = $firstOperand + $secondOperand;
						break;
					case "-":
						$total = $firstOperand - $secondOperand;
						break;
				}

				if (isset($total)) {
					array_splice($segments, $firstOperandIndex, $secondOperandIndex - $firstOperandIndex + 1, $total);
					\printer\Printer::print_ln("$firstOperand $current $secondOperand = $total");
				}

				// reset index to check again for other occurences of that arithmetic operator
				$i--;

				\printer\Printer::print_to_index_table($segments);
			
		}
	}
}

return $segments[0];

}


function explode_string ($content, $keepDelimiter = false, ...$delimiters) {
	$length = strlen($content);

	$segment = "";
	$segments = [];
	for ($i=0; $i < $length; $i++) { 
		$current = $content[$i];

		if (!in_array($current, $delimiters)) {

			$segment .= $current;
		} else  {
			if ($segment !== "") {
				$segments[] = $segment;
			}
			
			$segment = "";

			if ($keepDelimiter) {
				$segments[] = $current;
			}
		}
	}

	$segments[] = $segment; // last segment

	array_filter($segments);
	return $segments;	
}


function getInner($expression, $startChar, $endChar, $skipOccurence=0) {
	$length = strlen($expression);

	$startIndex = [];
	$endIndex = [];

	// Every time endChar is found both vars are incremented
	// but every time these two values are equalle current count will be reset
	$totalFoundOccurence = 0;
	$currentFoundOccurence = 0;

	for ($i=0; $i < $length; $i++) { 
		$current = $expression[$i];

		if ($current === $startChar) {
			\printer\Printer::print_ln("_change start_{$i}_");
			$startIndex[] = $i;
		}

		if ($current === $endChar) {
			\printer\Printer::print_ln("_change end_{$i}_");
			$endIndex[] = $i;
			$totalFoundOccurence++;
			$currentFoundOccurence++;

			if ($totalFoundOccurence === $skipOccurence+1) {
				break;	
			} else {
				// reset indexes if all inner segments are closed
				// because character occurence is encapsulated
				if (count($startIndex) === count($endIndex)) {
					\printer\Printer::print_ln($startIndex);
					\printer\Printer::print_ln($endIndex);
					\printer\Printer::print_ln($currentFoundOccurence);
					$startIndex = array_splice($startIndex, $currentFoundOccurence);
					$endIndex = array_splice($endIndex, $currentFoundOccurence);

					$currentFoundOccurence = 0;
				}	
			}
		}
	}

	if (!empty($endIndex)) {
		$startOffset = end($startIndex);
		$substrLength = end($endIndex) - end($startIndex) + 1;

		$substring = substr($expression, $startOffset, $substrLength);
		$out = new \model\ExpressionSection($substring, $startOffset, $substrLength);
		return $out;
	} else {
		return "";
	}
    }
}

