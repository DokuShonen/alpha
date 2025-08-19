<?php
// Inclure la connexion a la base de donnees
require_once '../includes/connexion_db.php';

// Utiliser le header pour une belle presentation
require_once '../includes/header.php';

echo '<section class="contact-response">';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Recuperer et nettoyer les donnees
    $nom = strip_tags(trim($_POST["nom"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $sujet = strip_tags(trim($_POST["sujet"]));
    $message = strip_tags(trim($_POST["message"]));

    // 2. Validation simple
    if (empty($nom) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($sujet) || empty($message)) {
        echo '<h2>Erreur !</h2>';
        echo '<p>Veuillez remplir tous les champs correctement.</p>';
        echo '<a href="../contact.php" class="btn">Retour au formulaire</a>';
    } else {
        // 3. Inserer le message dans la base de donnees
        try {
            $sql = "INSERT INTO contact_messages (nom, email, sujet, message) VALUES (:nom, :email, :sujet, :message)";
            $stmt = $pdo->prepare($sql);

            // Lier les parametres
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':sujet', $sujet);
            $stmt->bindParam(':message', $message);

            // Executer la requete
            if ($stmt->execute()) {
                // 4. Afficher un message de succes
                echo '<h2>Message Enregistré !</h2>';
                echo '<p>Merci, ' . htmlspecialchars($nom) . '. Votre message a bien été sauvegardé dans notre base de données.</p>';
                echo '<a href="../index.php" class="btn">Retour à l\'accueil</a>';
            } else {
                echo '<h2>Erreur !</h2>';
                echo '<p>Une erreur est survenue lors de l\'enregistrement de votre message.</p>';
                echo '<a href="../contact.php" class="btn">Retour au formulaire</a>';
            }
        } catch (PDOException $e) {
            // Gerer les erreurs de base de donnees
            // En production, ne pas afficher les details de l\'erreur a l\'utilisateur
            // error_log("Erreur d'insertion : " . $e->getMessage());
            echo '<h2>Erreur !</h2>';
            echo '<p>Impossible de se connecter a la base de donnees pour enregistrer le message.</p>';
            echo '<a href="../contact.php" class="btn">Retour au formulaire</a>';
        }
    }
} else {
    // Rediriger si quelqu'un essaie d'acceder au script directement
    header("Location: ../contact.php");
    exit;
}

echo '</section>';

// Utiliser le footer
require_once '../includes/footer.php';
?>