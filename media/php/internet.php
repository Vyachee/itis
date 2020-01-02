<?php

    include 'db_config.php';

    class Internet {

        function getResponse($url) {
            return json_decode(file_get_contents($url), true);
        }

        function getAbout($ip) {
            if(empty($ip)) return null;

            $url = BASE_API_URL . $ip . "?access_key=" . API_KEY;
            $response = $this->getResponse($url);

            return $response;
        }

        function pushIPLocations($data) {
            $ps = array();

            foreach($data as $item) {
                $p = R::dispense('ips');
                $p->ip = $item['ip'];
                $p->location = $item['location'];
                $ps[] = $p;
            }
        
            R::storeAll($ps);
        }

        function pushGoods($data) {
            $gs = array();

            foreach($data as $categorie) {

                foreach($categorie['goods'] as $good) {
                    $g = R::dispense('goods');
                    $g->goodname = $good;
                    $g->categorie = $categorie['categorie_name'];
                    $gs[] = $g;
                }
            }

            R::storeAll($gs);
        }

        function getAndPushLocations($parsedData) { 
            error_reporting(0);
            $locations = array();
            foreach($parsedData as $item) {
                if(empty($locations[$item['ip']])) {
                    
                    $infoAboutIP = $this->getAbout($item['ip']);

                    $testForError = null;
                    if(empty($infoAboutIP['country_name'])) 
                        $location = "Unknown";
                    else $location = $infoAboutIP['country_name'];

                    $locations[$item['ip']] = array(
                        "ip" => $item['ip'],
                        "location" => $location
                    );

                    $p = R::dispense('ips');
                    $p->ip = $item['ip'];
                    $p->location = $location;
                    R::store($p);
                }
            }
    
            return $locations;
        }

        function pushAllTransactions($carts, $pays, $successPays) {

            $ts = array();

            foreach($successPays as $item) {
                $payInfo = $pays[$item['cart_id']];
                    $user_id = $payInfo['user_id'];
                
                $cartInfo = $carts[$item['cart_id']];
                    $goods_id = $cartInfo['goods_id'];
                    $amount = $cartInfo['amount'];

                $t = R::dispense('transactions');
                $t->userid = strval($user_id);
                $t->goods_id = $goods_id;
                $t->amount = $amount;
                $t->cart_id = $item['cart_id'];
                $t->ip = $item['ip'];
                $t->date = $item['date'];
                $ts[] = $t;
        
            }

            R::storeAll($ts);
        }

        function pushAllActions($actions) {

            $ts = array();

            foreach($actions as $item) {
                
                $t = R::dispense('actions');
                $t->action = $item['action'];
                $t->ip = $item['ip'];
                $t->date = $item['date'];
                $t->country = $item['country'];
                $ts[] = $t;
        
            }

            R::storeAll($ts);
        }

        function pushCategories($categories) {
            $ts = array();

            foreach($categories as $item) {
                
                $t = R::dispense('categories');
                $t->categoriename = $item;
                $ts[] = $t;
        
            }

            R::storeAll($ts);
        }

        function setParsedAll(bool $status) {

            $test = R::load('parsed', 1);
            if(!empty($test)) {
                $test->parsed = $status;
                R::store($test);
            }   else {
                $s = R::dispense('parsed');
                $s->parsed = $status;
                R::store($s);
            }

        }

        function isParsedAll() {
            
            $row = R::findOne('parsed', 'id = ?', [1]);
            if(!empty($row)) return $row['parsed'];
            else return false;

        }

        function clearAll() {
            R::nuke();
        }
    }

?>