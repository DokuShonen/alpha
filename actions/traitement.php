<?php
require_once '../includes/connexion_db.php'; // Inclure le fichier de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $designation = $_POST["designation"];
    $quantite = $_POST["quantite"];
    $prix_unitaire = $_POST["prix_unitaire"];
    $montant = $_POST["montant"]; // Récupérer le montant envoyé par le formulaire

    $sql = "INSERT INTO depenses (designation, quantite, prix_unitaire, montant)
            VALUES (:designation, :quantite, :prix_unitaire, :montant)";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':designation', $designation, PDO::PARAM_STR);
        $stmt->bindParam(':quantite', $quantite, PDO::PARAM_INT);
        $stmt->bindParam(':prix_unitaire', $prix_unitaire, PDO::PARAM_STR); // ou PDO::PARAM_INT si c'est un entier
        $stmt->bindParam(':montant', $montant, PDO::PARAM_STR); // ou PDO::PARAM_INT si c'est un entier
        $stmt->execute();

        http_response_code(200);  // Envoyer un code de succès
        exit();
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage(); // Utiliser getMessage() pour obtenir le message d'erreur PDO
        http_response_code(500);  // Envoyer un code d'erreur interne du serveur
    }
} else {
    http_response_code(400);  // Envoyer un code de requête incorrecte si la méthode n'est pas POST
}

$conn = null; // Fermer la connexion PDO en mettant $conn à null
