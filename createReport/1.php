<?php
    include '../media/php/db_config.php';

    $actions = R::findAll('actions');

    $filtered = array();
    
    foreach($actions as $action) {
        if(empty($filtered[$action['country']])) {
            $filtered[$action['country']] = array(
                "country" => $action['country'],
                "count" => 1
            );
        }   else {
            $filtered[$action['country']]['count']++;
        }
    }

    usort($filtered, function($a, $b) {
        return $a['count'] <=> $b['count'];
    });

    $filtered = array_reverse($filtered);

    echo json_encode($filtered);

?>