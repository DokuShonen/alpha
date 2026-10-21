<?php
/**
 * En-tete commun a toutes les pages.
 */
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/csrf.php';

$page_courante = basename($_SERVER['PHP_SELF'] ?? '');

$liens = [
    'index.php' => 'Accueil',
    'blog.php' => 'A propos',
    'service.php' => 'Services',
    'formulaire.php' => 'Budget',
    'contact.php' => 'Contact',
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_courante === 'index.php' ? 'ALPHA' : ucfirst(str_replace('.php', '', $page_courante)) . ' | ALPHA', ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="description" content="ALPHA - Intelligence artificielle et objets connectes : analyses, demonstrations et budget des projets du laboratoire.">
    <meta name="theme-color" content="#070a0d">
    <meta name="csrf-token" content="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="base-url" content="<?php echo htmlspecialchars(BASE_URL, ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="icon" href="<?php echo BASE_URL; ?>assets/images/favicon.svg" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Manrope:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body>
    <div class="grain" aria-hidden="true"></div>

    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="<?php echo BASE_URL; ?>index.php" aria-label="ALPHA, accueil">
                <span class="brand-mark" aria-hidden="true">A</span>
                <span class="brand-text">
                    <span class="brand-name">ALPHA</span>
                    <span class="brand-tag">Intelligence artificielle &amp; IoT</span>
                </span>
            </a>

            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="menu-principal" aria-label="Ouvrir le menu">
                <span class="menu-bar" aria-hidden="true"></span>
                <span class="menu-bar" aria-hidden="true"></span>
            </button>

            <nav class="site-nav" id="menu-principal" aria-label="Navigation principale">
                <ul>
                    <?php foreach ($liens as $fichier => $libelle): ?>
                        <li>
                            <a href="<?php echo BASE_URL . $fichier; ?>"<?php echo $page_courante === $fichier ? ' class="active" aria-current="page"' : ''; ?>>
                                <?php echo htmlspecialchars($libelle, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main class="site-main">
