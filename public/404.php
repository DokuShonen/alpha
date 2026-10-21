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
 * Page 404.
 *
 * Le code de reponse est pose ici : l'URL demandee n'existe pas, la
 * reponse doit le dire, meme si la page s'affiche correctement.
 */
http_response_code(404);

require_once $app_dir . '/config/db.php';
require_once $app_dir . '/includes/header.php';
?>

<section class="page-section">
    <p class="eyebrow">Erreur 404</p>
    <h1 class="page-title">Cette page n'existe pas</h1>
    <p class="lede">
        Le lien que vous avez suivi ne correspond plus a une page du site. Les
        adresses ci-dessous restent valides.
    </p>
    <p style="margin-top:34px; display:flex; gap:14px; flex-wrap:wrap;">
        <a href="<?php echo BASE_URL; ?>index.php" class="btn">Retour a l'accueil</a>
        <a href="<?php echo BASE_URL; ?>service.php" class="btn btn-ghost">Nos services</a>
    </p>
</section>

<?php require_once $app_dir . '/includes/footer.php'; ?>
