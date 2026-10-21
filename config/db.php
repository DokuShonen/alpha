<?php
/**
 * ALPHA - configuration et connexion a la base de donnees.
 *
 * Trois niveaux de configuration, du moins prioritaire au plus prioritaire :
 *   1. les valeurs par defaut ci-dessous ;
 *   2. le fichier config/db.local.php, non versionne ;
 *   3. les variables d'environnement ALPHA_DB_*.
 *
 * La connexion n'est etablie qu'a la demande : une page qui n'utilise pas
 * la base continue de s'afficher meme si MySQL est indisponible.
 */

/*
 * URL de base du site.
 *
 * BASE_URL est un prefixe de chemin, termine par un slash. Le dossier
 * public/ se trouve a la racine du projet : son URL se deduit du chemin de
 * la requete, ce qui fonctionne aussi bien a la racine d'un domaine que
 * dans un sous-dossier tel que /alpha/.
 */
/*
 * Dossier de l'application sur le disque.
 *
 * Par defaut, le dossier parent de config/. Il peut etre impose par la
 * variable ALPHA_APP_DIR quand l'application est hebergee dans un
 * sous-dossier d'un autre site.
 */
if (!defined('APP_DIR')) {
    $dossier_app = getenv('ALPHA_APP_DIR');
    define('APP_DIR', $dossier_app !== false && $dossier_app !== '' ? rtrim($dossier_app, '/') : dirname(__DIR__));
}

if (!defined('BASE_URL')) {
    $base_path = '/';

    $public_dir = realpath(__DIR__ . '/../public');
    $script_file = isset($_SERVER['SCRIPT_FILENAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']) : '';
    $script_name = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '';

    if ($public_dir && $script_file !== '' && strpos($script_file, $public_dir) === 0) {
        $relatif = substr($script_file, strlen($public_dir));
        $position = strpos($script_name, $relatif);
        if ($relatif !== '' && $position !== false) {
            $prefixe = substr($script_name, 0, $position);
            $base_path = $prefixe === '' ? '/' : $prefixe;
        }
    }

    // Repli : le dossier public se trouve plus haut que la racine servie.
    if ($base_path === '/') {
        $doc_root = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;
        if ($doc_root && $public_dir && strpos($public_dir, $doc_root) === 0) {
            $relative = str_replace('\\', '/', substr($public_dir, strlen($doc_root)));
            $base_path = '/' . trim($relative, '/');
            $base_path = $base_path === '/' ? '/' : $base_path . '/';
        }
    }

    // Surcharge explicite, pour les installations atypiques.
    $surcharge = getenv('ALPHA_BASE_PATH');
    if ($surcharge !== false && $surcharge !== '') {
        $base_path = '/' . trim($surcharge, '/') . '/';
    }

    define('BASE_URL', $base_path);
}

/**
 * Configuration de la base, assemblee depuis les trois niveaux.
 */
function getDBConfig(): array
{
    static $config = null;

    if ($config !== null) {
        return $config;
    }

    $config = [
        'host' => 'localhost',
        'port' => 3306,
        'socket' => '',
        'user' => 'root',
        'pass' => '',
        'name' => 'projet',
    ];

    $local_file = __DIR__ . '/db.local.php';
    if (is_file($local_file)) {
        $local = require $local_file;
        if (is_array($local)) {
            $config = array_merge($config, $local);
        }
    }

    $env_vars = [
        'host' => 'ALPHA_DB_HOST',
        'port' => 'ALPHA_DB_PORT',
        'socket' => 'ALPHA_DB_SOCKET',
        'user' => 'ALPHA_DB_USER',
        'pass' => 'ALPHA_DB_PASS',
        'name' => 'ALPHA_DB_NAME',
    ];

    foreach ($env_vars as $key => $variable) {
        $value = getenv($variable);
        if ($value !== false && $value !== '') {
            $config[$key] = $value;
        }
    }

    $config['port'] = (int) $config['port'];

    return $config;
}

/**
 * Connexion PDO partagee par toute la requete.
 */
function getPDO(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $config = getDBConfig();

    if (!empty($config['socket'])) {
        $dsn = "mysql:unix_socket={$config['socket']};dbname={$config['name']};charset=utf8mb4";
    } else {
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['name']};charset=utf8mb4";
    }

    $pdo = new PDO($dsn, $config['user'], $config['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}
