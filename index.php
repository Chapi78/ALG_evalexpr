<?php

function prio($test) {
    if($test == '^') 
        return 3;
    else if($test == '/' || $test == '*' || $test == '%')
        return 2;
    else if($test == '+' || $test == '-')
        return 1;
    else
        return -1;
}

function eval_expr() {
    global $argv;
    $input = $argv[1];
    $input = str_split($input);
    $output = [];
    $stack = [];
    $line = 0;
    $new_num = true;
    $handle_operators = [
        "+",
        "-",
        "*",
        "/",
        "%"
    ]; //(50-6)*4/2-50*2*((2+5)*10) = 506-4*2/50225+10**-
    foreach($input as $key => $character) { // iterate chaque nombre mis en parametre
        // echo "\n" .$key. "\n"; //comms
        // echo $character."\n"; //comms
        $limite = count($stack) - 1;

        // echo "\n".$line."\n";
        if(is_numeric($character)) {
            // echo "\nchifre register:".$character."\n";
            // if(isset($output[$line]))
            if($new_num == true) {
                $output[] = $character; // si nouveau index
                $new_num = false;
            } else {
                if(isset($output[$line])) {
                    $output[$line] .= $character; // push le nombre dans le bonne index
                } else {
                    $output[] = $character;
                }
            }
        } else if($character == '(') { // prend en compte l'ouverture de la paranthese
            $stack[] = $character;
            // if(end($stack) == '(') {
            //     $line++;
            // }
        } else if($character == ')') { // fermer la paranthese range dans le bonne ordre les operateur de stack
            while($stack[count($stack)-1] != '(') {
                array_push($output, $stack[count($stack)-1]);
                array_pop($stack);
                $line++;
            }
            // $line++;
            array_pop($stack);
            // var_dump($stack); //comms
        } else {
            while(count($stack) != 0 && prio($character) <= prio($stack[count($stack)-1])) {
                // echo $stack[count($stack)-1]."\n";
                array_push($output, $stack[count($stack)-1]); // pour les operateur de calcul
                array_pop($stack);
                $line++;
                $new_num = true;
            }
            $line++;
            array_push($stack, $character);
        }
        // var_dump($output); //comms
        // var_dump($stack); //comms
    }
    while(count($stack) !== 0) {
        array_push($output, $stack[count($stack) - 1]); // concat stack et output
        array_pop($stack);
    }
    // $final_product = [];
    // while(count($output) !== 0) {
    //     array_push($final_product, array_shift($output)." ");
    // }
    // $output = $final_product;
    return compute($output);
}

function compute($postfix) {
    $numbers = array();
    $handle_operators = [
        "+",
        "-",
        "*",
        "/",
        "%"
    ];
    $result = "";
    // $split = str_replace();
    foreach($postfix as $operand) {
        // echo $operand."\n"; //comms
        if(is_numeric($operand)) {
            $numbers[] = $operand;
        } elseif (in_array($operand, $handle_operators)) {
            $first = array_pop($numbers);
            $second = array_pop($numbers);

            switch ($operand) {
                case '+':
                    $result = $second + $first;
                    // echo "\n+ second: ".$second."\nfirst :".$first; //comms
                    break;
                case '-':
                    $result = $second - $first;
                    // echo "\n- second: ".$second."\nfirst :".$first; //comms
                    break;
                case '/':
                    $result = $second / $first;
                    // echo "\n/ second: ".$second."\nfirst :".$first; //comms
                    break;
                case '*':
                    $result = $second * $first;
                    // echo "\n* second: ".$second."\nfirst :".$first; //comms
                    break;
                case '%':
                    $result = $second % $first;
                    // echo "\n% second: ".$second."\nfirst :".$first; //comms
                    break;
            }
            array_push($numbers, $result);
        }
        // echo "\n".$result; //comms
    }
    // echo "\n\nresultat: ". $result; //comms
    return $result;
}

eval_expr();

?>