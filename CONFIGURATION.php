<?php

    // DATABASE CONFIG

    $host = "127.0.0.1";
    $dbname = "itis2";
    $user = "root";
    $password = "";

    // INPUT FILE

    $useOwnPath = false;    // false - если файл лежит в папке input, true - если нужен свой путь до файла

    $filename = "logs.txt"; // Имя файла, в случае $useOwnPath = false; путь к файлу, в случае $useOwnPath = true

    // API KEYS

    define("API_KEY", "a8b608bb5fdbfd103d6eb506887a433b");

    // URLS

    define("BASE_API_URL", "http://api.ipstack.com/");


?>