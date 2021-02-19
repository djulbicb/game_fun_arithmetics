<?php

require_once './random/Random.php';
require_once './config/ExpressionConfig.php';
require_once './generator/ExpressionGenerator.php';
require_once './printer/Printer.php';
require_once './solver/ExpressionSolver.php';
require_once './formatter/ExpressionFormatter.php';

$friendlyMessage = "Hello there - visit https://github.com/regenbar/game_fun_arithmetics. Made by bojan djulbic :)";

/*
 * Generate expression
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $config = getConfig();
    
    $creator = new \generator\ExpressionGenerator($config);
    $formatter = new formatter\ExpressionFormatter();

    // Create a tree of nodes that represents expression
    $expressionNode = $creator->create();
    
    // Collapse nodes to simplify expression
    for ($index = 0; $index < $config->maxGenerateDepth * 2; $index++) {
        $expressionNode = $formatter->collapse($expressionNode);
    }
    
    // Transform node to string representation
    $expressionStr = $formatter->format($expressionNode);
    
    // Round off values to create a pretty expression
    $roundedExpressionStr = $formatter->round_decimal_numbers_in_string($expressionStr);
    
    echo $roundedExpressionStr;
    return;
}

/*
 * Solve expression
 */
else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paramName = "expression";
    
    if (!isset($_POST[$paramName])) {
         echo $friendlyMessage;
         return;
    }
   
    $expression = $_POST[$paramName];
    if (!is_clean($expression)) {
        echo $friendlyMessage;
        return;
    }
    
    $solver = new \solver\ExpressionSolver();
    $expressionStr = $_POST[$paramName];
   
    $basicConfig = \config\ExpressionConfig::level_1();
    
    $result = $solver->solve($basicConfig, $expressionStr);
    echo $result;
    return;
}

else {
    echo $friendlyMessage;
    return;
}
    

/*
 * Helper functions
 */

function is_clean ($string) {
    if (preg_match("/[a-zA-Z]/i", $string)) {
        return false;
    }
    
    return true;
}

function getConfig() {
    $level = 1;
    
    $paramName = "level";
    if (isset($_GET[$paramName]) && is_numeric($_GET[$paramName])) {
        $level= intval($_GET[$paramName]);        
    }
    
    switch ($level) {
        case 1:
            return \config\ExpressionConfig::level_1();
        case 2:
            return \config\ExpressionConfig::level_2();
        default :
            return \config\ExpressionConfig::level_1();
    }
}