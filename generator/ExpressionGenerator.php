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
        $result = rand($c->minTotal, $c->maxTotal);
        \printer\Printer::print_ln($result);

        $currentLevel = 0;
        $operands = $this->getOperands($result);
        $resultNode = new \model\ExpressionNode($operands);
        $innerNode=$resultNode;
        var_export($resultNode);
        while ($currentLevel < $c->maxNumOfOperands)
        {
            $innerNode = $this->createBranch($innerNode);
            $currentLevel++;
        }

        \printer\Printer::print_ln("---------------------------");
        \printer\Printer::print_ln(var_export($resultNode));
        return $resultNode;
    }

    private function createBranch($node)
    {
        \printer\Printer::print_ln(var_export($node));
        $c = $this->config;

        $count = sizeof($node->getElements());
        for ($i = 0;$i < $count;$i++)
        {
            $current = $node->get($i);

            if (is_numeric($current))
            {
                $innerOperands = $this->getOperands($current);
                $innerNode = new \model\ExpressionNode($innerOperands);
                $node->set($i, $innerNode);
            }

            else if (is_array($current))
            {
                $innerOperands = $this->createBranch($current);
                $innerNode = new \model\ExpressionNode($innerOperands);
                $node->set($i, $innerNode);             
            }
        }
        return $innerNode;
    }

    private function goToNextBranch($currentDepthLevel = 0)
    {
        $c = $this->config;
        if ($currentDepthLevel < $c->maxNumOfOperands)
        {
            return true;
        }
        return false; //$this->rand_bool();
        
    }

    private function rand_bool()
    {
        return (bool)random_int(0, 1);
    }

    private function getOperands($total)
    {
        $operands = [];
        $min = $total * $this->config->minOperandRange;
        $max = $total * $this->config->maxOperandRange;

        $firstOperand = $this->getFirstOperand($min, $max);
        $operator = $this->getRandomOperator();
        $secondOperand = $this->getSecondOperand($firstOperand, $operator, $total);

        $operands[] = $firstOperand;
        $operands[] = $operator;
        $operands[] = $secondOperand;

        return $operands;
    }

    private function getFirstOperand($min, $max)
    {
        return \random\Random::getRandInt($min, $max, 0.5);
    }

    private function getSecondOperand($firstOperand, $operator, $total)
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

    public function getRandomOperator()
    {
        return $this
            ->config
            ->availableOperators[rand(0, sizeof($this
            ->config
            ->availableOperators) - 1) ];
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
}

