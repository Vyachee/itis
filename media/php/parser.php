<?php

    class Parser {
        function getParsedData($file) {  // Returns array in format [date, ip, location]

            // Read everything from logs and write it to array $array
            $input = fopen($file, 'r');
            $array = null;
    
            if($input) {
                while(($buffer = fgets($input)) !== false) {
                    $array[] = $buffer;
                }
            }
    
            fclose($input); // Close file
    
            $items = array();
            for($i = 0; $i < sizeof($array); $i++) {
    
                $matches = null;    // IP
                $matches1 = null;   // DATE & TIME
                $matches2 = null;   // USER LOCATION
    
                preg_match("/[0-9]*\.[0-9]*\.[0-9]*\.[0-9]*/", $array[$i], $matches);
                preg_match("/[0-9]+\-[0-9]+\-[0-9]+ [0-9]+\:[0-9]+\:[0-9]+/", $array[$i], $matches1);
                preg_match("/https:.*/", $array[$i], $matches2);
    
                $items[] = array(
                    "date" => $matches1[0],
                    "ip" => $matches[0],
                    "location" => $matches2[0]
                );
            }
    
            return $items;
        }

        function getAllActions($parsedData) {
            $actions = array();

            foreach($parsedData as $item) {
                $aboutIP = R::findOne('ips', 'ip = ?', [$item['ip']]);
                
                if(empty($aboutIP['location']))
                    $country = "Unknown";
                else $country = $aboutIP['location'];

                $actions[] = array(
                    "ip" => $item['ip'],
                    "date" => $item['date'],
                    "country" => $country,
                    "action" => $this->getACtion($item['location'])
                );
            }

            return $actions;
        }

        function getAction($location) {
            $localLocation = null;
            preg_match("/\.com(.*)/", $location, $localLocation);
            $localLocation = $localLocation[1];

            $isSuccessPay = strpos($localLocation, "success_pay");
            $isPay = strpos($localLocation, "pay?");
            $isCart = strpos($localLocation, "cart?");

            if($isSuccessPay) return "SuccessPay";
            if($isPay) return "Pay";
            if($isCart) return "InCart";

            return "On page: " . $localLocation;
        }

        function getCategories($parsedData) {
            $categories = array();
            foreach($parsedData as $item) {
                $m = null;
                preg_match("/\.com\/(.*)\//", $item['location'], $m);

                if(sizeof($m) == 2) {

                    $m2 = null;
                    preg_match("/success_pay/", $m[1], $m2);

                    if(empty($m2)) {
                        $splitted = explode("/", $m[1]);
                        $categories[$splitted[0]] = $splitted[0];
                    }
                }
            }
            return $categories;
        }

        function getCatergoriesAndGoods($parsedData) {
            $catsAndGoods = array();
            foreach($parsedData as $item) {
                $m = null;
                preg_match("/\.com\/(.*)\//", $item['location'], $m);

                if(sizeof($m) == 2) {

                    $m2 = null;
                    preg_match("/success_pay/", $m[1], $m2);

                    if(empty($m2)) {

                        $splitted = explode("/", $m[1]);
                        $catsAndGoods[] = $splitted;
                    }
                }
            }

            $catAndGoods = array();

            foreach($catsAndGoods as $item) {

                if(sizeof($item) == 2) {

                    if(empty($catAndGoods[$item[0]])) {

                        $catAndGoods[$item[0]] = array(
                            "categorie_name" => $item[0],
                            "goods" => array($item[1])
                        );
                    }   else {
                        $isExist = in_array($item[1], $catAndGoods[$item[0]]['goods']);

                        if($isExist == false)
                            array_push($catAndGoods[$item[0]]['goods'], $item[1]);
                    }
                }
            }
            return $catAndGoods;
        }

        function getCartsAndPays($parsedData) {

            $carts = array();
            $pays = array();
            $successPays = array();


            foreach($parsedData as $item) {
                $location = $item['location'];

                $ip = $item['ip'];
                $date = $item['date'];

                $hasCart = null;
                $hasPay = null;
                $hasSuccessPay = null;

                preg_match("/(\/cart.*)/", $location, $hasCart);
                preg_match("/(\/pay.*)/", $location, $hasPay);
                preg_match("/(\/success_pay.*)/", $location, $hasSuccessPay);

                if(!empty($hasCart)) {

                    $goods_id = null;
                    $amount = null;
                    $cart_id = null;

                    preg_match("/goods_id=(.*)&a/", $hasCart[0], $goods_id);
                    preg_match("/amount=(.*)&/", $hasCart[0], $amount);
                    preg_match("/cart_id=(.*)/", $hasCart[0], $cart_id);

                    $carts[$cart_id[1]] = array(
                        "cart_id" => $cart_id[1],
                        "goods_id" => $goods_id[1],
                        "amount" => $amount[1]
                    );

                }

                if(!empty($hasPay)) {
                    $user_id = null;
                    $cart_id = null;
                    preg_match("/=([0-9]+)&/", $hasPay[0], $user_id);
                    preg_match("/cart_id=([0-9]+)/", $hasPay[0], $cart_id);

                    $pays[$cart_id[1]] = array(
                        "user_id" => $user_id[1],
                        "cart_id" => $cart_id[1]
                    );
                }
                if(!empty($hasSuccessPay)) {
                    $paid_cart_id = null;
                    preg_match("/pay_([0-9]+)\//", $hasSuccessPay[0], $paid_cart_id);
                    $successPays[] = array(
                        "cart_id" => $paid_cart_id[1],
                        "ip" => $ip,
                        "date" => $date
                    );
                }
            }

            return [
                "carts" => $carts,
                "pays" => $pays,
                "successPays" => $successPays
            ];
        }

        function printConfig() {

            global $host, $dbname, $user, $password, $useOwnPath, $filename;

            if($useOwnPath) $useOwnPath = "Yes";
            else $useOwnPath = "No";

            if(empty($password)) $password = "(empty)";

            $text = "--------------- CONFIGURATION.php ---------------" . "\n<br>";
            $text .= "Database host: " . $host . "\n<br>";
            $text .= "Database name: " . $dbname . "\n<br>";
            $text .= "Database login: " . $user . "\n<br>";
            $text .= "Database password: " . $password . "\n<br>";
            $text .= "---------------------- File config: -----------------------" . "\n<br>";
            $text .= "Use own path: " . $useOwnPath . "\n<br>";
            $text .= "Filename/path: " . $filename . "\n<br>";
            $text .= "----------------------- API config: -----------------------" . "\n<br>";
            $text .= "API KEY: " . API_KEY . "\n<br>";
            $text .= "API BASE URL: " . BASE_API_URL . "\n<br>";
            $text .= "--------------- CONFIGURATION.php ---------------" . "\n<br>";

            echo $text;
        }
    }

?>