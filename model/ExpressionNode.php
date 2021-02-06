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
    
    public function get($index) {
        return $this->elements[$index];
    }
    
    public function set($index, $value) {
        $this->elements[$index] = $value;
    }
  
    function setElements($elements) {
        $this->elements = $elements;
    }
    
    public function is_Collapsable() {
        for ($i = 0 ; $i < sizeof($this->getElements()); $i++ ) {
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
            return;
        }
        
        $newElements = array();
        
         for ($i = 0 ; $i < sizeof($this->getElements()); $i++ ) {
            $element = $this->get($i);

            if (is_object($element)) {
                foreach ($element->getElements() as $child) {
                    $newElements[] = $child;
                }
            } else {
                \printer\Printer::print_ln($element);
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
