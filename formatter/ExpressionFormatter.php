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
        $content = "";
        for ($i=0; $i < sizeof($node->getElements()); $i++) { 
            $before = $i - 1;
            $after = $i + 1;
            
            $current = $node->get($i);
                
            if (is_object($current)) {
                $content .= ExpressionFormatter::format($current);
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
                $content .= ExpressionFormatter::formatBasic($current);
            } else {
                $content .= $current;
            }
        }
        return "({$content})";
    }
    
    public function collapse ($node) {
        for ($i=0; $i < sizeof($node->getElements()); $i++) { 
            $current = $node->get($i);

            if (is_object($current)) {
                if ($current->hasPrimitiveChildNodes()) {
//                    \printer\Printer::print_ln("+-----");
//                    var_dump($current);
//                    \printer\Printer::print_ln("+-----");
                    echo '<br>collapse</br>';
                        $current->collapseChildren();
                    
                } else {
                    return $this->collapse($current);
                }
            }
        } 
        
        return $node;
       
    }
}
