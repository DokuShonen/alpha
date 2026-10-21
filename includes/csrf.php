<?php
/**
 * Protection contre les requetes falsifiees (CSRF).
 *
 * Un jeton aleatoire est stocke en session. Il est insere dans chaque
 * formulaire qui modifie des donnees, puis verifie cote serveur avant
 * tout traitement.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Retourne le jeton de la session, en le creant au besoin.
 */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Champ cache pret a inserer dans un formulaire.
 */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="'
        . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8')
        . '">';
}

/**
 * Indique si le jeton fourni correspond a celui de la session.
 */
function csrf_is_valid($token): bool
{
    return is_string($token)
        && $token !== ''
        && !empty($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Exige un jeton valide sur la requete courante.
 *
 * En cas d'echec, la requete est refusee avec le code 403 : l'action
 * demandee n'est pas executee.
 */
function csrf_require_valid(): void
{
    if (!csrf_is_valid($_POST['csrf_token'] ?? null)) {
        http_response_code(403);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'ok' => false,
            'error' => 'Session expiree. Merci de recharger la page et de reessayer.',
        ]);
        exit;
    }
}
