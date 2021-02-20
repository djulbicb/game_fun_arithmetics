<?php

namespace model;

class ExpressionSection
{
    public $section;
    public $substringStartIndex;
    public $substringLength;

    public function __construct($section, $substringStartIndex, $substringLength){
        $this->section = $section;
        $this->substringStartIndex = $substringStartIndex;
        $this->substringLength = $substringLength;
    }
}