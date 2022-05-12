<?php

require_once 'Models/itemsModel.php';

class itemsController
{

    //получение всех предметов
    public function GetAllItems($conn)
    {
        $result = itemsModel::GetAllItems($conn);
        return $result;
    }

    //получение одного предмета по id
    public function GetOneItem($conn, $idItem)
    {
        $result = itemsModel::GetOneItem($conn, $idItem);
        return $result;
    }

    //добавление предмета
    public function SendItem($conn, $Item_Name, $Item_Desk, $Item_Pict, $Item_Price)
    {
        $result = itemsModel::SendItem($conn, $Item_Name, $Item_Desk, $Item_Pict, $Item_Price);
        return $result;
    }

    //редактирование предмета
    public function UpdateItem($conn, $Item_Name, $Item_Desk, $Item_Pict, $Item_Price, $id_Item)
    {
        $result = itemsModel::UpdateItem($conn, $Item_Name, $Item_Desk, $Item_Pict, $Item_Price, $id_Item);
        return $result;
    }

    //удаление предмета
    public function DeleteItem($conn, $id)
    {
        $result = itemsModel::DeleteItem($conn, $id);
        return $result;
    }
    //проверка токена на правильность
    public function checkToken($conn, $postToken)
    {
        $result = orderModel::getAllUser($conn);

        if ($result['token'] == $postToken) {
            return true;
        } else {
            return false;
        }
    }
}
