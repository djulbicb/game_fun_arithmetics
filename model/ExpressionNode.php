<?php

namespace model;

class ExpressionNode {
    private $elements = array();
    private $visited = 0;
    
    public function __construct($elements) {
        if (is_array($elements)) {
            $this->elements= $elements;
        } else {
            $this->elements= array($elements);
        }
    }
    
//    public function __toString() {
//        return "" . var_export($this->getElements());
//    }
//    
    public function get($index) {
        return \util\Util::array_get($this->elements, $index);
    }
    
    public function set($index, $value) {
        $this->elements[$index] = $value;
    }
  
    function setElements($elements) {
        $this->elements = $elements;
    }
    
    public function is_Collapsable() {
        if (count($this->getElements()) == 1) {
            return true;
        }
        
        for ($i = 0 ; $i < count($this->getElements()); $i++ ) {
            $current = $this->get($i);
            if (in_array($current, array("-", "*", "/") )) {
                //\printer\Printer::print_ln("no");
                return false;
            }
        }
        
        return true;
    }


    public function collapseChildren () {
        if (!$this->is_Collapsable()) {
            return $this->getElements();
        }
        
        $newElements = array();
        for ($i = 0 ; $i < sizeof($this->getElements()); $i++ ) {
            $element = $this->get($i);

            if (is_object($element)) {
                foreach ($element->getElements() as $child) {
                    $newElements[] = $child;
                }
            } else {
                $newElements[] = $element;
            }
        }        
        return $newElements;
    }
    
    public function hasPrimitiveChildNodes() {
        foreach ($this->elements as $element) {
            if (is_object($element)) {
                foreach ($element->getElements() as $child) {
                    if (is_object($child) || is_array($child)) {
                        return false;
                    }
                }
            }
        }
        
        return true;
    }
            
    function getElements() {
        return $this->elements;
    }

    function getVisited() {
        return $this->visited;
    }

    function setVisited($visited) {
        $this->visited = $visited;
    }

        
    public function visit() {
        $this->visited++;
    }
    
}
