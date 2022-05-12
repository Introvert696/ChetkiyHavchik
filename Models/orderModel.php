<?php

class orderModel
{

    //модель списка заказов
    public static function listOrder($conn)
    {
        $stmt = $conn->prepare("SELECT * FROM orders");
        $stmt->execute();
        // set the resulting array to associative
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    //модель создания заказа
    public static function createOrder($conn, $FIO, $number, $email, $address, $purchases, $price, $comment)
    {

        $stmt = $conn->prepare("INSERT INTO `orders`( `Order_FIO`, `Order_Number`, `Order_Email`, `Order_Address`, `Order_Purchases`,`Order_Price` , `Order_Comment`) VALUES ('$FIO','$number','$email','$address','$purchases','$price','$comment')");
        $stmt->execute();
        // set the resulting array to associative
        //$result = $stmt->fetch(PDO::FETCH_ASSOC);
        //return $result;
    }

    //модель выполнения заказа
    public static function doneOrder($conn, $id)
    {
        $stmt = $conn->prepare("UPDATE `orders` SET `Order_State`='1' WHERE Order_id = $id");
        $stmt->execute();
        // set the resulting array to associative
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    //модель удаления заказа
    public static function deleteOrder($conn, $id)
    {
        $stmt = $conn->prepare("DELETE FROM `orders` WHERE Order_id = " . $id);
        $stmt->execute();
        // set the resulting array to associative
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    //модель получения токена
    public static function getAllUser($conn)
    {
        $stmt = $conn->prepare("Select * from users");
        $stmt->execute();
        // set the resulting array to associative
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}
