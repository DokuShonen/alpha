<?php
require_once '../includes/connexion_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $designation = $_POST["designation"];
    $quantite = $_POST["quantite"];
    $prix_unitaire = $_POST["prix_unitaire"];
    $montant = $_POST["montant"]; // Récupérer le montant du formulaire

    $sql = "UPDATE depenses SET designation = :designation, quantite = :quantite, prix_unitaire = :prix_unitaire, montant = :montant WHERE id = :id";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':designation', $designation, PDO::PARAM_STR);
        $stmt->bindParam(':quantite', $quantite, PDO::PARAM_INT);
        $stmt->bindParam(':prix_unitaire', $prix_unitaire, PDO::PARAM_STR); // ou PDO::PARAM_INT
        $stmt->bindParam(':montant', $montant, PDO::PARAM_STR); // ou PDO::PARAM_INT
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: formulaire.php"); // Rediriger vers la liste des dépenses
        exit();
    } catch (PDOException $e) {
        echo "Erreur lors de la mise à jour de la dépense: " . $e->getMessage();
        http_response_code(500);
    }
} else {
    http_response_code(400);
    echo "Méthode incorrecte.";
}

$conn = null;
