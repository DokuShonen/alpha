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
 * Budget des depenses.
 *
 * Le tableau est charge par JavaScript : la page reste affichee meme si la
 * base de donnees est indisponible.
 */
require_once $app_dir . '/config/db.php';
require_once $app_dir . '/includes/header.php';
?>

<section class="page-section">
    <p class="eyebrow">Budget &amp; approvisionnements</p>
    <h1 class="page-title">Suivi des depenses</h1>
    <p class="lede">
        Chaque materiel, chaque capteur et chaque abonnement du laboratoire est
        declare ici. Le montant est recalcule par le serveur a l'enregistrement.
    </p>

    <form id="depense-form" class="card-form" method="post" action="<?php echo BASE_URL; ?>actions/traitement.php">
        <?php echo csrf_field(); ?>

        <div class="form-group">
            <label for="designation">Designation</label>
            <input type="text" id="designation" name="designation" required maxlength="255" placeholder="Carte de developpement IoT">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="quantite">Quantite</label>
                <input type="number" id="quantite" name="quantite" min="1" step="1" value="1" required>
            </div>

            <div class="form-group">
                <label for="prix_unitaire">Prix unitaire</label>
                <input type="number" id="prix_unitaire" name="prix_unitaire" min="0.01" step="0.01" value="0" required>
            </div>

            <div class="form-group">
                <label for="montant">Montant</label>
                <input type="text" id="montant" name="montant" readonly aria-readonly="true" placeholder="0,00 FCFA">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Ajouter la depense</button>
        </div>
    </form>

    <div class="table-wrap">
        <table id="depenses-table">
            <thead>
                <tr>
                    <th>N</th>
                    <th>Designation</th>
                    <th>Quantite</th>
                    <th>Prix unitaire</th>
                    <th class="montant">Montant</th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <p class="total-line">
        Total general
        <strong id="total-value">0,00 FCFA</strong>
    </p>
</section>

<?php require_once $app_dir . '/includes/footer.php'; ?>
