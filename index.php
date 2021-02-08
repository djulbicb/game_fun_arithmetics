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
        
        echo 'sss';
        echo 'sssss';
        
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
        
//        $solver = new \solver\ExpressionSolver();
//        $result = $solver->solve($output);
//        \printer\Printer::print_ln($result );
        ?>
    </body>
</html>
