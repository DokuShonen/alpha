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
 * Modification d'une depense : la page.
 */
require_once $app_dir . '/config/db.php';
require_once $app_dir . '/includes/connexion_db.php';
require_once $app_dir . '/includes/csrf.php';

$depense = null;
$erreur = null;
$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    try {
        $stmt = db()->prepare('SELECT id, designation, quantite, prix_unitaire, montant FROM depenses WHERE id = ?');
        $stmt->execute([$id]);
        $depense = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('ALPHA - lecture de la depense : ' . $e->getMessage());
        $erreur = 'La base de donnees est indisponible.';
    }

    if ($depense === null && $erreur === null) {
        $erreur = 'Cette depense est introuvable.';
    }
} else {
    $erreur = 'Aucune depense a ete selectionnee.';
}

require_once $app_dir . '/includes/header.php';
?>

<section class="page-section">
    <p class="eyebrow">Budget &amp; approvisionnements</p>
    <h1 class="page-title">Modifier la depense</h1>

    <?php if ($erreur !== null): ?>
        <p class="alert alert-error"><?php echo htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8'); ?></p>
        <a href="<?php echo BASE_URL; ?>formulaire.php" class="btn btn-ghost">Retour au budget</a>
    <?php else: ?>
        <form id="edit-depense-form" class="card-form" data-action="<?php echo BASE_URL; ?>actions/update_depense.php" method="post">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo (int) $depense['id']; ?>">

            <div class="form-group">
                <label for="designation">Designation</label>
                <input type="text" id="designation" name="designation" value="<?php echo htmlspecialchars($depense['designation'], ENT_QUOTES, 'UTF-8'); ?>" required maxlength="255">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="quantite">Quantite</label>
                    <input type="number" id="quantite" name="quantite" value="<?php echo (int) $depense['quantite']; ?>" min="1" step="1" required>
                </div>

                <div class="form-group">
                    <label for="prix_unitaire">Prix unitaire</label>
                    <input type="number" id="prix_unitaire" name="prix_unitaire" value="<?php echo htmlspecialchars((string) $depense['prix_unitaire'], ENT_QUOTES, 'UTF-8'); ?>" min="0.01" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="montant">Montant</label>
                    <input type="text" id="montant" name="montant" value="<?php echo number_format((float) $depense['montant'], 2, ',', ' '); ?> FCFA" readonly aria-readonly="true">
                </div>
            </div>

            <p class="form-hint">Le montant est recalcule par le serveur a l'enregistrement.</p>

            <div class="form-actions">
                <button type="submit" class="btn">Enregistrer</button>
                <a href="<?php echo BASE_URL; ?>formulaire.php" class="btn btn-ghost">Annuler</a>
            </div>
        </form>
    <?php endif; ?>
</section>

<?php require_once $app_dir . '/includes/footer.php'; ?>
