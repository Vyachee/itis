<?php

    include '../media/php/db_config.php';
    
    $allAcitons = R::findAll('actions');

    $times = array();

    // брать дату и час из date и записывать кол-во в массив

    foreach($allAcitons as $item) {
        $elem = null;
        preg_match("/\d{4}-\d{2}-\d{2} \d{2}/", $item['date'], $elem);
        if(empty($times[$elem[0]])) $times[$elem[0]] = array(
            "date" => $elem[0],
            "count" => 1
        );
            else $times[$elem[0]]['count']++;
    }

    $amountDates = sizeof($times);
    $amountOfRequests = 0;

    foreach($times as $item) 
        $amountOfRequests += $item['count'];
    
    $elements = array();
    foreach($times as $item)
        $elements[] = $item;
    
    $average = intval(($amountOfRequests / $amountDates * 100)) / 100;

    $result = [
        "average" => $average,
        "info" => $elements
    ];

    echo json_encode($result);


?>