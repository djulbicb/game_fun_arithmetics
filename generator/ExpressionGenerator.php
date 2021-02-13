<?php

namespace generator;

require_once 'model/ExpressionNode.php';

class ExpressionGenerator
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function create()
    {
        $c = $this->config;
        $result = $this->generateResult();
        \printer\Printer::print_ln($result);

        $operands = $this->generateOperands($result);
        $resultNode = new \model\ExpressionNode($operands);
        
        $innerNode=$resultNode;
        $currentGenerateDepth = 0;
        while ($currentGenerateDepth < $c->maxGenerateDepth)
        {
         
            
            \printer\Printer::print_ln(">>>> Pocinje grana");
            $currentGenerateDepth++;
            $innerNode = $this->generateExpressionDepth($innerNode);
            \printer\Printer::print_ln(">>>> $currentGenerateDepth"); 
            \printer\Printer::print_ln(">>>> Zavrsava grana");   
        }
       
        return $resultNode;
    }
    
    /**
     * Generate final number. Will prefer numbers that are more divisible
     * Example: 12 is more divisible than 3. 
     *          12 can be divided with 1, 2, 3, 4, 6, 12. 
     *          3 can be divided with 1 and 3
     * @return  nunber - this number will be final result\Printer::print_ln(">>>> $currentGenerateDepth"); of expression
     */
    public function generateResult() {
        $c = $this->config;
        $min = $c->minTotal;
        $max = $c->maxTotal;
        
        $attempt = 0;
        $lastResult = 0;
        $lastResultDivisibleCount = 0;
        
        while ($attempt < $c->randomMaxGenerationAttemps) {
            $attempt++;
            
            $result = \random\Random::getRandInt($min, $max, 0.5);
            $resultDivisibleCount = \util\Util::getDivisiblesOfNumber($lastResult);
            
            if ($resultDivisibleCount >= $lastResultDivisibleCount) {
                $lastResultDivisibleCount = $resultDivisibleCount;
                $lastResult = $result;
            }
        }
        
        return $lastResult;
    }
    
        public function getRandomOperator()
    {
        return $this->config->availableOperators[rand(0, sizeof($this->config->availableOperators) - 1) ];
    }
    
    private function generateFirstOperand($min, $max)
    {
        return \random\Random::getRandInt($min, $max, 0.5);
    }

    private function generateSecondOperand($firstOperand, $operator, $total)
    {
        switch ($operator)
        {
            case '+':
                // 10 + x = 30;
                // x = 30 - 10;
                return $total - $firstOperand;
            case '-':
                // 10 - x = 30;
                // - x = 30 - 10;
                // x = - 20 * -1;
                return ($total - $firstOperand) * -1;
            case '*':
                // 10 * x = 30
                // x = 30 / 10
                return $total / $firstOperand;
            case '/':
                // 10 / x = 30
                // 10 / 30 = x
                return $firstOperand / $total;
        }
    }

    private function generateExpressionDepth($node)
    {
        $c = $this->config;
        $count = sizeof($node->getElements());
        for ($i = 0;$i < $count;$i++)
        {
            $current = $node->get($i);

            if (is_numeric($current))
            {
                $innerOperands = $this->generateOperands($current);
                $innerNode = new \model\ExpressionNode($innerOperands);
                $node->set($i, $innerNode);
            }

            else if (is_array($current))
            {
                $innerOperands = $this->generateExpressionDepth($current);
                $innerNode = new \model\ExpressionNode($innerOperands);
                $node->set($i, $innerNode);             
            } else {
                \printer\Printer::print_ln("WHAT THE FUCK");
                var_dump($current);
            }
        }
        return $innerNode;
    }

    private function goToNextBranch($currentDepthLevel = 0)
    {
        $c = $this->config;
        if ($currentDepthLevel < $c->maxGenerateDepth)
        {
            return true;
        }
        return false; //$this->rand_bool();
        
    }

    private function rand_bool()
    {
        return (bool)random_int(0, 1);
    }

    
    /**
     * Generate combination of two numbers and arithmetic operation that equals to result
     * Example: Input result is 8
     *          output is [4, "*", 2]
     * 
     * @param number $result - expected result
     * @return array - elements of arithmetic expression
     */
    private function generateOperands($result)
    {
        $c = $this->config;
        
        \printer\Printer::print_ln("====================");
        
        $operands = [];
        $min = $result * $c->minOperandRange;
        $max = $result * $c->maxOperandRange;
        
        $operator = $this->getRandomOperator();
        // if result is 0, dont allow operator to be / or *
        if ($result == 0) {
            while ($this->isDivMult($operator)) {
                $operator = $this->getRandomOperator();
            }
        }
        
        $firstOperand = $this->generateFirstOperand($min, $max);
        // if operand is / or *, dont allow operand to be 0
        while ($this->isDivMult($operator) && $firstOperand == 0) {
            $firstOperand = $this->generateFirstOperand($min, $max);
        }
        
        \printer\Printer::print_ln("$firstOperand $operator");
        
        $secondOperand = $this->generateSecondOperand($firstOperand, $operator, $result);
        // if operand is / or *, dont allow operand to be 0
        while ($this->isDivMult($operator) && $secondOperand == 0) {
            $secondOperand = $this->generateSecondOperand($firstOperand, $operator, $result);
        }
        
        \printer\Printer::print_ln("$firstOperand $operator $secondOperand");
        
        // Round operands if decimal
        if (\util\Util::isDecimal($firstOperand)) {
           \printer\Printer::print_ln("round 1");
            $firstOperand = round($firstOperand, $c->roundDecimalPrecision);
        }
        if (\util\Util::isDecimal($secondOperand)) {
            \printer\Printer::print_ln("round 2");
            $secondOperand = round($secondOperand, $c->roundDecimalPrecision);
        }

        \printer\Printer::print_ln("$firstOperand $operator $secondOperand");
        
        $operands[] = $firstOperand;
        $operands[] = $operator;
        $operands[] = $secondOperand;
        
        \printer\Printer::print_ln(">>>> Gotovi operandi");   

        return $operands;
    }



    private function calculate($firstOperand, $operator, $secondOperand)
    {
        switch ($operator)
        {
            case '+':
                return $firstOperand + $secondOperand;
            case '-':
                return $firstOperand - $secondOperand;
            case '*':
                return $firstOperand * $secondOperand;
            case '/':
                return $firstOperand / $secondOperand;
        }
    }

    private function getOppositeOperator($operator)
    {
        switch ($operator)
        {
            case '+':
                return '-';
            break;
            case '-':
                return '+';
            break;
            case '*':
                return '/';
            break;
            case '/':
                return '*';
            break;
        }
    }
    
    public function isDivMult($element) {
        return in_array($element, array("*", "/"));
    }
}

