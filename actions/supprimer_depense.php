<?php
require_once '../includes/connexion_db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM depenses WHERE id = :id";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Important : spécifier le type de données et se protéger contre les injections SQL
        $stmt->execute();

        http_response_code(200); // OK
        exit();
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression de la dépense: " . $e->getMessage(); // Utiliser $e->getMessage() pour obtenir le message d'erreur PDO
        http_response_code(500); // Internal Server Error
    }
} else {
    http_response_code(400); // Bad Request
    echo "ID de dépense non spécifié.";
}

$conn = null; // Fermer la connexion PDO
