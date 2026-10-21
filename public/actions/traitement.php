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
 * Creation d'une depense.
 *
 * Le montant n'est pas lu dans le formulaire : il est recalcule par le
 * serveur a partir de la quantite et du prix unitaire, pour qu'un client
 * ne puisse pas declarer une somme arbitraire.
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

$designation = trim((string) ($_POST['designation'] ?? ''));
$quantite = (int) ($_POST['quantite'] ?? 0);
$prix_unitaire = (float) str_replace(',', '.', (string) ($_POST['prix_unitaire'] ?? 0));

if ($designation === '' || mb_strlen($designation) > 255) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'La designation est obligatoire.']);
    exit;
}

if ($quantite <= 0 || $quantite > 1000000) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'La quantite doit etre un entier positif.']);
    exit;
}

if ($prix_unitaire <= 0 || $prix_unitaire > 100000000) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Le prix unitaire doit etre un nombre positif.']);
    exit;
}

$montant = round($quantite * $prix_unitaire, 2);

try {
    $sql = 'INSERT INTO depenses (designation, quantite, prix_unitaire, montant)
            VALUES (:designation, :quantite, :prix_unitaire, :montant)';

    $stmt = db()->prepare($sql);
    $stmt->execute([
        ':designation' => $designation,
        ':quantite' => $quantite,
        ':prix_unitaire' => $prix_unitaire,
        ':montant' => $montant,
    ]);

    http_response_code(201);
    echo json_encode([
        'ok' => true,
        'id' => (int) db()->lastInsertId(),
        'depense' => [
            'id' => (int) db()->lastInsertId(),
            'designation' => $designation,
            'quantite' => $quantite,
            'prix_unitaire' => $prix_unitaire,
            'montant' => $montant,
        ],
    ]);
} catch (PDOException $e) {
    error_log('ALPHA - creation de depense : ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => "L'enregistrement a echoue. Reessayez dans un instant."]);
}
