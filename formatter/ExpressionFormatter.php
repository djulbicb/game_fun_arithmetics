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

    public function collapse(&$node) {
        $collapsed = array();
        $skipNextChild = false;
        $found = false;
        $break = false;
        
        for ($index = 0; $index < count($node->getElements()); $index++) {   
            if ($skipNextChild) {
                $skipNextChild = false;
                continue;
            }
            
            $prev = $node->get($index - 1);
            $current = $node->get($index);
            $next = $node->get($index + 1);
            
            if (!$break && is_a($current, '\model\ExpressionNode')) {
                if (count($current->getElements()) == 0) {
                    // element is empty so remove expression sign in front
                    if ($this->isMinusPlus($prev)){
                        array_pop($collapsed);    

                    }
                    
                } else if (count($current->getElements()) == 1) {
                    for ($y = 0; $y < count($current->getElements()); $y++) {
                        $collapsed[] = $current->get($y);
                    }
                } else if ($this->isMinDivMult($prev)) {

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
            } 
            else {
                if (is_object($current)) {
                    $collapsed[] = $current;
                }
                else if ( $current == 0 && $this->isMinusPlus($prev) && $this->isMinusPlus($next)) {
                    if ($this->isMinusPlus($prev)) {
                        array_pop($collapsed);
                        $break = true;
                    }
                } 
                
                else if ($current == 0 && is_null($next)) {
                    // get rid of 0 at end
                    // 12-4+(-2)-(-7)+(0)+(-1)+(0)+(-1)+(0)+(0)
                    if ($this->isMinusPlus($prev)) {
                        array_pop($collapsed);
                        $break = true;
                    }
                    
                } else if (is_null($prev) && $this->isPlus($current) && is_object($next)) {
                    // 17-7-(-1-(-9)+(-3)+(-1)-(+(-1)))
                }               

                else {
                    $collapsed[] = $current;
                } 
                
//                $collapsed[] = $current;
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
}
