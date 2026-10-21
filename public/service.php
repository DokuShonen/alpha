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
 * Page des services.
 */
require_once $app_dir . '/config/db.php';
require_once $app_dir . '/includes/header.php';
?>

<section class="page-section">
    <p class="eyebrow">Services</p>
    <h1 class="page-title">Trois domaines, une meme exigence</h1>
    <p class="lede">
        Nos prestations sont construites sur des projets deja menes en laboratoire :
        elles sont documentees, chiffrees et reproductibles.
    </p>

    <div class="service-grid">
        <article class="service-card">
            <span class="service-index">01</span>
            <h3>Analyse de donnees</h3>
            <p>Notre IA analyse vos donnees et fournit des insights precieux pour optimiser vos decisions.</p>
        </article>
        <article class="service-card">
            <span class="service-index">02</span>
            <h3>Automatisation</h3>
            <p>Automatisez les taches repetitives et gagnez du temps grace a nos algorithmes intelligents.</p>
        </article>
        <article class="service-card">
            <span class="service-index">03</span>
            <h3>Assistance virtuelle</h3>
            <p>Un assistant intelligent capable de repondre a vos besoins et d'interagir naturellement.</p>
        </article>
    </div>

    <h2 class="section-title">Notre approche</h2>
    <div class="prose">
        <p>
            Chaque mission commence par un cadrage technique : identifier la
            donnee disponible, la decision a soutenir et le critere de succes.
            Nous prototypons ensuite sur des jeux de donnees reelles avant tout
            passage a l'echelle.
        </p>
        <p>
            Les livrables sont documentes dans le projet, et les depenses
            correspondantes sont declarees dans le budget du site.
        </p>
    </div>
</section>

<?php require_once $app_dir . '/includes/footer.php'; ?>
