<?php

function executeQuery($query, $values) {
    $credentials = "mysql:host=localhost;dbname=webproject;charset=utf8";
    $user = "root";
    $password = "";
    $conn = new PDO($credentials, $user, $password);
    $stmt = $conn->prepare($query);
    return $stmt->execute($values);
}

function selectQuery($query, $values) {
    $credentials = "mysql:host=localhost;dbname=webproject;charset=utf8";
    $user = "root";
    $password = "";
    $conn = new PDO($credentials, $user, $password);
    $stmt = $conn->prepare($query);
    if($stmt->execute($values)) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else return false;
}

?>