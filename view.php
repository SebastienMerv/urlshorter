<?php

require_once('config.php');

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

$code = $_SERVER["REQUEST_URI"];

// On retire le / tout devant

$code = substr($code, 1);

// On vérifie si le code existe dans la db
try {
    $sql = "SELECT * FROM codes WHERE code = :code";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':code', $code);
    $stmt->execute();
    $data = $stmt->fetch();

    $result = $stmt->rowCount();
} catch (PDOException $e) {
    echo $e->getMessage();
    die;
}
if ($data == true) {
    $url = $data['url'];
    header("Location: $url");
} else {
    echo "Code non retrouvé...";
}
