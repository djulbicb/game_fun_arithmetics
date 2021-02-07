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
        
            for ($i=0; $i < sizeof($node->getElements()); $i++) { 
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
     public function formatBasic ($node) {
        $content = "";
        for ($i=0; $i < sizeof($node->getElements()); $i++) { 
            $current = $node->get($i);

            if (is_object($current)) {
                $content .= $this->formatBasic($current);
            } else {
                $content .= $current;
            }
        }
        return "({$content})";
    }
    
    public function collapse (&$node) {
        $collapsed = array();
        
        for ($index = 0; $index < count($node->getElements()); $index++) {                  
            $prev = $node->get($index - 1);
            $current = $node->get($index);
            $next = $node->get($index + 1);
            
            if (is_a($current , '\model\ExpressionNode')) {  
                if (count($current->getElements()) == 1) {
                    for ($y = 0; $y < count($childNodes->getElements()); $y++) {
                        $collapsed[] = $childNodes[$y];
                    }
                }
                else if ($prev === "-" ) {
                    $collapsed[] = $current;
                } else {
                    $childNodes = $current->collapseChildren();
                    $skipNextChild = false;
                    
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
                        
                        if (is_numeric($child ) && $child  == 0 &&  $this->isMinusPlusNull($prevChild) && $this->isMinusPlusNull($nextChild) ) {
                            if ($skipNextChild) {
                                $$skipNextChild= false;
                                continue;
                            }
                            if ($this->isMinusPlusNull($prevChild)) {
                                array_pop($collapsed);
                                $skipNext = true;
                            }
                            // skip adding 0
                        } else {
                            $collapsed[] = $child;
                        } 
                        
                       // $collapsed[] = $child;
                    }
                }
            } else {
                
//                if ($current == 0 &&  $this->isMinusPlusNull($prev) && $this->isMinusPlusNull($next) ) {
//                    if ($this->isMinusPlus($prev)) {
//                        array_pop($collapsed);
//                    }
//                     // skip adding 0
//                } else {
//                    $collapsed[] = $current;   
//                }
                
                 $collapsed[] = $current;   
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


