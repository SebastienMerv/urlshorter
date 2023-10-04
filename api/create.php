<?php
// Affichage des erreurs
ini_set('display_errors', 1);
require_once('../config.php');
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

$url = $_POST['url']; // Utilise simplement $_POST['url'] sans intval()

$short_url = createCode($url, $conn);

$json = array(
    'short_url' => $short_url
);
echo json_encode($json); // Utilise echo pour renvoyer la réponse JSON

function createCode($url, $conn)
{
    // Vérifier si l'url est une url valide
    if (filter_var($url, FILTER_VALIDATE_URL) == false) {
        return "url";
    }
    // Génération d'un code aléatoire
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $code = '';
    for ($i = 0; $i < 5; $i++) {
        $code .= $characters[rand(0, $charactersLength - 1)];
    }
    try {
        $sql = "SELECT * FROM codes WHERE code = :code";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':code', $code);
        $stmt->execute();

        $result = $stmt->rowCount(); // Utilise rowCount() pour obtenir le nombre de résultats
    } catch (PDOException $e) {
        echo $e->getMessage();
        die;
    }
    if ($result < 1) {
        try {
            $date = date("Y-m-d H:i:s");
            $url = htmlspecialchars($url);
            $sql = "INSERT INTO codes (code, url, CREATED_AT) VALUES (:code, :url, :data)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':url', $url);
            $stmt->bindParam(':data', $date);
            $stmt->execute();
            $short_url = $code;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die;
        }
    } else {
        $short_url = "error";
    }

    return $short_url;
}
