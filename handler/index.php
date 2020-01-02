<?php
     /********************************
     * 
     *  Test task for itis.is74.ru
     *  by Grinvald Vyacheslav 
     *      vk.com/gora_pl
     * 
     ********************************/

    include "../media/php/parser.php";
    include "../media/php/internet.php";

    set_time_limit(0);

    $net = new Internet;
    $parser = new Parser;

    if($_GET['reboot'] == '1') {
        $net->clearAll();
        header('Location: index.php');
    }

    $status = $net->isParsedAll();

    $start_time = time();

    if($status == true) exit('Все логи уже обработаны, нажмите <a href="index.php?reboot=1">Да</a>, если хотите обработать всё заново. Или <a href="../"> вернуться на страницу отчетов</a>');

    if($useOwnPath == false) $file = '../input/' . $filename;
    else $file = $filename;

    $parser->printConfig();

    if($_GET['start'] == 'true') {

        R::nuke();

        // Представление логов в виде массива массивов с полями [date, ip, location (URL)]

        echo "Подготовка логов...";

        $parsedData = $parser->getParsedData($file);
    
        echo "Готово";

        // Проходится по каждому IP в логах и определяет страну IP, создает таблицу ips [ip / location]

        echo "<br>Получаю страну каждого IP...";


        $net->getAndPushLocations($parsedData);
    
    
        echo "Готово";

        // Создает таблицу actions [action / ip / date / country]

        echo "<br>Анализирую действия всех пользователей на сайте...";

        $actions = $parser->getAllActions($parsedData);
        $net->pushAllActions($actions);
    
        echo "Готово";

        // Создает таблицу categories [categoriename]

        echo "<br>Получаю категории товаров...";

        $cats = $parser->getCategories($parsedData);
        $net->pushCategories($cats);
    
        echo "Готово";

        // Получает все корзины, оплаты и завершенные оплаты, 
        // создает таблицу transactions [userid / goods_id / amount / cart_id / ip / date]

        echo "<br>Анализирую транзакции...";
    
        $cartsPaysSuccess = $parser->getCartsAndPays($parsedData);
    
        $carts = $cartsPaysSuccess['carts'];
        $pays = $cartsPaysSuccess['pays'];
        $successPays = $cartsPaysSuccess['successPays'];
    
        $net->pushAllTransactions($carts, $pays, $successPays);
    
        echo "Готово";

        // Получает все товары по категориям, создает таблицу goods [goodname / categorie]

        echo "<br>Получаю информацию о товарах по категориям...";

        $goodsAndCategories = $parser->getCatergoriesAndGoods($parsedData);
        $net->pushGoods($goodsAndCategories);

        echo "Готово";

        echo "<br>Вся обработка завершена.";

        $net->setParsedAll(true);

    }   else {
        echo '<br>Чтобы запустить обработку логов нажмите <a href="index.php?start=true">Старт</a><br>';
        echo 'Обработка занимает много времени (&#8776; 1000 сек., в случае с тестовым заданием)';
        
    }

    $end_time = time();
    $difference = $end_time - $start_time;
    // sleep(10);
    echo "<br><br>Выполнено за: " . $difference . " сек.";

?>