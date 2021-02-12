<?php
    require_once './random/Random.php';
    require_once './config/ExpressionConfig.php';
    require_once './generator/ExpressionGenerator.php';
    require_once './printer/Printer.php';
    require_once './solver/ExpressionSolver.php';
    require_once './formatter/ExpressionFormatter.php';
?>
<!DOCTYPE html>
    
    <head>
        <meta charset="UTF-8">
        <title></title>
        
        <style type='text/css'>
* {
   font-family:courier, courier new, serif; }
</style>

    </head>
    <body>
        <?php

        $config = config\ExpressionConfig::level_1();
        $creator = new \generator\ExpressionGenerator($config);
        $formatter = new formatter\ExpressionFormatter();
        
        $node = $creator->create();
        $output = $formatter->format($node);
        \printer\Printer::print_ln("$output");

        //  $config->maxNumOfOperands
        for ($index = 0; $index < $config->maxNumOfOperands * 4; $index++) {
            $node = $formatter->collapse($node);
            
            $output = $formatter->format($node);
            \printer\Printer::print_ln("$output");
        }
       
        $format = $formatter->format($node);
        \printer\Printer::print_ln($format);
        
        var_dump($format);
  
        
        
//          $output = "-10/12-4-(-9+2-(-5+(0)+(1+(0))-(0-1)))";
//          $expression = "(20 + 30 * 5 / 4 + 10 * 5 / 2 - 4 + 3 - 2 /10 * 3 + 5 - 10 * 1 / 5 + 10 + 44 + 2 - 5 - 10 * -10  / -5 * -1)";
//          
//          $output = "5*-10+-10/12-4-(-9+2-(-10/12-5+0+1--1*-1))";
//          printer\Printer::print_to_index_table($output);
        $solver = new \solver\ExpressionSolver();
        $result = $solver->solve($output);
        \printer\Printer::print_ln($result );
        ?>
    </body>
</html>
