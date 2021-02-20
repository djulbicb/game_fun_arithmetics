<?php
namespace model;

class ExpressionSolveResult {
    public $result;
    public $status;

    public function __construct($result, $status){
        $this->result = $result;
        $this->status = $status;
    }
}
