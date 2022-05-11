<?php

//обьявления константы с адресом сайта "/" - после конца обязательно
define("SITE_ADDR", "http://localhost/");

//подключение к бд файлу
require_once 'db/dbconnect.php';

class Route {

    //метод для получения массива с url путями
    private function getUri() {
        $rawUri = $_SERVER['REQUEST_URI'];
        $arrUri = explode("/", $rawUri);
        array_shift($arrUri);
        return $arrUri;
    }

    public function start() {
        //установка ответа в json-е
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        //получаем коннект с БД
        $conn = GetConnect();
        //получение массива с юрл
        $uriArr = $this->getUri();

        //коннектим контроллеры
        require_once 'Controllers/itemsController.php';
        require_once 'Controllers/OrderController.php';
        //создаем экземпляры
        $itemController = new itemsController();
        $orderController = new OrderController();

        //начало самого роутинга
        if ($uriArr[0] == "items") {
            if (isset($uriArr[1])) {
                $subDomen = $uriArr[1];
                $subDomen = explode("?", $subDomen);
            }

            //создание нового элемента, типо из меню которая
            if (isset($uriArr[1]) && $subDomen[0] == "create") {
                //проверка на существование переменных переданных POST-запросом
                if (isset($_POST['name']) && isset($_POST['desk']) && isset($_POST['price'])) {
                    $itemController->SendItem($conn, $_POST['name'], $_POST['desk'], $_POST['price']);
                    $_POST = array();
                    http_response_code(201);
                    echo 'Created';
                } else {
                    http_response_code(502);
                }
            }
            //изменение элемента меню
            else if (isset($uriArr[1]) && $subDomen[0] == "update") {
                //проверка на существование переменных переданных POST-запросом
                if (isset($_POST['name']) && isset($_POST['desk']) && isset($_POST['price']) && isset($uriArr[2]) && $uriArr[2] != null) {

                    $itemArr = $itemController->GetAllItems($conn);
                    foreach ($itemArr as $item) {
                        if ($item['Item_Id'] == $uriArr[2]) {
                            $resultUpdate = $itemController->UpdateItem($conn, $_POST['name'], $_POST['desk'], $_POST['price'], $uriArr[2]);
                            http_response_code(202);

                            break;
                        } else {
                            http_response_code(404);
                        }
                    }
                } else {
                    if (isset($uriArr[2]) && $uriArr[2] != null) {
                        $itemArr = $itemController->GetAllItems($conn);
                        foreach ($itemArr as $item) {
                            if ($item['Item_Id'] == $uriArr[2]) {
                                $items = $itemController->GetOneItem($conn, $uriArr[2]);
                                http_response_code(202);
                            } else {
                                http_response_code(404);
                            }
                        }
                    } else {
                        http_response_code(404);
                        echo 'Not Found';
                    }
                }
            }
            //удаление элемента
            else if (isset($uriArr[1]) && $subDomen[0] == "delete") {

                if (isset($uriArr[2]) && $uriArr[2] != null) {
                    try {
                        $itemArr = $itemController->GetAllItems($conn);
                        foreach ($itemArr as $item) {
                            if ($item['Item_Id'] == $uriArr[2]) {
                                $items = $itemController->DeleteItem($conn, $uriArr[2]);
                                http_response_code(202);
                                break;
                            } else {
                                http_response_code(404);
                            }
                        }
                    } catch (Exception $exc) {
                        http_response_code(400);
                        echo 'Items used';
                    }
                } else {
                    http_response_code(404);
                }
            } else {

                $allItems = $itemController->GetAllItems($conn);

                //Код ответа
                $code = 200;
                //если пытаются выбрать 1 элемент
                if (isset($uriArr[1]) && $uriArr[1] != null) {

                    foreach ($allItems as $item) {
                        if ($item['Item_Id'] == $uriArr[1]) {
                            echo $this->ArrToJson($item);
                            $code = 200;
                            break;
                        } else {
                            $code = 404;
                        }
                    }
                    http_response_code($code);
                } else {
                    echo $this->ArrToJson($allItems);
                }
            }
        }
        //Работа с заказами
        else if ($uriArr[0] == "order") {
            //создание заказа
            if (isset($uriArr[1]) && $uriArr[1] == "create") {
                //проверка на существование переменных переданных POST-запросом
                if (isset($_POST['FIO']) && isset($_POST['number']) && isset($_POST['email']) && isset($_POST['address']) && isset($_POST['purchases']) && isset($_POST['comment'])) {
                    try {
                        $orderController->createOrder($conn, $_POST['FIO'], $_POST['number'], $_POST['email'], $_POST['address'], $_POST['purchases'], $_POST['comment']);
                        http_response_code(201);
                    } catch (Exception $exc) {
                        http_response_code(502);
                    }
                } else {
                    http_response_code(502);
                }
            }
            //выполнен заказ
            else if (isset($uriArr[1]) && $uriArr[1] == "done") {

                if (isset($uriArr[2])) {
                    $result = $orderController->doneOrder($conn, $uriArr[2]);
                    $allOrders = $orderController->listOrder($conn);
                    //проверка на соответсвие id
                    foreach ($allOrders as $order) {
                        if ($order['Order_id'] == $uriArr[2]) {
                            //если найдено то заменяем 0 на 1 в таблице с id
                            $items = $orderController->doneOrder($conn, $uriArr[2]);
                            http_response_code(202);
                            break;
                        } else {
                            //если ненайдено, то отвечаем то не найдено
                            http_response_code(404);
                        }
                    }
                } else {
                    http_response_code(502);
                }
            }
            //удаление заказа
            else if (isset($uriArr[1]) && $uriArr[1] == "delete") {
                //проверка на содержания id
                if (isset($uriArr[2])) {
                    $allOrders = $orderController->listOrder($conn);
                    //проверка есть ли такой id в базе
                    foreach ($allOrders as $order) {
                        //если все совпадает то проходит и удаляет заказ
                        if ($order['Order_id'] == $uriArr[2]) {
                            $result = $orderController->deleteOrder($conn, $uriArr[2]);
                            http_response_code(202);
                            break;
                        }
                        //если нету, то возвращает 404
                        else {
                            http_response_code(404);
                        }
                    }
                } else {
                    http_response_code(404);
                    echo 'Not found';
                }
            } else {
                $orders = $orderController->listOrder($conn);





                //Код ответа
                $code = 200;
                //если пытаются выбрать 1 заказ
                if (isset($uriArr[1]) && $uriArr[1] != null) {

                    foreach ($orders as $ord) {
                        if ($ord['Order_id'] == $uriArr[1]) {
                            echo $this->ArrToJson($ord);
                            $code = 200;
                            break;
                        } else {
                            $code = 404;
                        }
                    }
                    http_response_code($code);
                } else {
                    echo $this->ArrToJson($orders);
                    ;
                }
            }
        } else {
            http_response_code(404);
            echo 'Not found';
        }
    }

    public function ArrToJson($resultAr) {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        return json_encode($resultAr);
    }

}
