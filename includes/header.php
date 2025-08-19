<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALPHA Project</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <nav class="header">
            <img src="<?php echo BASE_URL; ?>assets/images/globe1.png">
            <h1>ALPHA</h1>
        </nav>
    </header>
    <button class="menu-toggle" onclick="toggleMenu()">☰ Menu</button>
    <div id="dropdown-menu" class="dropdown-menu">
        <ul>
            <li><a href="<?php echo BASE_URL; ?>index.php">Accueil</a></li>
            <li><a href="<?php echo BASE_URL; ?>service.php">Services</a></li>
            <li><a href="<?php echo BASE_URL; ?>formulaire.php">Formulaire</a></li>
            <li><a href="<?php echo BASE_URL; ?>blog.php">Blog</a></li>
            <li><a href="<?php echo BASE_URL; ?>contact.php">Contact</a></li>
        </ul>
    </div>
    <main>