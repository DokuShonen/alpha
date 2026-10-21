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
 * Suppression d'une depense.
 *
 * L'action est destructive : elle n'est acceptee qu'en POST, avec un jeton
 * anti-CSRF. Un simple lien ne peut plus declencher la suppression.
 */
require_once $app_dir . '/config/db.php';
require_once $app_dir . '/includes/connexion_db.php';
require_once $app_dir . '/includes/csrf.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Methode non autorisee.']);
    exit;
}

csrf_require_valid();

$id = (int) ($_POST['id'] ?? 0);
if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Depense introuvable.']);
    exit;
}

try {
    $stmt = db()->prepare('DELETE FROM depenses WHERE id = ?');
    $stmt->execute([$id]);

    echo json_encode(['ok' => true]);
} catch (PDOException $e) {
    error_log('ALPHA - suppression de depense : ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => "La suppression a echoue. Reessayez dans un instant."]);
}
