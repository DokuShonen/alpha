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
 * Liste des depenses, au format JSON.
 */
require_once $app_dir . '/config/db.php';
require_once $app_dir . '/includes/connexion_db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $stmt = db()->query('SELECT id, designation, quantite, prix_unitaire, montant
                         FROM depenses ORDER BY id DESC');
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    error_log('ALPHA - lecture des depenses : ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([]);
}
