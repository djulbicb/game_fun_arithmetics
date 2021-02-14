<?php

namespace formatter;

require_once 'model/ExpressionNode.php';

class ExpressionFormatter {

    /**
     * Prints expression as a formatted string
     * 
     * @param type $node :: ExpressionNode
     */
    public function format($node) {
        $output = $this->formatArray($node);

        $output = substr($output, 1, strlen($output) - 2);

        return $output;
    }

    public function formatArray($node) {
        $content = "";

        for ($i = 0; $i < sizeof($node->getElements()); $i++) {
            $before = $i - 1;
            $after = $i + 1;

            $current = $node->get($i);

            if (is_object($current)) {
                $content .= $this->formatArray($current);
            } else {
                $current = $node->get($i);

                // Add parentheses in front of negative elements
                if ($before >= 0) {
                    $beforeEleme = $node->get($before);
                    if ($current <= 0 && ($beforeEleme === "-" || $beforeEleme === "+" )) {
                        $current = "(" . $current . ")";
                    }
                }

                $content .= $current;
            }
        }
        return "({$content})";
    }

    /**
     * Prints expression as a basic formatted string
     * 
     * @param type $node :: ExpressionNode
     */
    public function formatBasic($node) {
        $content = "";
        for ($i = 0; $i < sizeof($node->getElements()); $i++) {
            $current = $node->get($i);

            if (is_object($current)) {
                $content .= $this->formatBasic($current);
            } else {
                $content .= $current;
            }
        }
        return "({$content})";
    }

    public function round_decimal_numbers_in_string($expressionStr) {
        $output= array();
        
        $segments = \util\Util::explode_string($expressionStr, true, "+", "-", "/", "*", "(", ")");
        foreach ($segments as $segment) {   
            if (is_numeric($segment) && \util\Util::isDecimal(floatval($segment))) {
                $roundedSegment= round($segment, 2);
                
                if ($x == 0) {
                    $output[] = $segment;
                } else {
                    $output[] = $roundedSegment;
                }
                
            } else {
                $output[] = $segment;
            }
        }
        return implode("", $output);
    }


    public function collapse(&$node) {
        $collapsed = array();
        $skipNextChild = false;
       
        $skipAllNext = false;
        
        for ($index = 0; $index < count($node->getElements()); $index++) {            
            if ($skipNextChild) {
                $skipNextChild = false;
                continue;
            }
            
            $prev = $node->get($index - 1);
            $current = $node->get($index);
            $next = $node->get($index + 1);
            
            if ($skipAllNext) {
                \printer\Printer::print_ln("skipping");
                $collapsed[] = $current;
                continue;
            }
            
            if (is_a($current, '\model\ExpressionNode')) {
                if (count($current->getElements()) == 0) {
                    // element is empty so remove expression sign in front
                    if ($this->isMinusPlus($prev)){
                        array_pop($collapsed);    
                        $skipAllNext=true;
                    }
                    
                } else if (count($current->getElements()) == 1) {
                    for ($y = 0; $y < count($current->getElements()); $y++) {
                        $collapsed[] = $current->get($y);
                    }
                    $skipAllNext=true;
                } else if ($this->isMinDivMult($prev) || $this->isDivMult($next)) {

                    $new = $this->collapse($current);
                    //var_export($new->getElements());
                    $collapsed[] = $new;
                } else {
                    $childNodes = $current->collapseChildren();

                    if (count($childNodes) == 1) {
                        for ($y = 0; $y < count($childNodes->getElements()); $y++) {
                            $collapsed[] = $childNodes[$y];
                        }
                        continue;
                    }

                    for ($x = 0; $x < count($childNodes); $x++) {
                        $child = $childNodes[$x];
                        $prevChild = $this->getOrNull($childNodes, $x - 1);
                        $nextChild = $this->getOrNull($childNodes, $x + 1);

                        $collapsed[] = $child;
                    }
                }
            } else if (is_object($current)) {
                    $collapsed[] = $current;
            }
            
            else {                
                if ( $current == 0 && $this->isMinusPlus($prev) && $this->isMinusPlus($next)) {
                    if ($this->isMinusPlus($prev)) {
                        array_pop($collapsed);
                        $skipAllNext=true;
                    }
                } 
                else if (is_null($prev) && $current == 0 && $this->isPlus($next)) {
                    $skipNextChild = true;
                    $skipAllNext=true;
                    // 17-7-(-1-(-9)+(-3)+(-1)-(+(-1)))
                }
                else if (is_null($prev) && $current == 0 && $this->isMinus($next)) {
                
                    $skipAllNext=true;
                    // 17-7-(-1-(-9)+(-3)+(-1)-(+(-1)))
                }
               else if ($current == 0 && is_null($next) && $this->isMinusPlus($prev)) {
                    array_pop($collapsed);
                    $skipNextChild = true;
                    $skipAllNext=true;
                }
                else if ($current == 0 && is_null($next)) {
                    // get rid of 0 at end
                    // 12-4+(-2)-(-7)+(0)+(-1)+(0)+(-1)+(0)+(0)
                    if ($this->isMinusPlus($prev)) {
                        array_pop($collapsed);
                        $skipNextChild = true;
                        $skipAllNext=true;
                    }
                }           

                else {
                    $collapsed[] = $current;
                } 
            }
        }

        return new \model\ExpressionNode($collapsed);
    }

    public function getOrNull($array, $incex) {
        if ($incex < 0 || $incex > count($array) - 1) {
            return null;
        }
        return $array[$incex];
    }

      public function isMinus($element) {
        if (in_array($element, array("-"))) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isPlus($element) {
        if (in_array($element, array("+"))) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isMinDivMult($element) {
        if (in_array($element, array("-", "*", "/"))) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isMinusPlus($element) {
        if (in_array($element, array("+", "-"))) {
            return true;
        } else {
            return false;
        }
    }

    public function isMinusPlusNull($element) {
        if (in_array($element, array("+", "-", null))) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isDivMult($element) {
        return in_array($element, array("*", "/"));
    }
}
