<?php
/**
 * Point d'entree de la connexion a la base.
 *
 * Ce fichier ne connecte rien : il expose une fonction. La connexion est
 * etablie a la demande, pour qu'une page sans base reste accessible meme
 * si le serveur MySQL est indisponible.
 */
require_once __DIR__ . '/../config/db.php';

/**
 * Connexion a la base, etablie au premier appel.
 */
function db(): PDO
{
    return getPDO();
}

/**
 * Verifie que la table existe et renvoie son identifiant.
 */
function tableExiste(string $table): bool
{
    try {
        $stmt = db()->prepare('SHOW TABLES LIKE :table');
        $stmt->execute(['table' => $table]);
        return $stmt->fetchColumn() !== false;
    } catch (PDOException $e) {
        return false;
    }
}
