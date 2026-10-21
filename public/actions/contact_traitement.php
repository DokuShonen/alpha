<?php
// Dossier de l'application.
//
// ALPHA_APP_DIR permet d'heberger l'application dans un sous-dossier d'un
// autre site. Sans cette variable, le dossier qui contient config/db.php
// est recherche en remontant depuis le fichier courant.
$app_dir = getenv('ALPHA_APP_DIR');
if ($app_dir === false || $app_dir === '') {
    $app_dir = __DIR__;
    while ($app_dir !== dirname($app_dir) && !is_file($app_dir . '/config/db.php')) {
        $app_dir = dirname($app_dir);
    }
} else {
    $app_dir = rtrim($app_dir, '/');
}

/**
 * Enregistrement d'un message de contact.
 */
require_once $app_dir . '/config/db.php';
require_once $app_dir . '/includes/connexion_db.php';
require_once $app_dir . '/includes/csrf.php';

$erreur = null;
$succes = false;
$nom = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_is_valid($_POST['csrf_token'] ?? null)) {
        http_response_code(403);
        $erreur = 'Votre session a expire. Merci de reessayer.';
    } else {
        $nom = trim((string) ($_POST['nom'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $sujet = trim((string) ($_POST['sujet'] ?? ''));
        $message = trim((string) ($_POST['message'] ?? ''));

        if ($nom === '' || mb_strlen($nom) > 255) {
            $erreur = 'Merci d indiquez votre nom.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur = 'Cette adresse email n est pas valide.';
        } elseif ($sujet === '' || mb_strlen($sujet) > 255) {
            $erreur = 'Merci d indiquez un sujet.';
        } elseif ($message === '' || mb_strlen($message) > 5000) {
            $erreur = 'Votre message est vide ou trop long.';
        } else {
            try {
                $sql = 'INSERT INTO contact_messages (nom, email, sujet, message)
                        VALUES (:nom, :email, :sujet, :message)';

                $stmt = db()->prepare($sql);
                $stmt->execute([
                    ':nom' => $nom,
                    ':email' => $email,
                    ':sujet' => $sujet,
                    ':message' => $message,
                ]);

                $succes = true;
                $nom = '';
            } catch (PDOException $e) {
                error_log('ALPHA - enregistrement du message : ' . $e->getMessage());
                $erreur = "Le message n'a pas pu etre enregistre. Reessayez dans un instant.";
            }
        }
    }
}

require_once $app_dir . '/includes/header.php';
?>

<section class="page-section">
    <p class="eyebrow">Contact</p>
    <h1 class="page-title">Parlons de votre projet</h1>

    <?php if ($succes): ?>
        <p class="alert alert-success">Merci, votre message a bien ete enregistre. Nous y repondons dans les meilleurs delais.</p>
        <a href="<?php echo BASE_URL; ?>index.php" class="btn btn-ghost">Retour a l accueil</a>
    <?php else: ?>
        <?php if ($erreur !== null): ?>
            <p class="alert alert-error"><?php echo htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <form class="card-form" method="post" action="<?php echo BASE_URL; ?>actions/contact_traitement.php">
            <?php echo csrf_field(); ?>

            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Votre nom</label>
                    <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom, ENT_QUOTES, 'UTF-8'); ?>" required maxlength="255">
                </div>

                <div class="form-group">
                    <label for="email">Votre email</label>
                    <input type="email" id="email" name="email" required maxlength="255">
                </div>
            </div>

            <div class="form-group">
                <label for="sujet">Sujet</label>
                <input type="text" id="sujet" name="sujet" required maxlength="255">
            </div>

            <div class="form-group">
                <label for="message">Votre message</label>
                <textarea id="message" name="message" rows="7" required maxlength="5000"></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Envoyer le message</button>
            </div>
        </form>
    <?php endif; ?>
</section>

<?php require_once $app_dir . '/includes/footer.php'; ?>
