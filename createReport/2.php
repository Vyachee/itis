<?php
    include '../media/php/db_config.php';

    $needleCat = $_GET['categorie'];
    if(empty($needleCat)) exit('Missing GET argument: categorie');

    $isCatExist = R::findOne('categories', 'categoriename = ?', [$needleCat]);
    if(empty($isCatExist)) exit('Invalid catergorie: ' . $needleCat);
    
    
    $actions = R::find('actions', 'action LIKE ?', ["%" . $needleCat . "%"]);

    $countries = array();
    foreach($actions as $item) {
        if(empty($countries[$item["country"]])) {
            $countries[$item['country']] = array(
                "country" => $item["country"],
                "count" => 1
            );
        }   else {
            $countries[$item["country"]]["count"]++;
        }
    }

    usort($countries, function($a, $b) {
        return $a['count'] <=> $b['count'];
    });

    $countries = array_reverse($countries);

    $result = [
        "categorie" => $needleCat,
        "countries" => $countries
    ];


    echo json_encode($result);

?>