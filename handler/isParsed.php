<?php

    include '../media/php/db_config.php';
    
    $isParsed = R::findOne('parsed', 'id = ?', [1]);

    $status = 'false';

    if(!empty($isParsed)) {
        if($isParsed['parsed'] == "1") $status = 'true';
        else $status = 'false';
    }   else $status = 'false';

    echo json_encode(array(
        "status" => $status
    ));



?>