<?php

    include '../media/php/db_config.php';

    $categories = R::findAll('categories');
    $cats = [];
    foreach($categories as $c) $cats[] = $c['categoriename'];

    echo json_encode($cats);

?>