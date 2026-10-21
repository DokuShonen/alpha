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
 * Page de contact.
 */
require_once $app_dir . '/config/db.php';
require_once $app_dir . '/includes/header.php';
?>

<section class="page-section">
    <p class="eyebrow">Contact</p>
    <h1 class="page-title">Parlons de votre projet</h1>
    <p class="lede">
        Une question, une suggestion ou une proposition de collaboration ?
        Laissez un message, il sera enregistre et traite par l'equipe.
    </p>

    <form class="card-form" method="post" action="<?php echo BASE_URL; ?>actions/contact_traitement.php">
        <?php echo csrf_field(); ?>

        <div class="form-row">
            <div class="form-group">
                <label for="nom">Votre nom</label>
                <input type="text" id="nom" name="nom" required maxlength="255">
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
</section>

<?php require_once $app_dir . '/includes/footer.php'; ?>
