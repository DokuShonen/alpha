<?php
// Dynamic BASE_URL for project portability
if (!defined('BASE_URL')) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];

    // Get the path from the document root to the project root
    $document_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    $current_file_path = str_replace('\\', '/', __FILE__); // Path to connexion_db.php
    // Calculate project root path (e.g., C:/wamp64/www/Herberger/GROUPE1)
    // This assumes connexion_db.php is always in includes/
    $project_root_path = str_replace('/includes/connexion_db.php', '', $current_file_path);

    $base_url_path = '';
    if (strpos($project_root_path, $document_root) === 0) {
        $base_url_path = substr($project_root_path, strlen($document_root));
    }

    // Ensure it starts with a slash and ends with a slash
    if (empty($base_url_path)) {
        $base_url_path = '/';
    } elseif ($base_url_path[0] !== '/') {
        $base_url_path = '/' . $base_url_path;
    }
    if (substr($base_url_path, -1) !== '/') {
        $base_url_path .= '/';
    }

    define('BASE_URL', $base_url_path);
}


$servername = "localhost"; // Remplace par le nom de ton serveur MySQL
$username = "root"; // Remplace par ton nom d'utilisateur MySQL
$password = ""; // Remplace par ton mot de passe MySQL
$dbname = "projet"; // Remplace par le nom de ta base de données

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Définir le mode d'erreur PDO sur exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Désactiver l'émulation des requêtes préparées
    //echo "Connected successfully"; // Tu peux commenter cette ligne une fois que tu as vérifié la connexion
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die(); // Arrête l'exécution si la connexion échoue
}
