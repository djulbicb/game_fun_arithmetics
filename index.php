<?php
    require_once './random/Random.php';
    require_once './config/ExpressionConfig.php';
    require_once './generator/ExpressionGenerator.php';
    require_once './printer/Printer.php';
    require_once './solver/ExpressionSolver.php';
?>
<!DOCTYPE html>
    
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        echo 'sss';
        echo 'sssss';
        
        $config = config\ExpressionConfig::level_1();
        $creator = new \generator\ExpressionGenerator($config);
        $test = $creator->getDivisiblesOfNumber(34);
        
        $x = $creator->create();
        \printer\Printer::print_to_index_table($x);
        
        $output = sprintOperands($x);
        \printer\Printer::print_ln($output);
        
        
//        $solver = new \solver\ExpressionSolver();
//        $result = $solver->solve($output);
        
        
        function sprintOperands ($operands) {
	$content = "";
	for ($i=0; $i < sizeof($operands); $i++) { 
		$current = $operands[$i];

		if (is_array($current)) {
			$content .= sprintOperands($current);
		} else {
			$content .= $current;
		}
	}

	return "({$content})";
        }

        ?>
    </body>
</html>
