<?php

function GetConnect() {
    //переменные для настройки коннекта с бд
    $server = "localhost";
    $dbName = "chetkiyhavchik";
    $user = "root";
    $password = "";
    
    try {
        //создание коннекта с бд
        $conn = new PDO("mysql:host=$server;dbname=$dbName", $user, $password);
        //чета вроде отображения ошибок
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //если все норм возвращаем коннект
        $stmt = $conn->prepare("SET NAMES 'utf8'");
        $stmt->execute();
        return $conn;
    } catch (PDOException $e) {
        //если плоха, то возвращаем ошибочку
        echo "Connection failed: " . $e->getMessage();
        return null;
    }
}
