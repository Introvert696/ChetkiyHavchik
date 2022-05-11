<?php

require_once 'Models/orderModel.php';

class OrderController {

    //получение списка заказа
    public function listOrder($conn) {
        $result = orderModel::listOrder($conn);
        return $result;
    }

    //создание заказа
    public function createOrder($conn, $FIO, $number, $email, $address, $purchases, $comment) {
        $result = orderModel::createOrder($conn, $FIO, $number, $email, $address, $purchases, $comment);
        return $result;
    }

    //выполнение заказа
    public function doneOrder($conn, $id) {
        $result = orderModel::doneOrder($conn, $id);
        return $result;
    }

    //удаление заказа
    public function deleteOrder($conn, $id) {
        $result = orderModel::deleteOrder($conn, $id);
        return $result;
    }

}
