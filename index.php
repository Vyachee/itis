<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>IT.IS TEST TASK</title>
    <link rel="stylesheet" href="libs/css/bootstrap.min.css">
    <link rel="stylesheet" href="media/css/style.css">
</head>
<body class="bg-secondary">
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 p-3 bg-dark text-white">
                <div class="row">
                    <div class="col-md-6">
                        <h2>IT.IS TEST TASK</h2>
                    </div>
                    <div class="col-md-6 text-lg-right">
                        <h6 class="mr-auto">by Grinvald Vyacheslav</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-1">
        <div class="row">
            <div class="col-md-12 p-2 pl-3 bg-dark text-white">
                <h3>Отчетность</h3>
            </div>
        </div>

        <div class="row" id="generate" style="display: none;">
            <div class="col-md-12 text-white bg-dark mt-1 text-center pb-2 pt-2">
                <h2>Невозможно сгенерировать отчеты. Пройдите обработку логов:</h2>
                <a href="handler">Обработка логов</a>
            </div>
        </div>

        <div class="row" id="report">
            <div class="col-md-6 tile p-1">
                <div class="inTile p-2 bg-dark text-white">
                    <h4 class="text-center">Посетители из какой страны совершают больше всего действий на сайте?</h4>

                    <ul class="list-group" id="topOfCountries"></ul>

                </div>
            </div>
            <div class="col-md-6 tile p-1">
                <div class="inTile p-2 bg-dark text-white">
                    <h4 class="text-center">Посетители из какой страны чаще всего интересуются товарами из определенных категорий?</h4>
                        <select class="form-control" id="selectCat">
                            <option>Выберите категорию</option>
                        </select>

                        <ul class="list-group mt-1" id="topOfCategories"></ul>
                </div>
            </div>
            <div class="col-md-12 tile p-1">
                <div class="inTile p-2 bg-dark text-white">
                    <h4 class="text-center">Какая нагрузка (число запросов) на сайт за астрономический час?</h4>
                    <h2 id="load" class="big text-center">0</h2>
                    <p class="text-center">Средняя нагрузка в час</p>
                </div>
            </div>
        </div>

    </div>

    <script src="libs/js/bootstrap.min.js"></script>
    <script src="libs/js/jq.js"></script>
    <script src="media/js/script.js"></script>

</body>
</html>