<?php
require_once '../includes/connexion_db.php';

$sql = "SELECT * FROM depenses";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $depenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($depenses);
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage(); // Gérer les erreurs PDO
    http_response_code(500); // Envoyer un code d'erreur
}

$conn = null; // Fermer la connexion PDO
