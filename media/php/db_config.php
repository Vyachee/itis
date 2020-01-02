<?php

    include './../libs/php/rb.php';
    include './../CONFIGURATION.php';

    R::setup( "mysql:host=$host;dbname=$dbname", $user, $password );
?>