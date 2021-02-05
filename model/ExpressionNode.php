<?php

namespace model;

class ExpressionNode {
    private $elements;
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


    public function size() {
        $count = sizeof($this->elements);
        return $count;
    }

    public function hasChildNodes() {
        foreach ($this->elements as $element) {
            if (is_object($element) || is_array($element)) {
                return true;
            }
        }
        
        return false;
    }
            
    function getElements() {
        return $this->elements;
    }

    function getVisited() {
        return $this->visited;
    }

    function setElements($elements) {
        $this->elements = $elements;
    }

    function setVisited($visited) {
        $this->visited = $visited;
    }

        
    public function visit() {
        $this->visited++;
    }
    
}
