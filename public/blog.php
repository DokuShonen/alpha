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
 * Page a propos.
 */
require_once $app_dir . '/config/db.php';
require_once $app_dir . '/includes/header.php';
?>

<section class="page-section">
    <p class="eyebrow">A propos</p>
    <h1 class="page-title">Un laboratoire d'etudiants, ouvert sur l'industriel</h1>

    <div class="prose">
        <p>
            <strong>ALPHA</strong> est un espace de recherche appliquee constitue
            d'etudiants de l'Universite Joseph Ki-Zerbo. L'objectif est de rendre
            l'intelligence artificielle accessible : loin des concepts
            abstraits, chaque sujet est traite par une experience, un code ou une
            mesure.
        </p>
        <p>
            Nous developpons et experimentons des algorithmes en PHP et dans
            d'autres langages pour proposer des solutions innovantes, du
            traitement du langage naturel a la vision par ordinateur.
        </p>

        <p>Sur ce site, vous trouverez :</p>
        <ul>
            <li><strong>Des demonstrations interactives</strong> pour comprendre le fonctionnement des algorithmes utilises</li>
            <li><strong>Des tutoriels et analyses</strong> qui expliquent les concepts cles et leurs applications concretes</li>
            <li><strong>Un espace de contact</strong> pour echanger sur les projets, les methodes et les innovations</li>
            <li><strong>Un budget ouvert</strong> qui retrace les depenses de materiel et de donnees de chaque experience</li>
        </ul>

        <p>
            Notre ambition est de creer un espace d'apprentissage et
            d'experimentation ou l'intelligence artificielle devient une
            technologie a portee de main.
        </p>
        <p>
            N'hesitez pas a explorer le site, et a nous contacter pour toute
            question de collaboration.
        </p>
    </div>
</section>

<?php require_once $app_dir . '/includes/footer.php'; ?>
