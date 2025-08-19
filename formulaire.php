<?php require_once 'includes/connexion_db.php';
require_once 'includes/header.php'; ?>

<form id="depense-form" method="post" action="actions/traitement.php">
    <h2>Nouvelle Dépense</h2>
    <label for="designation">Désignation:</label>
    <input type="text" id="designation" name="designation" required>

    <label for="quantite">Quantité:</label>
    <input type="number" id="quantite" name="quantite" required>

    <label for="prix_unitaire">Prix Unitaire:</label>
    <input type="number" step="0.01" id="prix_unitaire" name="prix_unitaire" required>

    <label for="montant">Montant:</label>
    <input type="number" step="0.01" id="montant" name="montant" readonly>

    <button type="submit">Ajouter</button>
</form>

<h2>Liste des Dépenses</h2>
<table id="depenses-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Désignation</th>
            <th>Quantité</th>
            <th>Prix Unitaire</th>
            <th>Montant</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <!-- Les données seront chargées ici par JavaScript -->
    </tbody>
</table>
<p id="montant-total">Montant total: <span id="total-value">0.00 FCFA</span></p>

<?php require_once 'includes/footer.php'; ?>