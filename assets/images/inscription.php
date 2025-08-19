<?php
session_start();

require_once 'fonctions.php';

$message_erreur = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = escape($_POST['nom']);
    $prenom = escape($_POST['prenom']);
    $date_naissance = escape($_POST['date_naissance']);
    $pays = escape($_POST['pays']);
    $email = escape($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    if (empty($nom) || empty($prenom) || empty($email) || empty($mot_de_passe)) {
        $message_erreur = "Veuillez remplir tous les champs obligatoires.";
    }

    if ($message_erreur == "") {
        $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_DEFAULT);

        $bdd = db_connect();
        $requete = $bdd->prepare("INSERT INTO projet (nom, prenom, date_naissance, pays, email, mot_de_passe) VALUES (?, ?, ?, ?, ?, ?)");
        $requete->execute([$nom, $prenom, $date_naissance, $pays, $email, $mot_de_passe_hache]);

        header("Location: index.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    <link rel="stylesheet" href="inscription.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include("header.php"); ?>

    <div id="content">
        <h2>Inscription</h2>

        <?php if ($message_erreur != ""): ?>
            <p style="color: red;"><?php echo $message_erreur; ?></p>
        <?php endif; ?>

        <form method="post">
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" required><br>

            <label for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="prenom" required><br>

            <label for="date_naissance">Date de naissance:</label>
            <input type="date" id="date_naissance" name="date_naissance"><br>

            <label for="pays">Pays:</label>
            <input type="text" id="pays" name="pays"><br>

            <label for="email">Adresse-mail:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="mot_de_passe">Mot de passe:</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required><br>

            <button type="submit">S'inscrire</button>
        </form>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>