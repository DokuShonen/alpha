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
 * Modification d'une depense.
 *
 * La reponse s'adapte a l'appelant : JSON pour une requete JavaScript,
 * redirection pour un formulaire classique.
 */
require_once $app_dir . '/config/db.php';
require_once $app_dir . '/includes/connexion_db.php';
require_once $app_dir . '/includes/csrf.php';

$est_ajax = ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';

if ($est_ajax) {
    header('Content-Type: application/json; charset=utf-8');
}

$repondre = function (array $donnees, int $code) use ($est_ajax): void {
    if ($est_ajax) {
        http_response_code($code);
        echo json_encode($donnees);
        exit;
    }
    header('Location: ' . BASE_URL . 'formulaire.php');
    exit;
};

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $repondre(['ok' => false, 'error' => 'Methode non autorisee.'], 405);
}

if (!csrf_is_valid($_POST['csrf_token'] ?? null)) {
    $repondre(['ok' => false, 'error' => 'Session expiree. Merci de recharger la page.'], 403);
}

$id = (int) ($_POST['id'] ?? 0);
$designation = trim((string) ($_POST['designation'] ?? ''));
$quantite = (int) ($_POST['quantite'] ?? 0);
$prix_unitaire = (float) str_replace(',', '.', (string) ($_POST['prix_unitaire'] ?? 0));

if ($id <= 0 || $designation === '' || mb_strlen($designation) > 255 || $quantite <= 0 || $prix_unitaire <= 0) {
    $repondre(['ok' => false, 'error' => 'Les informations envoyees sont invalides.'], 400);
}

// Le montant est recalcule par le serveur : le formulaire ne fait pas foi.
$montant = round($quantite * $prix_unitaire, 2);

try {
    $sql = 'UPDATE depenses
            SET designation = :designation, quantite = :quantite,
                prix_unitaire = :prix_unitaire, montant = :montant
            WHERE id = :id';

    $stmt = db()->prepare($sql);
    $stmt->execute([
        ':designation' => $designation,
        ':quantite' => $quantite,
        ':prix_unitaire' => $prix_unitaire,
        ':montant' => $montant,
        ':id' => $id,
    ]);

    $repondre(['ok' => true], 200);
} catch (PDOException $e) {
    error_log('ALPHA - modification de depense : ' . $e->getMessage());
    $repondre(['ok' => false, 'error' => "La modification a echoue. Reessayez dans un instant."], 500);
}
