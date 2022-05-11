<?php

class itemsModel {

    //модель получения всех предметов
    public static function GetAllItems($conn) {
        $stmt = $conn->prepare("SELECT * FROM items");
        $stmt->execute();
        // set the resulting array to associative
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    //модель получения одного предмета
    public static function GetOneItem($conn, $idItem) {
        $stmt = $conn->prepare("SELECT * FROM items where Item_Id = " . $idItem);
        $stmt->execute();
        // set the resulting array to associative
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result != "") {
            return $result;
        } else {
            return null;
        }
    }

    //модель добавления предмета
    public static function SendItem($conn, $Item_Name, $Item_Desk, $Item_Price) {
        $stmt = $conn->prepare("INSERT INTO `items`(`Item_Name`, `Item_Desk`, `Item_Price`) VALUES ('$Item_Name','$Item_Desk','$Item_Price')");
        $stmt->execute();
        // set the resulting array to associative
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    //модель редактирование предмета
    public static function UpdateItem($conn, $Item_Name, $Item_Desk, $Item_Price, $id_Item) {
        $stmt = $conn->prepare("UPDATE `items` SET `Item_Name`='$Item_Name',`Item_Desk`='$Item_Desk',`Item_Price`='$Item_Price' WHERE Item_Id = $id_Item");
        $stmt->execute();
        // set the resulting array to associative
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    
    //модель удаление предмета
    public static function DeleteItem($conn, $id) {
        $stmt = $conn->prepare("DELETE FROM `items` WHERE Item_Id = " . $id);
        $stmt->execute();
        // set the resulting array to associative
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

}
