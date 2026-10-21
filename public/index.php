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
 * Page d'accueil.
 */
require_once $app_dir . '/config/db.php';
require_once $app_dir . '/includes/header.php';
?>

<section class="hero">
    <div class="container">
        <p class="hero-prompt">
            <span>ulrich@alpha:~$</span>
            <span class="cursor" aria-hidden="true"></span>
        </p>
        <h1>L'intelligence artificielle, <em>comprise</em> et appliquee.</h1>
        <p class="lede">
            ALPHA est un laboratoire applique : nous tudions l'IA et les objets
            connectes, nous confrontons les algorithmes a des cas concrets, et nous
            documentons le budget de chaque experience.
        </p>
        <div class="hero-actions">
            <a href="<?php echo BASE_URL; ?>service.php" class="btn">Nos services</a>
            <a href="<?php echo BASE_URL; ?>blog.php" class="link-texte">Le projet</a>
        </div>

        <div class="image-bubbles">
            <article class="bubble">
                <img src="<?php echo BASE_URL; ?>assets/images/IA.png" alt="Representation de l'intelligence artificielle">
                <h3>Analyse de donnees</h3>
                <p>Extraire des signaux utiles de volumes de donnees heterogenes.</p>
            </article>
            <article class="bubble">
                <img src="<?php echo BASE_URL; ?>assets/images/IoT.jpg" alt="Objets connectes et capteurs">
                <h3>Objets connectes</h3>
                <p>Relier le monde physique a un systeme capable de reagir.</p>
            </article>
            <article class="bubble">
                <img src="<?php echo BASE_URL; ?>assets/images/bigdata.jpg" alt="Infrastructure de calcul pour le big data">
                <h3>Big data</h3>
                <p>Outiller la decision avec des traitements a l'echelle.</p>
            </article>
        </div>
    </div>
</section>

<section class="page-section">
    <p class="eyebrow">Notre methode</p>
    <h2 class="section-title">De la theorie a la mesure</h2>
    <div class="prose">
        <p>
            Chaque projet du laboratoire part d'un besoin reel, passe par un
            prototype, et se termine par une evaluation chiffree. Les depenses
            de materiel sont declarees et suivies dans le budget du site : rien
            n'est achete sans trace.
        </p>
        <p>
            L'equipe est ouverte a la collaboration : les messages envoyes par le
            formulaire de contact sont enregistres et traites par les membres.
        </p>
    </div>

    <p style="margin-top:34px;">
        <a href="<?php echo BASE_URL; ?>formulaire.php" class="btn btn-ghost">Consulter le budget</a>
    </p>
</section>

<?php require_once $app_dir . '/includes/footer.php'; ?>
