<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Modifier Dépense</title>
  
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <header>
    <nav class="header">
      <img src="photo/globe1.png" alt="Logo">
      <h1>ALPHA</h1>
    </nav>
  </header>
  <button class="menu-toggle" onclick="toggleMenu()">☰ Menu</button>
  <div id="dropdown-menu" class="dropdown-menu">
    <ul>
      <li><a href="index.php">Accueil</a></li>
      <li><a href="service.php">Services</a></li>
      <li><a href="formulaire.php">Formulaire</a></li>
      <li><a href="blog.php">Blog</a></li>
      <li><a href="contact.php">Contact</a></li>
    </ul>
  </div>
  <script>
    function toggleMenu() {
      document.getElementById("dropdown-menu").classList.toggle("show");
    }
  </script>

  <main>
    <?php
    require_once '../includes/connexion_db.php';

    if (isset($_GET['id'])) {
      $id = $_GET['id'];

      $sql = "SELECT * FROM depenses WHERE id = :id";

      try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $depense = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($depense) {
    ?>
          <!-- Formulaire pour modifier les informations -->
          <form id="edit-depense-form" method="post" action="update_depense.php">
            <input type="hidden" id="id" name="id" value="<?php echo htmlspecialchars($depense['id']); ?>">

            <label for="designation">Désignation:</label>
            <input type="text" id="designation" name="designation" value="<?php echo htmlspecialchars($depense['designation']); ?>" required><br><br>

            <label for="quantite">Quantité:</label>
            <input type="number" id="quantite" name="quantite" value="<?php echo htmlspecialchars($depense['quantite']); ?>" required><br><br>

            <label for="prix_unitaire">Prix Unitaire:</label>
            <input type="number" step="0.01" id="prix_unitaire" name="prix_unitaire" value="<?php echo htmlspecialchars($depense['prix_unitaire']); ?>" required><br><br>

            <label for="montant">Montant:</label>
            <input type="number" step="0.01" id="montant" name="montant" value="<?php echo htmlspecialchars($depense['montant']); ?>" readonly><br><br>

            <button type="submit">Mettre à Jour</button>
          </form>

          <script>
            // Fonction pour calculer le montant (copiée depuis edit_script.js)
            function calculateAmount() {
              const quantite = parseFloat(document.getElementById('quantite').value);
              const prix_unitaire = parseFloat(document.getElementById('prix_unitaire').value);

              if (!isNaN(quantite) && !isNaN(prix_unitaire)) {
                const montant = quantite * prix_unitaire;
                document.getElementById('montant').value = montant.toFixed(2);
              } else {
                document.getElementById('montant').value = '';
              }
            }

            // Attacher les événements aux champs quantite et prix_unitaire
            document.getElementById('quantite').addEventListener('input', calculateAmount);
            document.getElementById('prix_unitaire').addEventListener('input', calculateAmount);

            // Calculer le montant initial
            calculateAmount();
          </script>
    <?php
        } else {
          echo "<p>Dépense non trouvée.</p>";
        }
      } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
      }
    } else {
      echo "<p>ID de dépense non spécifié.</p>";
    }

    $conn = null;
    ?>
  </main>
  <footer class="footer">
    <div class="footer-container">
      <!-- Section Logo et Description -->
      <div class="footer-logo">
        <img src="photo/digitale.jpg" alt="Logo">
        <p>MON SITE IA description</p>
      </div>

      <!-- Section Navigation -->
      <div class="footer-links">
        <h3>Navigation</h3>
        <ul>
          <li><a href="index.php">Accueil</a></li>
          <li><a href="apropos.php">À Propos</a></li>
          <li><a href="actualites.php">Actualités</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
      </div>

      <!-- Section Réseaux Sociaux -->
      <div class="footer-social">
        <h3>Membres du Projet</h3>
        <ul class="members">
          <li>KINDA Ulrich</li>
          <li>DIENDERE Steve</li>
          <li>OUEDRAOGO Ambroise</li>
          <li>SORE Sarifatou</li>
          <li>ZONGO Romeo</li>
          <li>OUEDRAOGO W Yacouba</li>
          <li>MANO Marius</li>
          <li>Guira Soumailla</li>
          <li>Bance OUsseni</li>
          <li>ABADJAN Pauline</li>
          <li>Nikiema Samira</li>
          <li>TCHONOWOU Pricille</li>
          <li>BIOGO A Karim</li>
          <li>SAVADOGO I W Kevin</li>
          <li>KABORE Aboubacar</li>
        </ul>
      </div>

      <!-- Section Contact -->
      <div class="footer-contact">
        <h3>Contact</h3>
        <ul>
          <li><i class="fas fa-phone"></i> <a href="tel:+226 64-35-34-48">+226 64-35-34-48</a></li>
          <li><i class="fas fa-envelope"></i> <a href="mailto:projetIA@gmail.com">projetIA@gmail.com</a></li>
        </ul>
      </div>
    </div>

    <!-- Copyright -->
    <div class="footer-bottom">
      <p>© 2025 MON SITE IA. Tous droits réservés.</p>
    </div>
  </footer>
</body>

</html>