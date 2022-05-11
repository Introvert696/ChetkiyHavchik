<?php
require_once 'Controllers/router.php';

//запускаем роутер, и там все обрабатываем
$router = new Route();
$router->start();
//test